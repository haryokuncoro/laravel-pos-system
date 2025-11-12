<div class="p-6 bg-gray-100 min-h-screen">
    <h2 class="text-2xl font-bold mb-4">Sale Report</h2>

    <input wire:model="search" type="text" placeholder="Search by item name..." class="w-full mb-4 p-2 border rounded-md">

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-4 py-2">Date</th>
                    <th class="border px-4 py-2">Items</th>
                    <th class="border px-4 py-2">Subtotal</th>
                    <th class="border px-4 py-2">Tax</th>
                    <th class="border px-4 py-2">Total</th>
                    <th class="border px-4 py-2">Tendered</th>
                    <th class="border px-4 py-2">Change Due</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                    <tr class="hover:bg-gray-100">
                        <td class="border px-4 py-2">{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                        <td class="border px-4 py-2">
                            <ul class="list-disc pl-4">
                                 @foreach($sale->items as $item)
                                    <li>
                                        {{ $item->name ?? $item->product->name }} x {{ $item->quantity }}
                                        @if(isset($item->price))
                                            - ${{ number_format($item->price, 2) }}
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="border px-4 py-2">${{ number_format($sale->subtotal, 2) }}</td>
                        <td class="border px-4 py-2">${{ number_format($sale->tax, 2) }}</td>
                        <td class="border px-4 py-2">${{ number_format($sale->total, 2) }}</td>
                        <td class="border px-4 py-2">${{ number_format($sale->tendered, 2) }}</td>
                        <td class="border px-4 py-2">${{ number_format($sale->change_due, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center p-4 text-gray-500">No sales found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
