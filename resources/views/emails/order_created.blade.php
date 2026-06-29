<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: 'Poppins', 'Helvetica Neue', Arial, sans-serif;
            background-color: #F5F0E8;
            color: #0A0A0A;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #0A0A0A;
            color: #C9A84C;
            text-align: center;
            padding: 30px 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 2px;
            font-family: 'Playfair Display', serif;
        }
        .content {
            padding: 30px;
        }
        .order-meta {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #dee2e6;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .table th {
            background-color: #0A0A0A;
            color: #C9A84C;
            font-size: 14px;
            text-transform: uppercase;
        }
        .text-right {
            text-align: right;
        }
        .text-gold {
            color: #C9A84C;
        }
        .bank-details {
            background-color: #F5F0E8;
            border: 1px solid #C9A84C;
            padding: 20px;
            margin-top: 20px;
            border-radius: 4px;
        }
        .footer {
            background-color: #0A0A0A;
            color: #F5F0E8;
            text-align: center;
            padding: 20px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SAMAWA FASHION</h1>
        </div>
        <div class="content">
            <h2>Terima Kasih Atas Pesanan Anda!</h2>
            <p>Halo, <strong>{{ $order->recipient_name }}</strong>. Pesanan Anda dengan kode <strong>{{ $order->order_code }}</strong> telah berhasil dibuat.</p>
            
            <div class="order-meta">
                <p><strong>Tanggal Transaksi:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                <p><strong>Metode Pembayaran:</strong> {{ $order->payment_method === 'COD' ? 'Cash on Delivery (COD)' : 'Transfer Bank ' . $order->payment_method }}</p>
                <p><strong>Status Pesanan:</strong> <span style="text-transform: capitalize;">{{ $order->status }}</span></p>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="text-right">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2" class="text-right">Subtotal</td>
                        <td class="text-right">Rp {{ number_format($order->total_price - ($order->total_price > 500000 ? 0 : 15000), 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-right">Ongkos Kirim</td>
                        <td class="text-right">Rp {{ number_format($order->total_price > 500000 ? 0 : 15000, 0, ',', '.') }}</td>
                    </tr>
                    <tr style="font-weight: bold; background-color: #f8f9fa;">
                        <td colspan="2" class="text-right">Total Tagihan</td>
                        <td class="text-right text-gold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            @if($order->payment_method !== 'COD')
                <div class="bank-details">
                    <h3 style="margin-top: 0; color: #0A0A0A;">Petunjuk Transfer Pembayaran</h3>
                    <p>Silakan transfer total tagihan tepat sebesar <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong> ke rekening berikut:</p>
                    <p><strong>Bank {{ $order->payment_method }}</strong></p>
                    <p><strong>No. Rekening:</strong> 
                        @if($order->payment_method === 'BCA')
                            123-456-7890
                        @elseif($order->payment_method === 'BRI')
                            9876-01-000123-53-1
                        @elseif($order->payment_method === 'Mandiri')
                            123-00-0987654-3
                        @endif
                    </p>
                    <p><strong>Atas Nama:</strong> Samawa Fashion Indonesia</p>
                    <p style="margin-bottom: 0; font-size: 13px; color: #6c757d;">*Simpan bukti transfer dan unggah melalui halaman Riwayat Pesanan pada profil Anda.</p>
                </div>
            @endif
        </div>
        <div class="footer">
            &copy; 2026 SAMAWA Fashion Indonesia. All Rights Reserved.
        </div>
    </div>
</body>
</html>
