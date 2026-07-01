<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->order_code }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; line-height: 1.4; }
        .invoice-box { max-width: 800px; margin: auto; padding: 20px; font-size: 14px; }
        .header { border-bottom: 2px solid #C9A84C; padding-bottom: 15px; margin-bottom: 20px; }
        .title { font-size: 26px; font-weight: bold; color: #0A0A0A; letter-spacing: 1px; }
        .meta-table { width: 100%; margin-bottom: 30px; }
        .meta-table td { vertical-align: top; font-size: 13px; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th { background-color: #0A0A0A; color: #C9A84C; padding: 10px; font-size: 12px; text-transform: uppercase; text-align: left; }
        .items-table td { padding: 10px; border-bottom: 1px solid #eee; font-size: 13px; }
        .text-right { text-align: right; }
        .total-box { float: right; width: 300px; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <table width="100%">
                <tr>
                    <td class="title">SAMAWA FASHION</td>
                    <td align="right" style="font-size: 20px; color: #C9A84C; font-weight: bold;">INVOICE</td>
                </tr>
            </table>
        </div>
        
        <table class="meta-table">
            <tr>
                <td width="50%">
                    <strong>Tujuan Pengiriman:</strong><br>
                    {{ $order->recipient_name }}<br>
                    {{ $order->phone }}<br>
                    {{ $order->shipping_address }}<br>
                    {{ $order->city }}, {{ $order->postal_code }}
                </td>
                <td width="50%" align="right">
                    <strong>Detail Transaksi:</strong><br>
                    Kode Order: #{{ $order->order_code }}<br>
                    Tanggal: {{ $order->created_at->format('d M Y H:i') }}<br>
                    Metode Bayar: {{ $order->payment_method }}<br>
                    Status: <span style="text-transform: capitalize;">{{ $order->status }}</span>
                </td>
            </tr>
        </table>
        
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item Deskripsi</th>
                    <th class="text-right">Harga Satuan</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        @php
            $shippingCost = $order ? (float)$order->shipping_cost : 0;
            $subtotal = $order ? ($order->total_price - $shippingCost) : 0;
        @endphp
        
        <div class="total-box">
            <table width="100%" cellpadding="5">
                <tr>
                    <td align="right"><strong>Subtotal:</strong></td>
                    <td align="right" width="45%">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td align="right"><strong>Ongkos Kirim:</strong></td>
                    <td align="right">Rp {{ number_format($shippingCost, 0, ',', '.') }}</td>
                </tr>
                <tr style="border-top: 1px solid #ddd; font-size: 15px; font-weight: bold;">
                    <td align="right"><strong>Total Akhir:</strong></td>
                    <td align="right" style="color: #C9A84C;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
