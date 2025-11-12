    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sale Report') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="border px-4 py-2">Date</th>
                                    <th class="border px-4 py-2">Items</th>
                                    <th class="border px-4 py-2">Subtotal</th>
                                    <th class="border px-4 py-2">Tax</th>
                                    <th class="border px-4 py-2">Total</th>
                                    <th class="border px-4 py-2">Tendered</th>
                                    <th class="border px-4 py-2">Payment Method</th>
                                    <th class="border px-4 py-2">Invoice</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sales as $sale)
                                    <tr class="hover:bg-gray-100">
                                        <td class="border px-4 py-2">{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="border px-4 py-2">
                                            <ul class="list-disc pl-4">
                                                @foreach ($sale->items as $item)
                                                    <li>
                                                        {{ $item->name ?? $item->product->name }} x
                                                        {{ $item->quantity }}
                                                        @if (isset($item->price))
                                                            - ${{ number_format($item->price, 2) }}
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td class="border px-4 py-2">${{ number_format($sale->subtotal, 2) }}</td>
                                        <td class="border px-4 py-2">${{ number_format($sale->tax_amount, 2) }}</td>
                                        <td class="border px-4 py-2">${{ number_format($sale->total_amount, 2) }}</td>
                                        <td class="border px-4 py-2">${{ number_format($sale->tendered_amount, 2) }}
                                        </td>
                                        <td class="border px-4 py-2">{{ $sale->payment_method }}</td>
                                        <td class="border px-4 py-2 text-center">
                                            <a href="{{ route('invoice.preview', $sale) }}" target="_blank"
                                                class="text-blue-600 hover:underline">🖨️ View</a>
                                            |
                                            <a href="{{ route('invoice.download', $sale) }}"
                                                class="text-green-600 hover:underline">⬇️ PDF</a>
                                        </td>

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
            </div>
        </div>
    </div>
