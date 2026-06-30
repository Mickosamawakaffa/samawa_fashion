@extends('layouts.frontend')

@section('title', $product->name . ' - Samawa Fashion')

@section('content')
<div class="py-5" style="background-color: #FAF6F0;">
    <div class="container">
        
        <!-- Alerts -->
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: '{{ session('success') }}',
                        timer: 2000,
                        showConfirmButton: false
                    });
                });
            </script>
        @endif

        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: '{{ session('error') }}'
                    });
                });
            </script>
        @endif

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
            <!-- Product Images (Left) -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 10px; position: relative;">
                    <!-- Badge Stack -->
                    <div class="product-badge-container" style="position: absolute; top: 15px; left: 15px; display: flex; flex-direction: column; gap: 5px; z-index: 2;">
                        @if($product->stock == 0)
                            <span class="product-badge bg-secondary text-white">Stok Habis</span>
                        @endif
                        @if($product->is_flash_sale_active)
                            <span class="product-badge bg-warning text-dark fw-bold"><i class="fas fa-bolt me-1"></i>FLASH SALE</span>
                        @elseif($product->discount > 0)
                            <span class="product-badge bg-danger text-white">Diskon {{ $product->discount }}%</span>
                        @endif
                        @if($product->is_featured)
                            <span class="product-badge">Featured</span>
                        @elseif($product->is_best_seller)
                            <span class="product-badge">Best Seller</span>
                        @endif
                    </div>
                    
                    @php
                        $mainImg = $product->primaryImage();
                        $mainIsExternal = $mainImg && str_starts_with($mainImg, 'http');
                        $mainImgUrl = $mainIsExternal ? $mainImg : ($mainImg ? Storage::url($mainImg) : asset('images/no-image.jpg'));
                    @endphp
                    <div class="card-body p-0" style="height: 500px;">
                        <img src="{{ $mainImgUrl }}" 
                             alt="{{ $product->name }}" 
                             class="w-100 h-100" 
                             id="mainImage"
                             style="object-fit: cover;">
                    </div>
                </div>
                
                <!-- Thumbnails Grid -->
                @if($product->images->count() > 0)
                    <div class="row mt-3 g-2">
                        @foreach($product->images->sortBy('sort_order') as $image)
                            @php
                                $thumbIsExternal = str_starts_with($image->image_path, 'http');
                                $thumbUrl = $thumbIsExternal ? $image->image_path : Storage::url($image->image_path);
                            @endphp
                            <div class="col-3">
                                <div class="border rounded overflow-hidden cursor-pointer thumb-wrapper {{ $image->is_primary ? 'active' : '' }}" onclick="changeImage('{{ $thumbUrl }}', this)" style="height: 100px;">
                                    <img src="{{ $thumbUrl }}" 
                                         alt="Gallery Thumb" 
                                         loading="lazy"
                                         class="w-100 h-100" 
                                         style="object-fit: cover;">
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <!-- Product Info (Right) -->
            <div class="col-lg-6">
                <span class="text-gold fw-bold text-uppercase tracking-wider small" style="color: var(--gold-color);">{{ $product->category->name }}</span>
                <h1 class="mb-2 mt-1 fw-bold" style="font-family: 'Playfair Display', serif; font-size: 2.5rem;">{{ $product->name }}</h1>
                
                <!-- Ratings Summary -->
                @if($product->reviews_count > 0)
                    <div class="mb-3 d-flex align-items-center gap-2">
                        <div class="text-gold" style="color: var(--gold-color);">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= $product->average_rating ? 'fas' : 'far' }} fa-star"></i>
                            @endfor
                        </div>
                        <span class="fw-semibold text-dark">{{ $product->average_rating }} / 5</span>
                        <span class="text-muted">({{ $product->reviews_count }} Ulasan)</span>
                    </div>
                @endif

                <!-- Price Box -->
                <div class="mb-4">
                    <h2 class="text-gold fw-bold mb-1" style="color: var(--gold-color);">
                        Rp {{ number_format($product->final_price, 0, ',', '.') }}
                    </h2>
                    @if($product->is_flash_sale_active)
                        <span class="text-decoration-line-through text-muted me-2" style="font-size: 1.1rem;">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                        <span class="badge bg-warning text-dark px-3 py-2 fw-bold" style="border-radius: 0;"><i class="fas fa-bolt me-1"></i>Harga Flash Sale</span>
                        
                        <div class="flash-sale-countdown mt-3 p-3 border border-warning rounded" style="background-color: rgba(255, 193, 7, 0.05);" data-end="{{ $product->flash_sale_end->toISOString() }}">
                            <div class="small fw-semibold text-danger mb-1"><i class="far fa-clock me-1"></i> FLASH SALE AKAN BERAKHIR DALAM:</div>
                            <h4 class="mb-0 fw-bold countdown-timer text-danger">00:00:00</h4>
                        </div>
                    @elseif($product->discount > 0)
                        <span class="text-decoration-line-through text-muted me-2" style="font-size: 1.1rem;">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                        <span class="badge bg-danger px-3 py-2" style="border-radius: 0;">Diskon {{ $product->discount }}%</span>
                    @endif
                </div>
                
                <!-- Stock Status Info -->
                <div class="mb-4 d-flex align-items-center">
                    <span class="fw-semibold me-2">Status Stok:</span>
                    @if($product->stock > 0)
                        <span class="badge bg-success px-3 py-2" style="border-radius: 0;">Tersedia ({{ $product->stock }} item)</span>
                    @else
                        <span class="badge bg-secondary px-3 py-2 text-white" style="border-radius: 0;">Stok Habis</span>
                    @endif
                </div>
                
                <!-- Description -->
                <div class="mb-4 pb-3 border-bottom">
                    <h5 class="fw-bold" style="font-family: 'Playfair Display', serif;">Deskripsi</h5>
                    <p class="text-muted" style="line-height: 1.7;">{{ $product->description }}</p>
                </div>
                
                <!-- Size Options -->
                @if($product->sizes)
                    <div class="mb-4">
                        <h6 class="fw-bold">Pilih Ukuran:</h6>
                        <div class="d-flex gap-2 mt-2">
                            @foreach(json_decode($product->sizes) as $size)
                                <button class="btn btn-outline-dark size-btn px-4 py-2" onclick="selectSize(this)" {{ $product->stock == 0 ? 'disabled' : '' }} style="border-radius: 0; font-weight: 600;">
                                    {{ $size }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <!-- Color Options -->
                @if($product->colors)
                    <div class="mb-4 pb-4 border-bottom">
                        <h6 class="fw-bold">Pilih Warna:</h6>
                        <div class="d-flex gap-2 mt-2">
                            @foreach(json_decode($product->colors) as $color)
                                <button class="btn btn-outline-dark color-btn px-3 py-2" onclick="selectColor(this)" {{ $product->stock == 0 ? 'disabled' : '' }} style="border-radius: 0; font-weight: 500; font-size: 0.9rem;">
                                    {{ $color }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <!-- Actions Box -->
                <div class="row align-items-center mb-4 mt-4">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="input-group" style="border-radius: 0;">
                            <button class="btn btn-outline-dark" onclick="decreaseQty()" {{ $product->stock == 0 ? 'disabled' : '' }} style="border-radius: 0;">-</button>
                            <input type="number" id="quantity" class="form-control text-center bg-white" value="1" min="1" max="{{ $product->stock }}" readonly style="border-left: 0; border-right: 0;">
                            <button class="btn btn-outline-dark" onclick="increaseQty()" {{ $product->stock == 0 ? 'disabled' : '' }} style="border-radius: 0;">+</button>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <button class="btn-gold w-100 py-3 text-center" onclick="addToCart()" {{ $product->stock == 0 ? 'disabled' : '' }}>
                            <i class="fas fa-shopping-cart me-2"></i> {{ $product->stock == 0 ? 'Stok Habis' : 'Tambah ke Keranjang' }}
                        </button>
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-dark w-50 py-3" onclick="toggleWishlistDetail()" {{ $product->stock == 0 ? 'disabled' : '' }} style="border-radius: 0; font-weight: 600;">
                        <i class="fas fa-heart text-danger me-2"></i> Wishlist
                    </button>
                    @php
                        $waText = urlencode("Halo Samawa, saya mau tanya tentang produk " . $product->name . " (" . request()->url() . ")");
                    @endphp
                    <a href="https://wa.me/{{ config('services.social.whatsapp') }}?text={{ $waText }}" target="_blank" rel="noopener" class="btn btn-success w-50 py-3 d-flex align-items-center justify-content-center text-decoration-none text-white fw-semibold" style="border-radius: 0; background-color: #25d366; border-color: #25d366; transition: all 0.3s ease;">
                        <i class="fab fa-whatsapp me-2"></i> Tanya via WhatsApp
                    </a>
                </div>
            </div>
        </div>

        <!-- Product Reviews Section -->
        <div class="row mt-5 pt-4 border-top">
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold mb-0" style="font-family: 'Playfair Display', serif;">Ulasan Pembeli</h3>
                    <div>
                        <select id="rating-filter" class="form-select form-select-sm" style="border-radius: 0; width: 160px; font-weight: 600;">
                            <option value="all">Semua Bintang</option>
                            <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                            <option value="4">⭐⭐⭐⭐ (4)</option>
                            <option value="3">⭐⭐⭐ (3)</option>
                            <option value="2">⭐⭐ (2)</option>
                            <option value="1">⭐ (1)</option>
                        </select>
                    </div>
                </div>

                <!-- Review Feed list -->
                <div id="reviews-feed-container" class="mb-4">
                    @include('products._reviews')
                </div>
            </div>

            <!-- Write Review Form Sidebar -->
            <div class="col-lg-4">
                @if($canReview)
                    <div class="card border-0 shadow-sm p-4 text-dark" style="border-radius: 15px; background: #FFFBF5; border: 1px solid #EAE3D2 !important;">
                        <h4 class="fw-bold mb-3" style="font-family: 'Playfair Display', serif; color: #0A0A0A;">Tulis Ulasan</h4>
                        <p class="text-muted small">Bagikan pengalaman belanja Anda dengan pembeli lainnya.</p>
                        
                        <form action="{{ route('products.reviews.store', $product->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Rating Bintang <span class="text-danger">*</span></label>
                                <div class="rating-stars-input d-flex gap-2" style="font-size: 1.8rem; color: #dee2e6; cursor: pointer;">
                                    <i class="far fa-star star-input text-secondary" data-value="1"></i>
                                    <i class="far fa-star star-input text-secondary" data-value="2"></i>
                                    <i class="far fa-star star-input text-secondary" data-value="3"></i>
                                    <i class="far fa-star star-input text-secondary" data-value="4"></i>
                                    <i class="far fa-star star-input text-secondary" data-value="5"></i>
                                </div>
                                <input type="hidden" name="rating" id="review_rating" value="" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Ulasan Ulasan <span class="text-danger">*</span></label>
                                <textarea name="comment" class="form-control" rows="4" required placeholder="Tulis komentar ulasan mengenai kualitas jahitan, keindahan kain, atau kecepatan pengiriman..." style="border-radius: 0; font-size: 0.9rem;"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Foto Produk (Opsional)</label>
                                <input type="file" name="photo" class="form-control" style="border-radius: 0; font-size: 0.85rem;">
                            </div>
                            <button type="submit" class="btn btn-gold w-100 py-3 mt-2" style="border-radius: 0; font-weight: 600;">Kirim Ulasan</button>
                        </form>
                    </div>
                @else
                    <div class="card border-0 shadow-sm p-4 text-center text-muted" style="border-radius: 15px; background: #f8f9fa;">
                        <i class="fas fa-pencil-alt fa-3x mb-3 text-gold" style="color: var(--gold-color);"></i>
                        <h6 class="fw-bold text-black mb-1">Berikan Ulasan Ulasan</h6>
                        <p class="small mb-0">Ulasan hanya dapat dikirim oleh pembeli terverifikasi setelah order berstatus <strong>Selesai</strong>.</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Related Products (Similar) -->
        @if($relatedProducts->count() > 0)
            <div class="mt-5 pt-5 border-top">
                <div class="section-title">
                    <h2>Produk Serupa</h2>
                    <div class="divider"></div>
                </div>
                <div class="row">
                    @foreach($relatedProducts as $related)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                            <div class="product-card" style="position: relative; overflow: visible;">
                                <div class="product-image" style="position: relative; overflow: hidden; height: 300px; border-radius: 10px 10px 0 0;">
                                    @php
                                        $relImg = $related->primaryImage();
                                        $relIsExt = $relImg && str_starts_with($relImg, 'http');
                                        $relImgUrl = $relIsExt ? $relImg : ($relImg ? Storage::url($relImg) : asset('images/no-image.jpg'));
                                    @endphp
                                    <img src="{{ $relImgUrl }}" alt="{{ $related->name }}" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                                    
                                    <!-- Badges Stack -->
                                    <div class="product-badge-container">
                                        @if($related->stock == 0)
                                            <span class="product-badge bg-secondary text-white">Stok Habis</span>
                                        @endif
                                        @if($related->discount > 0)
                                            <span class="product-badge bg-danger text-white">Diskon {{ $related->discount }}%</span>
                                        @endif
                                        @if($related->is_featured)
                                            <span class="product-badge">Featured</span>
                                        @elseif($related->is_best_seller)
                                            <span class="product-badge">Best Seller</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Action overlay on hover -->
                                    <div class="product-actions">
                                        <button onclick="toggleWishlist({{ $related->id }}, this)" title="Add to Wishlist" class="wishlist-btn" {{ $related->stock == 0 ? 'disabled' : '' }}>
                                            <i class="{{ auth()->check() && auth()->user()->wishlist->contains('product_id', $related->id) ? 'fas' : 'far' }} fa-heart text-danger"></i>
                                        </button>
                                        <button onclick="addToCartRelated({{ $related->id }})" title="Add to Cart" {{ $related->stock == 0 ? 'disabled' : '' }}>
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                        <a href="{{ route('products.show', $related->slug) }}" class="btn btn-gold" style="width: auto; border-radius: 20px; padding: 8px 20px; font-size: 0.85rem; font-weight: 600;">
                                            Detail
                                        </a>
                                    </div>
                                </div>
                                <div class="product-info" style="padding: 20px; background: white; border-radius: 0 0 10px 10px;">
                                    <p class="product-category" style="color: var(--gray-color); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;">{{ $related->category->name }}</p>
                                    <a href="{{ route('products.show', $related->slug) }}">
                                        <h5 class="product-name text-truncate" style="font-size: 1.1rem; font-weight: 600; color: var(--primary-color); margin-bottom: 10px; transition: all 0.3s ease;">{{ $related->name }}</h5>
                                    </a>
                                    <div class="product-price" style="font-size: 1.2rem; font-weight: 700; color: var(--gold-color);">
                                        Rp {{ number_format($related->final_price, 0, ',', '.') }}
                                        @if($related->discount > 0)
                                            <span class="product-old-price" style="text-decoration: line-through; color: var(--gray-color); font-size: 0.9rem; margin-left: 10px;">Rp {{ number_format($related->price, 0, ',', '.') }}</span>
                                        @endif
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

<style>
    .thumb-wrapper {
        transition: all 0.3s ease;
    }
    .thumb-wrapper:hover,
    .thumb-wrapper.active {
        border-color: var(--gold-color) !important;
        box-shadow: 0 0 5px rgba(201, 168, 76, 0.5);
    }
    .text-gold {
        color: var(--gold-color) !important;
    }
</style>
@endsection

@push('scripts')
<script>
    let selectedSize = null;
    let selectedColor = null;
    
    function changeImage(src, wrapperElement) {
        document.getElementById('mainImage').src = src;
        document.querySelectorAll('.thumb-wrapper').forEach(w => w.classList.remove('active'));
        $(wrapperElement).addClass('active');
    }
    
    function selectSize(btn) {
        document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('btn-dark'));
        document.querySelectorAll('.size-btn').forEach(b => b.classList.add('btn-outline-dark'));
        btn.classList.remove('btn-outline-dark');
        btn.classList.add('btn-dark');
        selectedSize = btn.textContent.trim();
    }
    
    function selectColor(btn) {
        document.querySelectorAll('.color-btn').forEach(b => b.classList.remove('btn-dark'));
        document.querySelectorAll('.color-btn').forEach(b => b.classList.add('btn-outline-dark'));
        btn.classList.remove('btn-outline-dark');
        btn.classList.add('btn-dark');
        selectedColor = btn.textContent.trim();
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
        if ($('.size-btn').length > 0 && !selectedSize) {
            Swal.fire({
                icon: 'warning',
                title: 'Ukuran Belum Dipilih',
                text: 'Silakan pilih ukuran terlebih dahulu'
            });
            return;
        }
        if ($('.color-btn').length > 0 && !selectedColor) {
            Swal.fire({
                icon: 'warning',
                title: 'Warna Belum Dipilih',
                text: 'Silakan pilih warna terlebih dahulu'
            });
            return;
        }

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
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Update navbar badge count
                if (response.cart_count > 0) {
                    $('#cart-badge-count').text(response.cart_count).removeClass('d-none');
                }

                // Analytics tracking
                if (typeof window.trackAddToCart === 'function') {
                    window.trackAddToCart(
                        "{{ $product->id }}",
                        "{{ $product->name }}",
                        "{{ $product->category->name }}",
                        "{{ $product->final_price }}",
                        quantity
                    );
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
    
    function toggleWishlistDetail() {
        $.ajax({
            url: '{{ route('wishlist.toggle') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: {{ $product->id }}
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                    timer: 1500,
                    showConfirmButton: false
                });

                if (response.count > 0) {
                    $('#wishlist-badge-count').text(response.count).removeClass('d-none');
                } else {
                    $('#wishlist-badge-count').addClass('d-none');
                }
            },
            error: function(xhr) {
                if (xhr.status === 401) {
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
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan'
                    });
                }
            }
        });
    }

    // Toggle Wishlist inside similar list
    function toggleWishlist(productId, btnElement) {
        $.ajax({
            url: '{{ route('wishlist.toggle') }}',
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
                    timer: 1500,
                    showConfirmButton: false
                });

                let icon = $(btnElement).find('i');
                if (response.status === 'added') {
                    icon.removeClass('far').addClass('fas');
                } else {
                    icon.removeClass('fas').addClass('far');
                }

                if (response.count > 0) {
                    $('#wishlist-badge-count').text(response.count).removeClass('d-none');
                } else {
                    $('#wishlist-badge-count').addClass('d-none');
                }
            },
            error: function(xhr) {
                if (xhr.status === 401) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Login Diperlukan',
                        text: 'Silakan login untuk mengelola wishlist',
                        confirmButtonText: 'Login'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '{{ route('login') }}';
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan'
                    });
                }
            }
        });
    }
    
    function addToCartRelated(productId) {
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
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });

                if (response.cart_count > 0) {
                    $('#cart-badge-count').text(response.cart_count).removeClass('d-none');
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

    // --- Product Reviews AJAX filtering & Star Picker ---

    $(document).on('click', '.star-input', function() {
        let val = $(this).data('value');
        $('#review_rating').val(val);
        $('.star-input').each(function() {
            let starVal = $(this).data('value');
            if (starVal <= val) {
                $(this).removeClass('far text-secondary').addClass('fas text-gold');
            } else {
                $(this).removeClass('fas text-gold').addClass('far text-secondary');
            }
        });
    });

    $('#rating-filter').on('change', function() {
        let rating = $(this).val();
        loadReviews(1, rating);
    });

    $(document).on('click', '#reviews-pagination-links .pagination a', function(e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        let rating = $('#rating-filter').val();
        loadReviews(page, rating);
    });

    function loadReviews(page, rating) {
        $.ajax({
            url: '{{ route('products.show', $product->slug) }}?page=' + page + '&rating=' + rating,
            success: function(response) {
                $('#reviews-feed-container').html(response.html);
            }
        });
    }

    // Flash sale countdown logic
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('[data-end]').forEach(function(el) {
            const end = new Date(el.getAttribute('data-end')).getTime();
            
            function update() {
                const now = new Date().getTime();
                const dist = end - now;
                
                if (dist < 0) {
                    el.innerHTML = '<div class="alert alert-danger mb-0"><i class="far fa-clock me-1"></i> Flash sale telah berakhir!</div>';
                    return;
                }
                
                const hours = Math.floor((dist % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((dist % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((dist % (1000 * 60)) / 1000);
                
                const timerEl = el.querySelector('.countdown-timer');
                if (timerEl) {
                    timerEl.innerText = 
                        (hours < 10 ? '0' : '') + hours + ':' + 
                        (minutes < 10 ? '0' : '') + minutes + ':' + 
                        (seconds < 10 ? '0' : '') + seconds;
                }
            }
            
            update();
            setInterval(update, 1000);
        });

        // Analytics trackViewContent
        if (typeof window.trackViewContent === 'function') {
            window.trackViewContent(
                "{{ $product->id }}",
                "{{ $product->name }}",
                "{{ $product->category->name }}",
                "{{ $product->final_price }}"
            );
        }
    });
</script>
@endpush
