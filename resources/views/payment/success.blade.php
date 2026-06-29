@extends('layouts.frontend')

@section('title', 'Bukti Pembayaran Berhasil - Samawa Fashion')

@section('content')
<div class="py-5">
    <div class="container">
        <div class="section-title">
            <h2>Bukti Pembayaran Berhasil</h2>
            <div class="divider"></div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card text-center mb-4">
                    <div class="card-body py-5">
                        <i class="fas fa-check-circle fa-5x text-success mb-4"></i>
                        <h3 class="mb-3">Terima Kasih!</h3>
                        <p class="text-muted mb-4">Bukti pembayaran Anda telah berhasil diupload</p>
                        <div class="alert alert-info">
                            <strong>Kode Pesanan:</strong> {{ $order->order_code }}
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-info-circle me-2"></i> Status Pembayaran
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-clock me-2"></i>
                            <strong>Pembayaran sedang diverifikasi</strong>
                        </div>
                        <p class="text-muted">Pembayaran Anda akan diverifikasi oleh tim kami dalam 1x24 jam. Anda akan menerima notifikasi setelah pembayaran diverifikasi.</p>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-shopping-bag me-2"></i> Detail Pesanan
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Kode Pesanan:</strong>
                                <p>{{ $order->order_code }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Total Pembayaran:</strong>
                                <p class="text-gold" style="color: var(--gold-color);">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Metode Pembayaran:</strong>
                                <p>{{ ucfirst($order->payment_method) }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Status Pesanan:</strong>
                                <p>
                                    <span class="badge badge-{{ $order->status }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
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
