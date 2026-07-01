@extends('layouts.frontend')

@php
    $order = $order ?? null;
@endphp

@section('title', 'Pesanan Berhasil - Samawa Fashion')

@section('content')
<div class="py-5">
    <div class="container">
        <div class="section-title">
            <h2>Pesanan Berhasil</h2>
            <div class="divider"></div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Success Card -->
                <div class="card text-center mb-4 border-0 shadow-sm" style="border-radius: 10px;">
                    <div class="card-body py-5">
                        <i class="fas fa-check-circle fa-5x text-success mb-4 animate__animated animate__zoomIn"></i>
                        <h3 class="mb-3 fw-bold">Terima Kasih Atas Pesanan Anda</h3>
                        <p class="text-muted mb-4" style="font-size: 1.1rem;">Pesanan Anda dengan kode <strong class="text-gold">{{ $order->order_code }}</strong> telah berhasil dicatat oleh sistem kami.</p>
                        
                        <div class="p-3 bg-light inline-block rounded border border-warning" style="display: inline-block;">
                            <span class="text-muted small">Total Tagihan:</span>
                            <h4 class="text-gold fw-bold mb-0" style="color: var(--gold-color);">Rp {{ number_format($order->total_price, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Instructions -->
                @if($order->payment_method === 'Midtrans')
                    <div class="card mb-4 border-0 shadow-sm" style="border-radius: 10px;">
                        <div class="card-header bg-black text-gold" style="color: var(--gold-color); font-weight: 600; border-radius: 10px 10px 0 0;">
                            <i class="fas fa-credit-card me-2"></i> Pembayaran Online Otomatis (Midtrans)
                        </div>
                        <div class="card-body p-4 text-center">
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Metode Pembayaran Online:</strong> Gunakan tombol di bawah ini untuk membayar pesanan secara instan dan otomatis.
                            </div>
                            
                            <p class="mb-4">Status Pembayaran Saat Ini: 
                                @if($order->payment_status === 'paid')
                                    <span class="badge bg-success">Lunas</span>
                                @elseif($order->payment_status === 'failed')
                                    <span class="badge bg-danger">Gagal</span>
                                @else
                                    <span class="badge bg-warning text-dark">Belum Dibayar</span>
                                @endif
                            </p>
                            
                            @if($order->payment_status !== 'paid')
                                <button id="pay-button" class="btn btn-dark btn-lg py-3 px-5 text-gold border-gold" style="border: 2px solid var(--gold-color); border-radius: 0; font-weight: 700; background-color: #000;">
                                    <i class="fas fa-wallet me-2"></i> Bayar Sekarang via Midtrans
                                </button>
                            @else
                                <div class="text-success fw-bold">
                                    <i class="fas fa-check-circle me-1"></i> Pembayaran Anda telah dikonfirmasi oleh sistem. Terima kasih!
                                </div>
                            @endif
                        </div>
                    </div>
                @elseif($order->payment_method !== 'COD')
                    <div class="card mb-4 border-0 shadow-sm" style="border-radius: 10px;">
                        <div class="card-header bg-black text-gold" style="color: var(--gold-color); font-weight: 600; border-radius: 10px 10px 0 0;">
                            <i class="fas fa-credit-card me-2"></i> Petunjuk Pembayaran Transfer Bank {{ $order->payment_method }}
                        </div>
                        <div class="card-body p-4">
                            <div class="alert alert-warning mb-4">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Penting:</strong> Silakan lakukan pembayaran dalam waktu 24 jam untuk menghindari pembatalan otomatis.
                            </div>
                            
                            <p>Silakan transfer total tagihan Anda tepat sebesar <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong> ke rekening berikut:</p>
                            
                            <div class="bg-light p-4 rounded border mb-4">
                                <h5 class="fw-bold mb-2">Bank {{ $order->payment_method }}</h5>
                                <p class="mb-2"><strong>Nomor Rekening:</strong> 
                                    @if($order->payment_method === 'BCA')
                                        123-456-7890
                                    @elseif($order->payment_method === 'BRI')
                                        9876-01-000123-53-1
                                    @elseif($order->payment_method === 'Mandiri')
                                        123-00-0987654-3
                                    @endif
                                </p>
                                <p class="mb-0"><strong>Nama Penerima:</strong> Samawa Fashion Indonesia</p>
                            </div>
                            
                            <div class="text-muted small">
                                <p class="mb-1"><strong>Langkah Selanjutnya:</strong></p>
                                <ol class="ps-3 mb-0">
                                    <li>Lakukan pembayaran melalui ATM, Mobile Banking, atau Internet Banking.</li>
                                    <li>Simpan struk atau screenshot bukti transfer Anda.</li>
                                    <li>Buka menu <a href="{{ route('orders.index') }}" class="text-gold fw-semibold">Riwayat Pesanan</a> di profil Anda.</li>
                                    <li>Pilih pesanan Anda dan unggah bukti transfer agar pesanan dapat segera diproses.</li>
                                    <li>Jika menggunakan E-Commerce Midtrans Pembayaran otomatis, Anda tidak perlu mengunggah bukti transfer manual.</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card mb-4 border-0 shadow-sm" style="border-radius: 10px;">
                        <div class="card-header bg-black text-gold" style="color: var(--gold-color); font-weight: 600; border-radius: 10px 10px 0 0;">
                            <i class="fas fa-money-bill-wave me-2"></i> Pembayaran Cash on Delivery (COD)
                        </div>
                        <div class="card-body p-4">
                            <div class="alert alert-success">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Informasi COD:</strong> Anda memilih pembayaran di tempat.
                            </div>
                            <p class="mb-0 text-muted">Silakan siapkan uang tunai pas sebesar <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong> untuk diserahkan langsung kepada petugas kurir saat mengantarkan paket pesanan Anda.</p>
                        </div>
                    </div>
                @endif
                
                <!-- Order details -->
                <div class="card mb-4 border-0 shadow-sm" style="border-radius: 10px;">
                    <div class="card-header bg-black text-gold" style="color: var(--gold-color); font-weight: 600; border-radius: 10px 10px 0 0;">
                        <i class="fas fa-info-circle me-2"></i> Detail Pengiriman & Pesanan
                    </div>
                    <div class="card-body p-4">
                        <div class="row mb-3">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <span class="text-muted small d-block">Nama Penerima</span>
                                <strong>{{ $order->recipient_name }}</strong>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted small d-block">Nomor HP</span>
                                <strong>{{ $order->phone }}</strong>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <span class="text-muted small d-block">Alamat Pengiriman</span>
                                <strong>{{ $order->shipping_address }}, {{ $order->city }}, {{ $order->postal_code }}</strong>
                            </div>
                            <div class="col-md-6">
                                <span class="text-muted small d-block">Metode Pembayaran</span>
                                <strong>{{ $order->payment_method === 'COD' ? 'Cash on Delivery (COD)' : 'Transfer Bank ' . $order->payment_method }}</strong>
                            </div>
                        </div>
                        
                        <h6 class="fw-bold mb-3 border-bottom pb-2">Produk yang Dipesan</h6>
                        @foreach($order->items as $item)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small" style="max-width: 70%;">{{ $item->product->name }} (x{{ $item->quantity }})</span>
                                <span class="small fw-semibold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                        
                        @php
                            $shippingCost = $order ? (float)$order->shipping_cost : 0;
                            $subtotal = $order ? ($order->total_price - $shippingCost) : 0;
                        @endphp
                        <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                            <span class="text-muted small">Subtotal</span>
                            <span class="small fw-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Ongkos Kirim</span>
                            <span class="small fw-semibold">{{ $shippingCost == 0 ? 'Gratis' : 'Rp ' . number_format($shippingCost, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mt-2 pt-2 border-top">
                            <strong>Total Tagihan</strong>
                            <strong class="text-gold" style="color: var(--gold-color);">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="text-center mt-4">
                    <a href="{{ route('orders.show', $order->order_code) }}" class="btn-gold me-2 py-3 px-4 text-decoration-none">
                        <i class="fas fa-info-circle me-2"></i> Lihat Detail Order
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-dark py-3 px-4" style="border-radius: 0;">
                        <i class="fas fa-shopping-bag me-2"></i> Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@if($order->payment_method === 'Midtrans' && $order->payment_token)
    @php
        $snapUrl = config('services.midtrans.is_production') 
            ? 'https://app.midtrans.com/snap/snap.js' 
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    @endphp
    <script src="{{ $snapUrl }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const payButton = document.getElementById('pay-button');
            if (payButton) {
                function triggerSnap() {
                    window.snap.pay('{{ $order->payment_token }}', {
                        onSuccess: function(result){
                            alert("Pembayaran berhasil!");
                            window.location.reload();
                        },
                        onPending: function(result){
                            alert("Menunggu pembayaran Anda.");
                            window.location.reload();
                        },
                        onError: function(result){
                            alert("Pembayaran gagal, silakan coba lagi.");
                        },
                        onClose: function(){
                            alert('Anda menutup popup sebelum menyelesaikan pembayaran.');
                        }
                    });
                }

                payButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    triggerSnap();
                });

                // Automatically open Snap popup on load
                triggerSnap();
            }
        });
    </script>
@endif

<!-- Purchase Analytics Event -->
@php
    $purchaseItems = [];
    foreach ($order->items as $item) {
        $purchaseItems[] = [
            'item_id' => (string)$item->product_id,
            'item_name' => $item->product->name,
            'item_category' => $item->product->category->name,
            'price' => (float)$item->price,
            'quantity' => (int)$item->quantity
        ];
    }
@endphp
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof window.trackPurchase === 'function') {
            window.trackPurchase(
                "{{ $order->order_code }}",
                {{ (float)$order->total_price }},
                @json($purchaseItems)
            );
        }
    });
</script>
@endsection
