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
            <!-- Checkout Form Column -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm" style="border-radius: 10px;">
                    <div class="card-header bg-black text-gold" style="color: var(--gold-color); font-weight: 600; border-radius: 10px 10px 0 0;">
                        <i class="fas fa-shipping-fast me-2"></i> Informasi Pengiriman & Pembayaran
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('checkout.store') }}" method="POST">
                            @csrf
                            
                            <h5 class="mb-3 fw-semibold border-bottom pb-2">Data Penerima</h5>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="use_profile_data">
                                <label class="form-check-label small fw-semibold" for="use_profile_data">
                                    Gunakan data profil saya
                                </label>
                            </div>

                            <div class="mb-3">
                                <label for="recipient_name" class="form-label">Nama Penerima <span class="text-danger">*</span></label>
                                <input type="text" name="recipient_name" id="recipient_name" class="form-control @error('recipient_name') is-invalid @enderror" value="{{ old('recipient_name', $user->name) }}" required placeholder="Nama lengkap penerima paket">
                                @error('recipient_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Nomor HP <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" required placeholder="Nomor telepon aktif, cth: 0812345678">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="postal_code" class="form-label">Kode Pos <span class="text-danger">*</span></label>
                                    <input type="text" name="postal_code" id="postal_code" class="form-control @error('postal_code') is-invalid @enderror" value="{{ old('postal_code') }}" required placeholder="Cth: 12345">
                                    @error('postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="city" class="form-label">Kota / Kabupaten <span class="text-danger">*</span></label>
                                    <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}" required placeholder="Nama Kota atau Kabupaten pengiriman">
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="shipping_address" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                <textarea name="shipping_address" id="shipping_address" class="form-control @error('shipping_address') is-invalid @enderror" rows="3" required placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, dan kecamatan">{{ old('shipping_address', $user->address) }}</textarea>
                                @error('shipping_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <h5 class="mb-3 fw-semibold border-bottom pb-2">Metode Pembayaran</h5>
                            
                            <div class="mb-4">
                                <div class="form-check mb-3 p-3 border rounded">
                                    <input type="radio" name="payment_method" class="form-check-input ms-0 me-2" id="pay_bca" value="BCA" checked>
                                    <label class="form-check-label fw-semibold" for="pay_bca">
                                        <i class="fas fa-university text-gold me-2" style="color: var(--gold-color);"></i> Transfer Bank BCA (Verifikasi Manual)
                                    </label>
                                    <p class="text-muted small ms-4 mb-0 mt-1">Lakukan transfer ke rekening BCA Samawa Fashion. Bukti transfer harus diunggah setelah pemesanan.</p>
                                </div>

                                <div class="form-check mb-3 p-3 border rounded">
                                    <input type="radio" name="payment_method" class="form-check-input ms-0 me-2" id="pay_bri" value="BRI">
                                    <label class="form-check-label fw-semibold" for="pay_bri">
                                        <i class="fas fa-university text-gold me-2" style="color: var(--gold-color);"></i> Transfer Bank BRI (Verifikasi Manual)
                                    </label>
                                    <p class="text-muted small ms-4 mb-0 mt-1">Lakukan transfer ke rekening BRI Samawa Fashion. Bukti transfer harus diunggah setelah pemesanan.</p>
                                </div>

                                <div class="form-check mb-3 p-3 border rounded">
                                    <input type="radio" name="payment_method" class="form-check-input ms-0 me-2" id="pay_mandiri" value="Mandiri">
                                    <label class="form-check-label fw-semibold" for="pay_mandiri">
                                        <i class="fas fa-university text-gold me-2" style="color: var(--gold-color);"></i> Transfer Bank Mandiri (Verifikasi Manual)
                                    </label>
                                    <p class="text-muted small ms-4 mb-0 mt-1">Lakukan transfer ke rekening Mandiri Samawa Fashion. Bukti transfer harus diunggah setelah pemesanan.</p>
                                </div>

                                <div class="form-check p-3 border rounded">
                                    <input type="radio" name="payment_method" class="form-check-input ms-0 me-2" id="pay_cod" value="COD">
                                    <label class="form-check-label fw-semibold" for="pay_cod">
                                        <i class="fas fa-money-bill-wave text-gold me-2" style="color: var(--gold-color);"></i> Cash On Delivery (COD)
                                    </label>
                                    <p class="text-muted small ms-4 mb-0 mt-1">Bayar langsung secara tunai kepada kurir saat barang Anda sampai di alamat tujuan.</p>
                                </div>
                                
                                @error('payment_method')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <button type="submit" class="btn-gold w-100 py-3 text-center">
                                <i class="fas fa-check me-2"></i> Buat Pesanan Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar Order Summary Column -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm" style="border-radius: 10px;">
                    <div class="card-header bg-black text-gold" style="color: var(--gold-color); font-weight: 600; border-radius: 10px 10px 0 0;">
                        <i class="fas fa-shopping-bag me-2"></i> Ringkasan Pesanan
                    </div>
                    <div class="card-body p-4">
                        @foreach($cart->items as $item)
                            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                                <div style="max-width: 70%;">
                                    <h6 class="mb-1 fw-semibold text-truncate">{{ $item->product->name }}</h6>
                                    <small class="text-muted">
                                        {{ $item->quantity }} x Rp {{ number_format($item->product->final_price, 0, ',', '.') }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <strong class="text-gold" style="color: var(--gold-color);">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        @endforeach
                        
                         <div class="d-flex justify-content-between mb-3 mt-4">
                            <span>Subtotal</span>
                            <span class="fw-semibold">Rp {{ number_format($cart->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Ongkos Kirim</span>
                            <span class="fw-semibold">{{ $cart->total > 500000 ? 'Gratis' : 'Rp 15.000' }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total Tagihan</strong>
                            <strong class="text-gold fs-5" style="color: var(--gold-color);">
                                Rp {{ number_format($cart->total + ($cart->total > 500000 ? 0 : 15000), 0, ',', '.') }}
                            </strong>
                        </div>
                        
                        <div class="alert alert-info small mt-4 mb-0">
                            <i class="fas fa-info-circle me-2 text-primary"></i>
                            Barang yang dibeli tidak dapat ditukar atau dikembalikan kecuali terdapat cacat produksi bawaan pabrik.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#use_profile_data').on('change', function() {
        if ($(this).is(':checked')) {
            $('#recipient_name').val('{{ $user->name }}');
            $('#phone').val('{{ $user->phone }}');
            $('#shipping_address').val('{{ $user->address }}');
        } else {
            $('#recipient_name').val('');
            $('#phone').val('');
            $('#shipping_address').val('');
        }
    });
</script>
@endpush
