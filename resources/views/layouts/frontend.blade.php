<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Samawa Fashion - Luxury Fashion Store')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        :root {
            --primary-color: #0A0A0A;
            --gold-color: #C9A84C;
            --cream-color: #F5F0E8;
            --gray-color: #6c757d;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--cream-color);
            color: var(--primary-color);
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
        }
        
        /* Navbar */
        .navbar {
            background-color: var(--primary-color);
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--gold-color) !important;
            letter-spacing: 2px;
        }
        
        .nav-link {
            color: var(--cream-color) !important;
            font-weight: 500;
            margin: 0 10px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-link:hover,
        .nav-link.active {
            color: var(--gold-color) !important;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background-color: var(--gold-color);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }
        
        .cart-badge {
            background-color: var(--gold-color);
            color: var(--primary-color);
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.7rem;
            font-weight: 600;
            margin-left: 2px;
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1a1a1a 100%);
            color: var(--cream-color);
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1920') center/cover;
            opacity: 0.3;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .btn-gold {
            background-color: var(--gold-color);
            color: var(--primary-color);
            border: none;
            padding: 15px 40px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 0;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            text-transform: uppercase;
        }
        
        .btn-gold:hover {
            background-color: #b8963a;
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(201, 168, 76, 0.4);
        }
        
        .btn-outline-gold {
            background-color: transparent;
            color: var(--gold-color);
            border: 2px solid var(--gold-color);
            padding: 13px 38px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 0;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            text-transform: uppercase;
        }
        
        .btn-outline-gold:hover {
            background-color: var(--gold-color);
            color: var(--primary-color);
        }
        
        /* Section Titles */
        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .section-title .divider {
            width: 80px;
            height: 3px;
            background-color: var(--gold-color);
            margin: 0 auto;
        }
        
        /* Product Card */
        .product-card {
            background: white;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }
        
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
        
        .product-image {
            position: relative;
            overflow: hidden;
            height: 300px;
        }
        
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .product-card:hover .product-image img {
            transform: scale(1.1);
        }
        
        .product-badge-container {
            position: absolute;
            top: 15px;
            left: 15px;
            display: flex;
            flex-direction: column;
            gap: 5px;
            z-index: 2;
        }
        
        .product-badge {
            padding: 5px 15px;
            background-color: var(--gold-color);
            color: var(--primary-color);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            border-radius: 0;
            display: inline-block;
            width: fit-content;
        }
        
        .product-badge.bg-danger {
            background-color: #dc3545 !important;
            color: #fff !important;
        }
        
        .product-badge.bg-secondary {
            background-color: #6c757d !important;
            color: #fff !important;
        }
        
        .product-actions {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(10, 10, 10, 0.75);
            padding: 15px;
            display: flex;
            justify-content: center;
            gap: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .product-card:hover .product-actions {
            opacity: 1;
        }
        
        .product-actions button:disabled {
            background-color: #555555 !important;
            color: #888888 !important;
            cursor: not-allowed;
            transform: none !important;
        }
        
        /* Custom Pagination Styles */
        .pagination .page-link {
            color: var(--primary-color);
            background-color: transparent;
            border: 1px solid var(--primary-color);
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .pagination .page-link:hover {
            color: var(--primary-color);
            background-color: var(--gold-color);
            border-color: var(--gold-color);
        }
        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--gold-color);
            color: var(--gold-color) !important;
        }
        .pagination .page-item.disabled .page-link {
            color: var(--gray-color);
            background-color: transparent;
            border-color: #dee2e6;
        }
        
        /* Skeleton Pulse Animation */
        @keyframes pulse {
            0% { opacity: 0.6; }
            50% { opacity: 1; }
            100% { opacity: 0.6; }
        }
        .skeleton-card {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        .skeleton-img {
            background: #e2e8f0;
            height: 300px;
            animation: pulse 1.5s infinite ease-in-out;
        }
        .skeleton-info {
            padding: 20px;
        }
        .skeleton-text {
            background: #e2e8f0;
            height: 15px;
            margin-bottom: 10px;
            border-radius: 4px;
            animation: pulse 1.5s infinite ease-in-out;
        }
        
        .product-actions button {
            background: var(--gold-color);
            border: none;
            color: var(--primary-color);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .product-actions button:hover {
            background: var(--cream-color);
            transform: scale(1.1);
        }
        
        .product-info {
            padding: 20px;
        }
        
        .product-category {
            color: var(--gray-color);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        
        .product-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }
        
        .product-name:hover {
            color: var(--gold-color);
        }
        
        .product-price {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--gold-color);
        }
        
        .product-old-price {
            text-decoration: line-through;
            color: var(--gray-color);
            font-size: 0.9rem;
            margin-left: 10px;
        }
        
        /* Category Card */
        .category-card {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            height: 250px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .category-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .category-card:hover img {
            transform: scale(1.1);
        }
        
        .category-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(10, 10, 10, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .category-card:hover .category-overlay {
            background: rgba(10, 10, 10, 0.7);
        }
        
        .category-name {
            color: var(--cream-color);
            font-size: 1.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        /* Testimonial Card */
        .testimonial-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
            margin-bottom: 30px;
        }
        
        .testimonial-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 20px;
            border: 3px solid var(--gold-color);
        }
        
        .testimonial-rating {
            color: var(--gold-color);
            margin-bottom: 15px;
        }
        
        .testimonial-text {
            font-style: italic;
            color: var(--gray-color);
            margin-bottom: 20px;
        }
        
        .testimonial-name {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        /* Footer */
        .footer {
            background-color: var(--primary-color);
            color: var(--cream-color);
            padding: 60px 0 20px;
        }
        
        .footer h5 {
            color: var(--gold-color);
            margin-bottom: 20px;
            font-size: 1.2rem;
        }
        
        .footer a {
            color: var(--cream-color);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer a:hover {
            color: var(--gold-color);
        }
        
        .footer-social a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(201, 168, 76, 0.2);
            color: var(--gold-color);
            border-radius: 50%;
            margin-right: 10px;
            transition: all 0.3s ease;
        }
        
        .footer-social a:hover {
            background: var(--gold-color);
            color: var(--primary-color);
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(201, 168, 76, 0.3);
            padding-top: 20px;
            margin-top: 40px;
            text-align: center;
        }
        
        /* Newsletter */
        .newsletter {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1a1a1a 100%);
            color: var(--cream-color);
            padding: 60px 0;
        }
        
        .newsletter-form input {
            padding: 15px 20px;
            border: none;
            border-radius: 0;
            width: 100%;
            max-width: 400px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .section-title h2 {
                font-size: 1.8rem;
            }
        }

        /* WhatsApp Floating Button */
        .whatsapp-float {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 40px;
            right: 40px;
            background-color: #25d366;
            color: #fff;
            border-radius: 50%;
            text-align: center;
            font-size: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .whatsapp-float:hover {
            background-color: #20ba5a;
            color: #fff;
            transform: scale(1.1);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4);
        }
    </style>
    @if(config('services.analytics.ga_measurement_id'))
        <!-- Google Tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.analytics.ga_measurement_id') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ config('services.analytics.ga_measurement_id') }}');
        </script>
    @endif

    @if(config('services.analytics.meta_pixel_id'))
        <!-- Meta Pixel Code -->
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ config('services.analytics.meta_pixel_id') }}');
            fbq('track', 'PageView');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ config('services.analytics.meta_pixel_id') }}&ev=PageView&noscript=1"/>
        </noscript>
    @endif
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">SAMAWA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">Kategori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Kontak</a>
                    </li>
                </ul>
                <ul class="navbar-nav align-items-center">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cart.index') }}">
                                <i class="fas fa-shopping-cart"></i>
                                @if(session()->has('cart') && count(session('cart')) > 0)
                                    <span class="cart-badge" id="cart-badge-count">{{ array_sum(session('cart')) }}</span>
                                @else
                                    <span class="cart-badge d-none" id="cart-badge-count">0</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('wishlist.index') }}">
                                <i class="fas fa-heart"></i>
                                @php $wishCount = auth()->check() ? auth()->user()->wishlist()->count() : 0; @endphp
                                <span class="cart-badge {{ $wishCount == 0 ? 'd-none' : '' }}" id="wishlist-badge-count">{{ $wishCount }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cart.index') }}">
                                <i class="fas fa-shopping-cart"></i>
                                @php $cartCount = auth()->check() ? auth()->user()->carts()->sum('quantity') : 0; @endphp
                                <span class="cart-badge {{ $cartCount == 0 ? 'd-none' : '' }}" id="cart-badge-count">{{ $cartCount }}</span>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil Saya</a></li>
                                <li><a class="dropdown-item" href="{{ route('orders.index') }}">Riwayat Pesanan</a></li>
                                @if(auth()->user()->isAdmin())
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Panel</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    
    @yield('content')
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>SAMAWA FASHION</h5>
                    <p class="text-muted">Toko fashion luxury dengan koleksi terbaik untuk gaya hidup elegan Anda.</p>
                    <div class="footer-social mt-3">
                        <a href="{{ config('services.social.instagram') }}" target="_blank" rel="noopener" title="Instagram SAMAWA"><i class="fab fa-instagram"></i></a>
                        <a href="{{ config('services.social.tiktok') }}" target="_blank" rel="noopener" title="TikTok SAMAWA"><i class="fab fa-tiktok"></i></a>
                        <a href="https://wa.me/{{ config('services.social.whatsapp') }}?text={{ urlencode('Halo Samawa, saya mau tanya tentang produk') }}" target="_blank" rel="noopener" title="WhatsApp SAMAWA"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4">
                    <h5>Bantuan & Legal</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('terms') }}">Syarat & Ketentuan</a></li>
                        <li><a href="{{ route('privacy') }}">Kebijakan Privasi</a></li>
                        <li><a href="{{ route('returns') }}">Kebijakan Retur & Refund</a></li>
                        <li><a href="{{ route('faq') }}">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h5>Kontak</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt me-2"></i> Kemang Raya No. 45, Jakarta Selatan</li>
                        <li><i class="fas fa-phone me-2"></i> +62 878 5339 1433</li>
                        <li><i class="fas fa-envelope me-2"></i> info@samawafashion.com</li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h5>Newsletter</h5>
                    <p class="text-muted small">Dapatkan update terbaru dari kami</p>
                    <form class="newsletter-form">
                        <div class="input-group">
                            <input type="email" placeholder="Email Anda">
                            <button class="btn-gold" type="submit">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="mb-0 text-muted">&copy; {{ date('Y') }} Samawa Fashion. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        AOS.init({
            duration: 800,
            once: true
        });

        // Global Analytics e-commerce custom events
        window.trackViewContent = function(id, name, category, price) {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'view_item', {
                    value: parseFloat(price),
                    currency: 'IDR',
                    items: [{
                        item_id: id,
                        item_name: name,
                        item_category: category,
                        price: parseFloat(price)
                    }]
                });
            }
            if (typeof fbq !== 'undefined') {
                fbq('track', 'ViewContent', {
                    content_ids: [id.toString()],
                    content_name: name,
                    content_category: category,
                    content_type: 'product',
                    value: parseFloat(price),
                    currency: 'IDR'
                });
            }
        };

        window.trackAddToCart = function(id, name, category, price, quantity) {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'add_to_cart', {
                    value: parseFloat(price) * parseInt(quantity),
                    currency: 'IDR',
                    items: [{
                        item_id: id,
                        item_name: name,
                        item_category: category,
                        price: parseFloat(price),
                        quantity: parseInt(quantity)
                    }]
                });
            }
            if (typeof fbq !== 'undefined') {
                fbq('track', 'AddToCart', {
                    content_ids: [id.toString()],
                    content_name: name,
                    content_category: category,
                    content_type: 'product',
                    value: parseFloat(price) * parseInt(quantity),
                    currency: 'IDR'
                });
            }
        };

        window.trackInitiateCheckout = function(value, items) {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'begin_checkout', {
                    value: parseFloat(value),
                    currency: 'IDR',
                    items: items
                });
            }
            if (typeof fbq !== 'undefined') {
                fbq('track', 'InitiateCheckout', {
                    value: parseFloat(value),
                    currency: 'IDR'
                });
            }
        };

        window.trackPurchase = function(orderId, value, items) {
            if (typeof gtag !== 'undefined') {
                gtag('event', 'purchase', {
                    transaction_id: orderId,
                    value: parseFloat(value),
                    currency: 'IDR',
                    items: items
                });
            }
            if (typeof fbq !== 'undefined') {
                fbq('track', 'Purchase', {
                    content_type: 'product',
                    value: parseFloat(value),
                    currency: 'IDR'
                });
            }
        };
        
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif
        
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif
    </script>
    
    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/{{ config('services.social.whatsapp') }}?text={{ urlencode('Halo Samawa, saya mau tanya tentang produk') }}" class="whatsapp-float" target="_blank" rel="noopener" title="Hubungi CS kami di WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    @stack('scripts')
</body>
</html>
