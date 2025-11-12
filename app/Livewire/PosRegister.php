<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;

class PosRegister extends Component
{
    // Cart Properties
    public $cart = [];
    public $search = '';
    public $taxRate = 0.10; // 10% tax example

    // Payment Properties
    public $paymentMethod = 'Cash';
    public $tenderedAmount = 0.00;

    // Computed Properties (Livewire automatically recalculates these)

    public function getProductsProperty()
    {
        // Search for products by name or return all products
        return Product::where('stock', '>', 0) // Only show in-stock items
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy('name')
            ->get();
    }

    public function getSubtotalProperty()
    {
        return array_sum(array_map(fn ($item) => $item['price'] * $item['qty'], $this->cart));
    }

    public function getTaxAmountProperty()
    {
        return $this->subtotal * $this->taxRate;
    }

    public function getTotalAmountProperty()
    {
        return $this->subtotal + $this->taxAmount;
    }

    public function getChangeDueProperty()
    {
        return max(0, $this->tenderedAmount - $this->totalAmount);
    }

    // Cart Methods

    public function addToCart(Product $product)
    {
        $id = $product->id;
        
        if (isset($this->cart[$id])) {
            // Check stock before incrementing
            if ($this->cart[$id]['qty'] < $product->stock) {
                 $this->cart[$id]['qty']++;
            } else {
                 session()->flash('error', 'Cannot add more, maximum stock reached.');
            }
        } else {
            // Add new item
            $this->cart[$id] = [
                'id' => $id,
                'name' => $product->name,
                'price' => $product->price,
                'qty' => 1,
                'stock' => $product->stock,
            ];
        }
        $this->search = ''; // Clear search field
    }

    public function updateQty($productId, $action)
    {
        if (!isset($this->cart[$productId])) return;

        if ($action === 'increase') {
            if ($this->cart[$productId]['qty'] < $this->cart[$productId]['stock']) {
                $this->cart[$productId]['qty']++;
            } else {
                 session()->flash('error', 'Maximum stock reached for this item.');
            }
        } elseif ($action === 'decrease') {
            $this->cart[$productId]['qty']--;
            if ($this->cart[$productId]['qty'] < 1) {
                unset($this->cart[$productId]); // Remove if quantity hits zero
            }
        }
    }

    public function removeItem($productId)
    {
        unset($this->cart[$productId]);
    }

    public function clearCart()
    {
        $this->cart = [];
        $this->tenderedAmount = 0.00;
    }

    // Transaction Method

    public function checkout()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'The cart is empty. Add products to continue.');
            return;
        }

        if ($this->tenderedAmount < $this->totalAmount) {
             session()->flash('error', 'Tendered amount is less than total amount.');
            return;
        }

        DB::transaction(function () {
            // 1. Create the Sale record (Header)
            $sale = Sale::create([
                'user_id' => auth()->id(),
                'subtotal' => $this->subtotal,
                'tax_amount' => $this->taxAmount,
                'total_amount' => $this->totalAmount,
                'payment_method' => $this->paymentMethod,
                'tendered_amount' => $this->tenderedAmount,
            ]);

            // 2. Add Sale Items and Update Stock
            foreach ($this->cart as $item) {
                // a. Create SaleItem record
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'unit_price' => $item['price'],
                ]);

                // b. Deduct Stock
                Product::where('id', $item['id'])->decrement('stock', $item['qty']);
            }

            // 3. Clear the State and Notify
            $changeDue = $this->changeDue;
            $this->clearCart();
            session()->flash('success', "Sale successful! Change Due: $" . number_format($changeDue, 2));
        });
    }


    public function render()
    {
        return view('livewire.pos-register', [
            'productsList' => $this->products, // Use the computed property name
        ])->layout('layouts.app');
    }
}