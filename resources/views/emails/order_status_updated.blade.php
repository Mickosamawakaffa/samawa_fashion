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
        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
            margin: 20px 0;
        }
        .status-pending { background-color: #ffc107; color: #000; }
        .status-processing { background-color: #17a2b8; color: #fff; }
        .status-shipped { background-color: #007bff; color: #fff; }
        .status-delivered { background-color: #28a745; color: #fff; }
        .status-cancelled { background-color: #dc3545; color: #fff; }
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
            <h2>Status Pesanan Anda Diperbarui</h2>
            <p>Halo, <strong>{{ $order->recipient_name }}</strong>. Kami ingin menginformasikan bahwa status pesanan Anda dengan kode <strong>{{ $order->order_code }}</strong> telah diperbarui:</p>
            
            <div style="text-align: center;">
                <span class="status-badge status-{{ $order->status }}">
                    {{ $order->status }}
                </span>
            </div>

            <p><strong>Status Pembayaran:</strong> 
                @if($order->payment_status === 'paid')
                    Lunas (Paid)
                @elseif($order->payment_status === 'refunded')
                    Dikembalikan (Refunded)
                @else
                    Pending
                @endif
            </p>

            <p style="margin-top: 30px;">Silakan login ke akun SAMAWA Fashion Anda dan buka menu <a href="{{ route('orders.index') }}" style="color: #C9A84C; font-weight: bold; text-decoration: none;">Riwayat Pesanan</a> untuk detail status dan informasi kurir pengiriman.</p>
        </div>
        <div class="footer">
            &copy; 2026 SAMAWA Fashion Indonesia. All Rights Reserved.
        </div>
    </div>
</body>
</html>
