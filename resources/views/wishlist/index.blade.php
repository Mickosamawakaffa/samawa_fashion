@extends('layouts.frontend')

@section('title', 'Wishlist - Samawa Fashion')

@section('content')
<div class="py-5">
    <div class="container">
        <div class="section-title">
            <h2>Wishlist Saya</h2>
            <div class="divider"></div>
        </div>
        
        <div id="wishlist-container">
            @if($wishlistItems->count() > 0)
                <div class="row">
                    @foreach($wishlistItems as $item)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4" id="wishlist-card-{{ $item->product->id }}">
                            <div class="product-card" style="position: relative; overflow: visible;">
                                <div class="product-image" style="position: relative; overflow: hidden; height: 300px; border-radius: 10px 10px 0 0;">
                                    <img src="{{ $item->product->image ? Storage::url($item->product->image) : asset('images/no-image.jpg') }}" alt="{{ $item->product->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                                    
                                    <div class="product-actions">
                                        <button onclick="removeFromWishlist({{ $item->product->id }})" title="Hapus dari Wishlist" class="btn-danger">
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
                                        <h5 class="product-name text-truncate">{{ $item->product->name }}</h5>
                                    </a>
                                    <div class="product-price mb-3">
                                        Rp {{ number_format($item->product->final_price, 0, ',', '.') }}
                                        @if($item->product->discount > 0)
                                            <span class="product-old-price">Rp {{ number_format($item->product->price, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Move to Cart Button -->
                                    <button onclick="moveToCart({{ $item->product->id }})" class="btn btn-gold w-100 btn-sm py-2" style="border-radius: 0; font-weight: 600; font-size: 0.85rem;">
                                        <i class="fas fa-shopping-cart me-1"></i> Pindah ke Keranjang
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-heart fa-5x text-muted mb-4 text-gold" style="color: var(--gold-color);"></i>
                    <h3 class="text-muted">Wishlist Kosong</h3>
                    <p class="text-muted mb-4">Belum ada produk di wishlist Anda</p>
                    <a href="{{ route('products.index') }}" class="btn-gold py-3 px-5 text-decoration-none">
                        <i class="fas fa-shopping-bag me-2"></i> Jelajahi Produk
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function moveToCart(productId) {
        $.ajax({
            url: '{{ route('wishlist.moveToCart') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });

                // Remove card from wishlist view
                $('#wishlist-card-' + productId).fadeOut(300, function() {
                    $(this).remove();
                    
                    // Check if wishlist is empty
                    if ($('[id^="wishlist-card-"]').length === 0) {
                        $('#wishlist-container').html(`
                            <div class="text-center py-5">
                                <i class="fas fa-heart fa-5x text-muted mb-4 text-gold" style="color: var(--gold-color);"></i>
                                <h3 class="text-muted">Wishlist Kosong</h3>
                                <p class="text-muted mb-4">Belum ada produk di wishlist Anda</p>
                                <a href="{{ route('products.index') }}" class="btn-gold py-3 px-5 text-decoration-none">
                                    <i class="fas fa-shopping-bag me-2"></i> Jelajahi Produk
                                </a>
                            </div>
                        `);
                    }
                });

                // Update navbar badges dynamically
                if (response.wishlist_count > 0) {
                    $('#wishlist-badge-count').text(response.wishlist_count).removeClass('d-none');
                } else {
                    $('#wishlist-badge-count').addClass('d-none');
                }

                if (response.cart_count > 0) {
                    $('#cart-badge-count').text(response.cart_count).removeClass('d-none');
                } else {
                    $('#cart-badge-count').addClass('d-none');
                }
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
            confirmButtonColor: '#C9A84C',
            cancelButtonColor: '#0A0A0A',
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
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // Remove card from view
                        $('#wishlist-card-' + productId).fadeOut(300, function() {
                            $(this).remove();
                            
                            // Check if wishlist is empty
                            if ($('[id^="wishlist-card-"]').length === 0) {
                                $('#wishlist-container').html(`
                                    <div class="text-center py-5">
                                        <i class="fas fa-heart fa-5x text-muted mb-4 text-gold" style="color: var(--gold-color);"></i>
                                        <h3 class="text-muted">Wishlist Kosong</h3>
                                        <p class="text-muted mb-4">Belum ada produk di wishlist Anda</p>
                                        <a href="{{ route('products.index') }}" class="btn-gold py-3 px-5 text-decoration-none">
                                            <i class="fas fa-shopping-bag me-2"></i> Jelajahi Produk
                                        </a>
                                    </div>
                                `);
                            }
                        });

                        // Update navbar badge count
                        if (response.count > 0) {
                            $('#wishlist-badge-count').text(response.count).removeClass('d-none');
                        } else {
                            $('#wishlist-badge-count').addClass('d-none');
                        }
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
