@extends('layouts.frontend')

@section('title', 'Produk - Samawa Fashion')

@section('content')
<div class="py-5">
    <div class="container">
        <div class="section-title">
            <h2>Semua Produk</h2>
            <div class="divider"></div>
        </div>
        
        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <form method="GET" action="{{ route('products.index') }}">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="{{ request('search') }}">
                                    <button class="btn-gold" type="submit">Cari</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <select name="category" class="form-select" onchange="this.form.submit()">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="sort" class="form-select" onchange="this.form.submit()">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="row">
                @foreach($products as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                        <div class="product-card">
                            <div class="product-image">
                                <img src="{{ $product->main_image ?? 'https://via.placeholder.com/400x500?text=' . $product->name }}" alt="{{ $product->name }}">
                                @if($product->is_new_arrival)
                                    <span class="product-badge">New</span>
                                @elseif($product->is_best_seller)
                                    <span class="product-badge">Best Seller</span>
                                @endif
                                <div class="product-actions">
                                    <button onclick="addToWishlist({{ $product->id }})" title="Add to Wishlist">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                    <button onclick="addToCart({{ $product->id }})" title="Add to Cart">
                                        <i class="fas fa-shopping-cart"></i>
                                    </button>
                                    <a href="{{ route('products.show', $product->slug) }}" class="btn btn-gold" style="width: auto; border-radius: 20px; padding: 8px 20px;">
                                        Detail
                                    </a>
                                </div>
                            </div>
                            <div class="product-info">
                                <p class="product-category">{{ $product->category->name }}</p>
                                <a href="{{ route('products.show', $product->slug) }}">
                                    <h5 class="product-name">{{ $product->name }}</h5>
                                </a>
                                <div class="product-price">
                                    Rp {{ number_format($product->final_price, 0, ',', '.') }}
                                    @if($product->discount > 0)
                                        <span class="product-old-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Tidak ada produk ditemukan</h4>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    function addToCart(productId) {
        @auth
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
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan'
                    });
                }
            });
        @else
            Swal.fire({
                icon: 'info',
                title: 'Login Diperlukan',
                text: 'Silakan login untuk menambahkan produk ke keranjang',
                confirmButtonText: 'Login'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('login') }}';
                }
            });
        @endauth
    }

    function addToWishlist(productId) {
        @auth
            $.ajax({
                url: '{{ route('wishlist.add') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    product_id: productId
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Produk ditambahkan ke wishlist',
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
        @else
            Swal.fire({
                icon: 'info',
                title: 'Login Diperlukan',
                text: 'Silakan login untuk menambahkan produk ke wishlist',
                confirmButtonText: 'Login'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('login') }}';
                }
            });
        @endauth
    }
</script>
@endpush
