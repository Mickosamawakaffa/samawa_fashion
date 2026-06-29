@extends('layouts.frontend')

@section('title', 'Keranjang - Samawa Fashion')

@section('content')
<div class="py-5">
    <div class="container">
        <div class="section-title">
            <h2>Keranjang Belanja</h2>
            <div class="divider"></div>
        </div>
        
        @if($cart->items->count() > 0)
            <div class="row">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-shopping-cart me-2"></i> Produk di Keranjang
                        </div>
                        <div class="card-body">
                            @foreach($cart->items as $item)
                                <div class="row mb-4 pb-4 border-bottom">
                                    <div class="col-md-3">
                                        <img src="{{ asset('storage/' . $item->product->main_image) }}" 
                                             alt="{{ $item->product->name }}" 
                                             class="img-fluid rounded">
                                    </div>
                                    <div class="col-md-9">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h5>{{ $item->product->name }}</h5>
                                                <p class="text-muted mb-2">{{ $item->product->category->name }}</p>
                                                @if($item->size)
                                                    <span class="badge bg-secondary me-1">Ukuran: {{ $item->size }}</span>
                                                @endif
                                                @if($item->color)
                                                    <span class="badge bg-secondary">Warna: {{ $item->color }}</span>
                                                @endif
                                            </div>
                                            <div class="text-end">
                                                <h5 class="text-gold" style="color: var(--gold-color);">
                                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div class="input-group" style="width: 120px;">
                                                <button class="btn btn-outline-dark" onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})">-</button>
                                                <input type="number" class="form-control text-center" value="{{ $item->quantity }}" readonly>
                                                <button class="btn btn-outline-dark" onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})">+</button>
                                            </div>
                                            <button class="btn btn-danger btn-sm" onclick="removeItem({{ $item->id }})">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-calculator me-2"></i> Ringkasan Pesanan
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($cart->total, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Ongkos Kirim</span>
                                <span>Dihitung saat checkout</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-4">
                                <strong>Total</strong>
                                <strong class="text-gold" style="color: var(--gold-color);">
                                    Rp {{ number_format($cart->total, 0, ',', '.') }}
                                </strong>
                            </div>
                            <a href="{{ route('checkout.index') }}" class="btn-gold w-100 text-center d-block">
                                <i class="fas fa-credit-card me-2"></i> Checkout
                            </a>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-dark w-100 text-center d-block mt-2">
                                <i class="fas fa-arrow-left me-2"></i> Lanjut Belanja
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
                <h3 class="text-muted">Keranjang Anda Kosong</h3>
                <p class="text-muted mb-4">Belum ada produk di keranjang belanja Anda</p>
                <a href="{{ route('products.index') }}" class="btn-gold">
                    <i class="fas fa-shopping-bag me-2"></i> Mulai Belanja
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function updateQuantity(itemId, quantity) {
        if (quantity < 1) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Jumlah minimal 1',
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }
        
        $.ajax({
            url: '{{ route('cart.update') }}',
            method: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                cart_item_id: itemId,
                quantity: quantity
            },
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan'
                });
            }
        });
    }
    
    function removeItem(itemId) {
        Swal.fire({
            icon: 'warning',
            title: 'Hapus Produk',
            text: 'Yakin ingin menghapus produk ini dari keranjang?',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('cart.remove') }}',
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                        cart_item_id: itemId
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Produk dihapus dari keranjang',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Terjadi kesalahan'
                        });
                    }
                });
            }
        });
    }
</script>
@endpush
