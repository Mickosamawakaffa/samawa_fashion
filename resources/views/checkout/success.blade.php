@extends('layouts.frontend')

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
                <div class="card text-center mb-4">
                    <div class="card-body py-5">
                        <i class="fas fa-check-circle fa-5x text-success mb-4"></i>
                        <h3 class="mb-3">Terima Kasih!</h3>
                        <p class="text-muted mb-4">Pesanan Anda telah berhasil dibuat</p>
                        <div class="alert alert-info">
                            <strong>Kode Pesanan:</strong> {{ $order->order_code }}
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-info-circle me-2"></i> Detail Pesanan
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Kode Pesanan:</strong>
                                <p>{{ $order->order_code }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Tanggal:</strong>
                                <p>{{ $order->created_at->format('d F Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Status:</strong>
                                <p>
                                    <span class="badge badge-{{ $order->status }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <strong>Metode Pembayaran:</strong>
                                <p>{{ ucfirst($order->payment_method) }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Alamat Pengiriman:</strong>
                                <p>{{ $order->shipping_address }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Telepon:</strong>
                                <p>{{ $order->phone }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-shopping-bag me-2"></i> Produk yang Dipesan
                    </div>
                    <div class="card-body">
                        @foreach($order->items as $item)
                            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                                <div>
                                    <h6 class="mb-1">{{ $item->product->name }}</h6>
                                    <small class="text-muted">
                                        {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </small>
                                    @if($item->size || $item->color)
                                        <br>
                                        <small class="text-muted">
                                            @if($item->size) Ukuran: {{ $item->size }} @endif
                                            @if($item->color) | Warna: {{ $item->color }} @endif
                                        </small>
                                    @endif
                                </div>
                                <div>
                                    <strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        @endforeach
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total</strong>
                            <strong class="text-gold" style="color: var(--gold-color);">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </strong>
                        </div>
                    </div>
                </div>
                
                @if($order->payment_method !== 'cod')
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-credit-card me-2"></i> Informasi Pembayaran
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Silakan lakukan pembayaran dalam 24 jam</strong>
                            </div>
                            <p class="mb-3">Transfer ke rekening berikut:</p>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-1"><strong>Bank BCA</strong></p>
                                <p class="mb-1"><strong>No. Rekening:</strong> 1234567890</p>
                                <p class="mb-0"><strong>a.n. Samawa Fashion</strong></p>
                            </div>
                            <p class="mt-3 text-muted small">Upload bukti transfer melalui menu "Pesanan Saya"</p>
                        </div>
                    </div>
                @endif
                
                <div class="text-center">
                    <a href="{{ route('home') }}" class="btn-gold me-2">
                        <i class="fas fa-home me-2"></i> Kembali ke Beranda
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-dark">
                        <i class="fas fa-shopping-bag me-2"></i> Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
