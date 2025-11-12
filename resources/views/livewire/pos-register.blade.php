<div class="p-6 bg-gray-100 min-h-screen">
    
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="md:col-span-2 bg-white p-4 shadow rounded-lg">
            <h3 class="text-xl font-semibold mb-4">Products</h3>
            
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search products by name..." class="w-full mb-4 p-2 border rounded-md">

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 max-h-[70vh] overflow-y-auto">
                @forelse ($productsList as $product)
                    <div wire:click="addToCart({{ $product->id }})" class="border p-3 rounded-lg shadow hover:shadow-lg transition cursor-pointer bg-gray-50 flex flex-col justify-between">
                        <div>
                            <p class="font-bold text-lg text-gray-800">{{ $product->name }}</p>
                            <p class="text-sm text-gray-500">{{ $product->description }}</p>
                        </div>
                        <div class="mt-2 flex justify-between items-center">
                            <span class="text-green-600 font-extrabold">${{ number_format($product->price, 2) }}</span>
                            <span class="text-xs text-gray-400">Stock: {{ $product->stock }}</span>
                        </div>
                    </div>
                @empty
                    <p class="col-span-4 text-center text-gray-500">No products found or in stock.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white p-4 shadow rounded-lg">
            <h3 class="text-xl font-semibold mb-4">Current Cart (Items: {{ count($cart) }})</h3>
            
            <div class="max-h-60 overflow-y-auto mb-4">
                @forelse ($cart as $item)
                    <div class="flex items-center justify-between border-b py-2">
                        <div class="flex-1">
                            <p class="font-medium">{{ $item['name'] }}</p>
                            <p class="text-xs text-gray-500">${{ number_format($item['price'], 2) }} x {{ $item['qty'] }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button wire:click="updateQty({{ $item['id'] }}, 'decrease')" class="text-red-500 hover:text-red-700 font-bold px-1 text-lg">-</button>
                            <span class="w-6 text-center">{{ $item['qty'] }}</span>
                            <button wire:click="updateQty({{ $item['id'] }}, 'increase')" class="text-green-500 hover:text-green-700 font-bold px-1 text-lg">+</button>
                            <button wire:click="removeItem({{ $item['id'] }})" class="text-gray-400 hover:text-red-500 ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500">Cart is empty.</p>
                @endforelse
            </div>
            
            <div class="space-y-1 border-t pt-3">
                <div class="flex justify-between"><span>Subtotal:</span><span class="font-medium">${{ number_format($this->subtotal, 2) }}</span></div>
                <div class="flex justify-between"><span>Tax ({{ $taxRate*100 }}%):</span><span class="font-medium">${{ number_format($this->taxAmount, 2) }}</span></div>
                <div class="flex justify-between text-lg font-bold"><span>TOTAL:</span><span>${{ number_format($this->totalAmount, 2) }}</span></div>
            </div>
            
            <div class="mt-4">
                <label for="tendered" class="block text-sm font-medium text-gray-700">Amount Tendered</label>
                <input wire:model.live="tenderedAmount" type="number" step="0.01" min="{{ $this->totalAmount }}" class="mt-1 block w-full p-2 border rounded-md focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="flex justify-between text-xl font-extrabold mt-4 p-2 bg-indigo-50 rounded text-indigo-800">
                <span>Change Due:</span>
                <span>${{ number_format($this->changeDue, 2) }}</span>
            </div>

            <button 
                wire:click="checkout" 
                @if($this->totalAmount <= 0 || $this->tenderedAmount < $this->totalAmount) disabled @endif
                class="mt-4 w-full py-3 rounded-lg text-white font-bold transition 
                @if($this->totalAmount > 0 && $this->tenderedAmount >= $this->totalAmount) bg-green-600 hover:bg-green-700 @else bg-gray-400 cursor-not-allowed @endif"
            >
                Finalize Sale
            </button>
            <button wire:click="clearCart" class="mt-2 w-full py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200">
                Cancel Sale / Clear Cart
            </button>
        </div>

    </div>
</div>