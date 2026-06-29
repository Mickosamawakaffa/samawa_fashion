@extends('layouts.frontend')

@section('title', 'Wishlist - Samawa Fashion')

@section('content')
<div class="py-5">
    <div class="container">
        <div class="section-title">
            <h2>Wishlist Saya</h2>
            <div class="divider"></div>
        </div>
        
        @if($wishlistItems->count() > 0)
            <div class="row">
                @foreach($wishlistItems as $item)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="product-card">
                            <div class="product-image">
                                <img src="{{ $item->product->main_image ?? 'https://via.placeholder.com/400x500?text=' . $item->product->name }}" alt="{{ $item->product->name }}">
                                <div class="product-actions">
                                    <button onclick="addToCart({{ $item->product->id }})" title="Add to Cart">
                                        <i class="fas fa-shopping-cart"></i>
                                    </button>
                                    <button onclick="removeFromWishlist({{ $item->product->id }})" title="Remove from Wishlist" class="btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <a href="{{ route('products.show', $item->product->slug) }}" class="btn btn-gold" style="width: auto; border-radius: 20px; padding: 8px 20px;">
                                        Detail
                                    </a>
                                </div>
                            </div>
                            <div class="product-info">
                                <p class="product-category">{{ $item->product->category->name }}</p>
                                <a href="{{ route('products.show', $item->product->slug) }}">
                                    <h5 class="product-name">{{ $item->product->name }}</h5>
                                </a>
                                <div class="product-price">
                                    Rp {{ number_format($item->product->final_price, 0, ',', '.') }}
                                    @if($item->product->discount > 0)
                                        <span class="product-old-price">Rp {{ number_format($item->product->price, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-heart fa-5x text-muted mb-4"></i>
                <h3 class="text-muted">Wishlist Kosong</h3>
                <p class="text-muted mb-4">Belum ada produk di wishlist Anda</p>
                <a href="{{ route('products.index') }}" class="btn-gold">
                    <i class="fas fa-shopping-bag me-2"></i> Jelajahi Produk
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function addToCart(productId) {
        $.ajax({
            url: '{{ route('cart.add') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId,
                quantity: 1
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Produk ditambahkan ke keranjang',
                    timer: 2000,
                    showConfirmButton: false
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
    
    function removeFromWishlist(productId) {
        Swal.fire({
            icon: 'warning',
            title: 'Hapus dari Wishlist',
            text: 'Yakin ingin menghapus produk ini dari wishlist?',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('wishlist.remove') }}',
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: productId
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Produk dihapus dari wishlist',
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
