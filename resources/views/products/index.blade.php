@extends('layouts.frontend')

@section('title', 'Produk - Samawa Fashion')

@section('content')
<div class="py-5">
    <div class="container">
        <div class="section-title">
            <h2>Koleksi Busana</h2>
            <div class="divider"></div>
        </div>
        
        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-lg-3 mb-4">
                <div class="card p-4 border-0 shadow-sm" style="background-color: #fff; border-radius: 10px;">
                    <h4 class="mb-4 text-gold border-bottom pb-2" style="color: var(--gold-color); font-family: 'Playfair Display', serif;">Filter Produk</h4>
                    
                    <form id="filter-form">
                        <!-- Search Bar -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Cari</label>
                            <div class="input-group">
                                <input type="text" name="search" id="search-input" class="form-control" placeholder="Nama produk..." value="{{ request('search') }}" style="border-radius: 0;">
                            </div>
                        </div>

                        <!-- Categories Filter (Checkboxes) -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Kategori</label>
                            @foreach($categories as $category)
                                <div class="form-check mb-2">
                                    <input class="form-check-input category-checkbox" type="checkbox" name="category[]" value="{{ $category->id }}" id="cat-{{ $category->id }}" {{ request('category') == $category->id ? 'checked' : '' }}>
                                    <label class="form-check-label text-muted small" for="cat-{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Price Slider -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold d-flex justify-content-between">
                                <span>Harga Maksimal</span>
                                <span class="text-gold" id="price-label" style="color: var(--gold-color); font-weight: 600;">Rp {{ number_format($maxPrice, 0, ',', '.') }}</span>
                            </label>
                            <input type="range" class="form-range" id="price-slider" min="0" max="{{ $maxPrice }}" step="50000" value="{{ $maxPrice }}">
                            <input type="hidden" name="max_price" id="max-price-input" value="{{ $maxPrice }}">
                            <div class="d-flex justify-content-between text-muted small mt-1">
                                <span>Rp 0</span>
                                <span>Rp {{ number_format($maxPrice, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Sort Options -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Urutkan</label>
                            <select name="sort" id="sort-select" class="form-select text-muted" style="border-radius: 0;">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                                <option value="bestseller" {{ request('sort') == 'bestseller' ? 'selected' : '' }}>Terlaris</option>
                            </select>
                        </div>
                        
                        <button type="button" id="reset-filters" class="btn btn-outline-dark w-100 mt-2" style="border-radius: 0;">Riset Filter</button>
                    </form>
                </div>
            </div>

            <!-- Products Grid Column -->
            <div class="col-lg-9">
                <!-- Skeleton Loader Placeholders -->
                <div id="skeleton-container" class="d-none">
                    <div class="row">
                        @for($i = 0; $i < 8; $i++)
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                <div class="skeleton-card">
                                    <div class="skeleton-img"></div>
                                    <div class="skeleton-info">
                                        <div class="skeleton-text" style="width: 40%;"></div>
                                        <div class="skeleton-text" style="width: 80%; height: 20px;"></div>
                                        <div class="skeleton-text" style="width: 60%;"></div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
                
                <!-- Dynamic Products Container -->
                <div id="products-container">
                    @include('products._grid', ['products' => $products])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let maxVal = "{{ $maxPrice }}";
        
        // Handle Price Slider input
        $('#price-slider').on('input', function() {
            let val = $(this).val();
            $('#max-price-input').val(val);
            $('#price-label').text('Rp ' + new Intl.NumberFormat('id-ID').format(val));
        });

        // Trigger filter change on input / select changes
        $('#search-input').on('keyup', debounce(function() {
            filterProducts();
        }, 500));

        $('.category-checkbox, #sort-select').on('change', function() {
            filterProducts();
        });

        $('#price-slider').on('change', function() {
            filterProducts();
        });

        // Pagination Click Interception
        $(document).on('click', '.ajax-pagination a', function(e) {
            e.preventDefault();
            let url = $(this).attr('href');
            let page = getUrlParameter(url, 'page');
            filterProducts(page);
        });

        // Reset Filters Button
        $('#reset-filters').on('click', function() {
            $('#search-input').val('');
            $('.category-checkbox').prop('checked', false);
            $('#price-slider').val(maxVal);
            $('#max-price-input').val(maxVal);
            $('#price-label').text('Rp ' + new Intl.NumberFormat('id-ID').format(maxVal));
            $('#sort-select').val('newest');
            filterProducts();
        });

        // Debounce utility to prevent too many keyup requests
        function debounce(func, wait) {
            let timeout;
            return function() {
                const context = this, args = arguments;
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    func.apply(context, args);
                }, wait);
            };
        }

        // Helper to grab url parameters
        function getUrlParameter(url, name) {
            name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
            var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
            var results = regex.exec(url);
            return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
        }

        // Core AJAX Filter Logic
        function filterProducts(page = 1) {
            $('#products-container').addClass('d-none');
            $('#skeleton-container').removeClass('d-none');

            let formData = $('#filter-form').serialize();
            formData += '&page=' + page;

            $.ajax({
                url: '{{ route('products.index') }}',
                method: 'GET',
                data: formData,
                success: function(response) {
                    $('#products-container').html(response.html);
                    $('#skeleton-container').addClass('d-none');
                    $('#products-container').removeClass('d-none');
                    
                    // Smooth scroll back to top of products list
                    $('html, body').animate({
                        scrollTop: $('.section-title').offset().top - 100
                    }, 300);
                },
                error: function(xhr) {
                    $('#skeleton-container').addClass('d-none');
                    $('#products-container').removeClass('d-none');
                    console.error('Filter error:', xhr);
                }
            });
        }
    });

    // Add to Cart Action
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
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Update navbar badge count dynamically
                $.get('{{ route('cart.index') }}', function(data) {
                    // Update badge text by parsing return HTML or reload partially
                    let badge = $(data).find('#cart-badge-count').text();
                    if (parseInt(badge) > 0) {
                        $('#cart-badge-count').text(badge).removeClass('d-none');
                    }
                });
            },
            error: function(xhr) {
                if (xhr.status === 401) {
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

    // Toggle Wishlist Action
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

                // Toggle heart icon color dynamically
                let icon = $(btnElement).find('i');
                if (response.status === 'added') {
                    icon.removeClass('far').addClass('fas');
                } else {
                    icon.removeClass('fas').addClass('far');
                }

                // Update wishlist badge count
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
                        text: 'Silakan login untuk mengelola wishlist Anda',
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
</script>
@endpush
