@extends('layouts.frontend')

@section('title', 'Home - Samawa Fashion')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container hero-content">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h1 class="hero-title">Elegance Meets<br>Luxury Fashion</h1>
                <p class="hero-subtitle">Temukan koleksi fashion terbaik dengan desain eksklusif untuk gaya hidup modern Anda.</p>
                <a href="{{ route('products.index') }}" class="btn-gold">Belanja Sekarang</a>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section id="categories" class="py-5">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Kategori Produk</h2>
            <div class="divider"></div>
        </div>
        <div class="row">
            @foreach($categories as $category)
                <div class="col-md-4 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="category-card">
                        <img src="{{ $category->image ?? 'https://via.placeholder.com/400x300?text=' . $category->name }}" alt="{{ $category->name }}">
                        <div class="category-overlay">
                            <h3 class="category-name">{{ $category->name }}</h3>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- New Arrivals Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Produk Terbaru</h2>
            <div class="divider"></div>
        </div>
        <div class="row">
            @foreach($newProducts as $product)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="product-card">
                        <div class="product-image">
                            <img src="{{ $product->main_image ?? 'https://via.placeholder.com/400x500?text=' . $product->name }}" alt="{{ $product->name }}">
                            @if($product->is_new_arrival)
                                <span class="product-badge">New</span>
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
        <div class="text-center mt-4">
            <a href="{{ route('products.index') }}" class="btn-outline-gold">Lihat Semua Produk</a>
        </div>
    </div>
</section>

<!-- Best Sellers Section -->
<section class="py-5">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Produk Terlaris</h2>
            <div class="divider"></div>
        </div>
        <div class="row">
            @foreach($bestSellers as $product)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="product-card">
                        <div class="product-image">
                            <img src="{{ $product->main_image ?? 'https://via.placeholder.com/400x500?text=' . $product->name }}" alt="{{ $product->name }}">
                            @if($product->is_best_seller)
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
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Apa Kata Mereka</h2>
            <div class="divider"></div>
        </div>
        <div class="row">
            @foreach($testimonials as $testimonial)
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="testimonial-card">
                        <img src="{{ $testimonial->avatar ?? 'https://via.placeholder.com/100' }}" alt="{{ $testimonial->name }}" class="testimonial-avatar">
                        <div class="testimonial-rating">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $testimonial->rating)
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="testimonial-text">"{{ $testimonial->review }}"</p>
                        <h5 class="testimonial-name">{{ $testimonial->name }}</h5>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter">
    <div class="container text-center">
        <h2 class="mb-3" data-aos="fade-up">Subscribe Newsletter</h2>
        <p class="mb-4" data-aos="fade-up" data-aos-delay="100">Dapatkan penawaran eksklusif dan update terbaru</p>
        <form class="newsletter-form d-flex justify-content-center" data-aos="fade-up" data-aos-delay="200">
            <input type="email" placeholder="Masukkan email Anda">
            <button class="btn-gold" type="submit">Subscribe</button>
        </form>
    </div>
</section>
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
