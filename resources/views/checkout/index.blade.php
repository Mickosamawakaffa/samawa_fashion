@extends('layouts.frontend')

@section('title', 'Checkout - Samawa Fashion')

@section('content')
<div class="py-5">
    <div class="container">
        <div class="section-title">
            <h2>Checkout</h2>
            <div class="divider"></div>
        </div>
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-shipping-fast me-2"></i> Informasi Pengiriman
                    </div>
                    <div class="card-body">
                        <form action="{{ route('checkout.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control" required value="{{ $user->phone ?? old('phone') }}">
                                @error('phone')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Alamat Pengiriman <span class="text-danger">*</span></label>
                                <textarea name="shipping_address" class="form-control" rows="3" required>{{ old('shipping_address', $user->address ?? '') }}</textarea>
                                @error('shipping_address')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input type="radio" name="payment_method" class="form-check-input" id="transfer" value="transfer" checked>
                                            <label class="form-check-label" for="transfer">
                                                <i class="fas fa-university me-2"></i> Transfer Bank
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input type="radio" name="payment_method" class="form-check-input" id="ewallet" value="ewallet">
                                            <label class="form-check-label" for="ewallet">
                                                <i class="fas fa-wallet me-2"></i> E-Wallet
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input type="radio" name="payment_method" class="form-check-input" id="cod" value="cod">
                                            <label class="form-check-label" for="cod">
                                                <i class="fas fa-money-bill-wave me-2"></i> COD
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @error('payment_method')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <button type="submit" class="btn-gold w-100">
                                <i class="fas fa-check me-2"></i> Konfirmasi Pesanan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-shopping-bag me-2"></i> Ringkasan Pesanan
                    </div>
                    <div class="card-body">
                        @foreach($cart->items as $item)
                            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                                <div>
                                    <h6 class="mb-1">{{ $item->product->name }}</h6>
                                    <small class="text-muted">
                                        {{ $item->quantity }} x Rp {{ number_format($item->product->final_price, 0, ',', '.') }}
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
                            <span>Rp {{ number_format($cart->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Ongkos Kirim</span>
                            <span>Dihitung nanti</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Total</strong>
                            <strong class="text-gold" style="color: var(--gold-color);">
                                Rp {{ number_format($cart->total, 0, ',', '.') }}
                            </strong>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <small>Ongkos kirim akan dihitung setelah pesanan dikonfirmasi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
