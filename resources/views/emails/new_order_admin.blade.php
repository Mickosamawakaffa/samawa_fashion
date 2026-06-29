<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
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
            padding: 20px;
        }
        .content {
            padding: 30px;
        }
        .btn {
            display: inline-block;
            background-color: #C9A84C;
            color: #0A0A0A;
            padding: 12px 30px;
            text-decoration: none;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 20px;
        }
        .footer {
            background-color: #0A0A0A;
            color: #F5F0E8;
            text-align: center;
            padding: 15px;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>SAMAWA FASHION ADMIN PANEL</h2>
        </div>
        <div class="content">
            <h3>Halo Admin, Pesanan Baru Masuk!</h3>
            <p>Pemesanan baru dengan kode <strong>{{ $order->order_code }}</strong> telah berhasil dilakukan oleh customer.</p>
            <p><strong>Nama Customer:</strong> {{ $order->user->name }}</p>
            <p><strong>Total Transaksi:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
            <p><strong>Metode Pembayaran:</strong> {{ $order->payment_method }}</p>
            <p>Silakan buka admin panel untuk memproses pesanan ini.</p>
            
            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn">Proses Pesanan</a>
        </div>
        <div class="footer">
            SAMAWA Fashion Admin Notification System
        </div>
    </div>
</body>
</html>
