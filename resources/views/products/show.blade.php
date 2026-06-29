@extends('layouts.frontend')

@section('title', $product->name . ' - Samawa Fashion')

@section('content')
<div class="py-5">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category->id]) }}">{{ $product->category->name }}</a></li>
                <li class="breadcrumb-item active">{{ $product->name }}</li>
            </ol>
        </nav>
        
        <div class="row">
            <!-- Product Images -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0">
                    <div class="card-body p-0">
                        <img src="{{ $product->main_image ?? 'https://via.placeholder.com/600x600?text=' . $product->name }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid w-100" 
                             id="mainImage">
                    </div>
                </div>
                @if($product->images->count() > 1)
                    <div class="row mt-3">
                        @foreach($product->images as $image)
                            <div class="col-3">
                                <img src="{{ $image->image_path }}" 
                                     alt="Product Image" 
                                     class="img-fluid thumbnail cursor-pointer"
                                     onclick="changeImage('{{ $image->image_path }}')">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <!-- Product Info -->
            <div class="col-lg-6">
                <h1 class="mb-3">{{ $product->name }}</h1>
                <p class="text-muted mb-3">{{ $product->category->name }}</p>
                
                <div class="mb-4">
                    <h2 class="text-gold" style="color: var(--gold-color);">
                        Rp {{ number_format($product->final_price, 0, ',', '.') }}
                    </h2>
                    @if($product->discount > 0)
                        <span class="text-decoration-line-through text-muted">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                        <span class="badge bg-danger ms-2">{{ $product->discount }}% OFF</span>
                    @endif
                </div>
                
                <div class="mb-4">
                    <h5>Deskripsi</h5>
                    <p class="text-muted">{{ $product->description }}</p>
                </div>
                
                @if($product->sizes)
                    <div class="mb-4">
                        <h5>Ukuran</h5>
                        <div class="d-flex gap-2">
                            @foreach(json_decode($product->sizes) as $size)
                                <button class="btn btn-outline-dark size-btn" onclick="selectSize(this)">{{ $size }}</button>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                @if($product->colors)
                    <div class="mb-4">
                        <h5>Warna</h5>
                        <div class="d-flex gap-2">
                            @foreach(json_decode($product->colors) as $color)
                                <button class="btn btn-outline-dark color-btn" onclick="selectColor(this)">{{ $color }}</button>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <div class="mb-4">
                    <h5>Stok: {{ $product->stock }}</h5>
                </div>
                
                <div class="d-flex gap-3 mb-4">
                    <div class="input-group" style="max-width: 150px;">
                        <button class="btn btn-outline-dark" onclick="decreaseQty()">-</button>
                        <input type="number" id="quantity" class="form-control text-center" value="1" min="1" max="{{ $product->stock }}">
                        <button class="btn btn-outline-dark" onclick="increaseQty()">+</button>
                    </div>
                    <button class="btn-gold flex-grow-1" onclick="addToCart()">
                        <i class="fas fa-shopping-cart me-2"></i> Tambah ke Keranjang
                    </button>
                </div>
                
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-dark" onclick="addToWishlist()">
                        <i class="fas fa-heart me-2"></i> Tambah ke Wishlist
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="mt-5">
                <div class="section-title">
                    <h2>Produk Terkait</h2>
                    <div class="divider"></div>
                </div>
                <div class="row">
                    @foreach($relatedProducts as $related)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                            <div class="product-card">
                                <div class="product-image">
                                    <img src="{{ $related->main_image ?? 'https://via.placeholder.com/400x500?text=' . $related->name }}" alt="{{ $related->name }}">
                                    <div class="product-actions">
                                        <button onclick="addToWishlistRelated({{ $related->id }})" title="Add to Wishlist">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                        <button onclick="addToCartRelated({{ $related->id }})" title="Add to Cart">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                        <a href="{{ route('products.show', $related->slug) }}" class="btn btn-gold" style="width: auto; border-radius: 20px; padding: 8px 20px;">
                                            Detail
                                        </a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <p class="product-category">{{ $related->category->name }}</p>
                                    <a href="{{ route('products.show', $related->slug) }}">
                                        <h5 class="product-name">{{ $related->name }}</h5>
                                    </a>
                                    <div class="product-price">
                                        Rp {{ number_format($related->final_price, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    let selectedSize = null;
    let selectedColor = null;
    
    function changeImage(src) {
        document.getElementById('mainImage').src = src;
    }
    
    function selectSize(btn) {
        document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('btn-dark'));
        document.querySelectorAll('.size-btn').forEach(b => b.classList.add('btn-outline-dark'));
        btn.classList.remove('btn-outline-dark');
        btn.classList.add('btn-dark');
        selectedSize = btn.textContent;
    }
    
    function selectColor(btn) {
        document.querySelectorAll('.color-btn').forEach(b => b.classList.remove('btn-dark'));
        document.querySelectorAll('.color-btn').forEach(b => b.classList.add('btn-outline-dark'));
        btn.classList.remove('btn-outline-dark');
        btn.classList.add('btn-dark');
        selectedColor = btn.textContent;
    }
    
    function increaseQty() {
        const input = document.getElementById('quantity');
        const max = parseInt(input.max);
        if (parseInt(input.value) < max) {
            input.value = parseInt(input.value) + 1;
        }
    }
    
    function decreaseQty() {
        const input = document.getElementById('quantity');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }
    
    function addToCart() {
        @auth
            const quantity = document.getElementById('quantity').value;
            $.ajax({
                url: '{{ route('cart.add') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    product_id: {{ $product->id }},
                    quantity: quantity,
                    size: selectedSize,
                    color: selectedColor
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
    
    function addToWishlist() {
        @auth
            $.ajax({
                url: '{{ route('wishlist.add') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    product_id: {{ $product->id }}
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
    
    function addToCartRelated(productId) {
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
    
    function addToWishlistRelated(productId) {
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
