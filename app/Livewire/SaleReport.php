<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sale;

class SaleReport extends Component
{
    public $search = '';

    public function render()
    {
        $sales = Sale::latest()
            ->when($this->search, function($query){
                $query->where('items', 'like', '%'.$this->search.'%');
            })
            ->get();

        return view('livewire.sale-report', ['sales' => $sales])->layout('layouts.app');
    }
}
