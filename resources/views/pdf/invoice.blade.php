<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $sale->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border-bottom: 1px solid #ddd; padding: 6px; text-align: left; }
        .totals td { font-weight: bold; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Invoice #{{ $sale->id }}</h2>
        <p>Date: {{ $sale->created_at->format('d M Y, H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th class="right">Qty</th>
                <th class="right">Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? $item->name }}</td>
                    <td class="right">{{ $item->quantity }}</td>
                    <td class="right">{{ number_format($item->price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="totals">
                <td colspan="5" class="right">Subtotal</td>
                <td class="right">${{ number_format($sale->subtotal, 2) }}</td>
            </tr>
            <tr class="totals">
                <td colspan="5" class="right">Tax</td>
                <td class="right">${{ number_format($sale->tax_amount, 2) }}</td>
            </tr>
            <tr class="totals">
                <td colspan="5" class="right">Total</td>
                <td class="right">${{ number_format($sale->total_amount, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <p style="text-align:center; margin-top:30px;">Thank you for shopping with us!</p>
</body>
</html>
