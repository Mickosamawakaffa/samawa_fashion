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

            @if($order->status === 'shipped')
                <div style="margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-left: 4px solid #C9A84C; border-radius: 4px; text-align: left;">
                    <h4 style="margin-top: 0; color: #0A0A0A; border-bottom: 1px solid #dee2e6; padding-bottom: 8px;">🚚 Informasi Pengiriman</h4>
                    <p style="margin-bottom: 8px; font-size: 14px;"><strong>Kurir:</strong> {{ strtoupper($order->courier) }} - {{ $order->courier_service }}</p>
                    <p style="margin-bottom: 8px; font-size: 14px;"><strong>Nomor Resi:</strong> <span style="font-family: monospace; font-size: 14px; background: #e9ecef; padding: 2px 6px; border-radius: 3px; font-weight: bold;">{{ $order->tracking_number }}</span></p>
                    @if($order->estimated_delivery)
                        <p style="margin-bottom: 15px; font-size: 14px;"><strong>Estimasi Tiba:</strong> {{ $order->estimated_delivery }}</p>
                    @endif
                    
                    @php
                        $trackingUrl = '#';
                        $courierLower = strtolower($order->courier);
                        if (strpos($courierLower, 'jne') !== false) {
                            $trackingUrl = 'https://www.jne.co.id/id/tracking/trace?awb=' . $order->tracking_number;
                        } elseif (strpos($courierLower, 'j&t') !== false || strpos($courierLower, 'jnt') !== false) {
                            $trackingUrl = 'https://www.jet.co.id/track?awb=' . $order->tracking_number;
                        } elseif (strpos($courierLower, 'sicepat') !== false) {
                            $trackingUrl = 'https://www.sicepat.com/checkAwb?awb=' . $order->tracking_number;
                        }
                    @endphp
                    @if($trackingUrl !== '#')
                        <div style="margin-top: 15px; text-align: center;">
                            <a href="{{ $trackingUrl }}" target="_blank" style="background-color: #C9A84C; color: #0A0A0A; padding: 10px 20px; text-decoration: none; font-weight: bold; border-radius: 4px; display: inline-block; font-size: 14px;">Lacak Paket Sekarang</a>
                        </div>
                    @endif
                </div>
            @elseif($order->status === 'delivered')
                <div style="margin-top: 20px; padding: 20px; background-color: #f1f8e9; border-left: 4px solid #28a745; border-radius: 4px; text-align: left;">
                    <h4 style="margin-top: 0; color: #28a745; border-bottom: 1px solid #c3e6cb; padding-bottom: 8px;">✨ Terima Kasih Atas Kepercayaan Anda!</h4>
                    <p style="font-size: 14px;">Pesanan Anda telah diterima dengan baik. Kami sangat senang bisa menjadi bagian dari gaya busana Anda.</p>
                    <p style="font-size: 14px;">Bagaimana kualitas produk yang Anda terima? Yuk, bagikan ulasan/review Anda untuk produk yang telah dibeli!</p>
                    
                    <div style="margin-top: 20px; text-align: center;">
                        <a href="{{ route('orders.show', $order->order_code) }}" style="background-color: #0A0A0A; color: #C9A84C; padding: 10px 20px; text-decoration: none; font-weight: bold; border-radius: 4px; display: inline-block; border: 1px solid #C9A84C; font-size: 14px;">Tulis Ulasan Produk</a>
                    </div>
                </div>
            @endif

            <p style="margin-top: 30px;">Silakan login ke akun SAMAWA Fashion Anda dan buka menu <a href="{{ route('orders.index') }}" style="color: #C9A84C; font-weight: bold; text-decoration: none;">Riwayat Pesanan</a> untuk detail status.</p>
        </div>
        <div class="footer">
            &copy; 2026 SAMAWA Fashion Indonesia. All Rights Reserved.
        </div>
    </div>
</body>
</html>
