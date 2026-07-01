<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Samawa Fashion - Admin')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #0A0A0A;
            --gold-color: #C9A84C;
            --cream-color: #F5F0E8;
            --gray-color: #6c757d;
        }
        
        body {
            background-color: var(--cream-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            background-color: var(--primary-color);
            min-height: 100vh;
            color: var(--cream-color);
        }
        
        .sidebar .nav-link {
            color: var(--cream-color);
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 0;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: var(--gold-color);
            color: var(--primary-color);
        }
        
        .sidebar .nav-link i {
            width: 25px;
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--gold-color) !important;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: var(--cream-color);
            border-radius: 10px 10px 0 0 !important;
            font-weight: 600;
        }
        
        .stat-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1a1a1a 100%);
            color: var(--cream-color);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card .icon {
            font-size: 2.5rem;
            color: var(--gold-color);
        }
        
        .stat-card .number {
            font-size: 2rem;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .btn-gold {
            background-color: var(--gold-color);
            color: var(--primary-color);
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-gold:hover {
            background-color: #b8963a;
            color: var(--primary-color);
        }
        
        .table thead {
            background-color: var(--primary-color);
            color: var(--cream-color);
        }
        
        .badge-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .badge-pending { background-color: #ffc107; color: #000; }
        .badge-processing { background-color: #17a2b8; color: #fff; }
        .badge-processed { background-color: #17a2b8; color: #fff; }
        .badge-shipped { background-color: #007bff; color: #fff; }
        .badge-delivered { background-color: #28a745; color: #fff; }
        .badge-completed { background-color: #28a745; color: #fff; }
        .badge-cancelled { background-color: #dc3545; color: #fff; }

        /* Pagination Styling */
        .pagination {
            --gold: var(--gold-color);
        }
        .pagination .page-link {
            padding: 0.5rem 0.9rem;
            color: var(--gold);
            background-color: var(--primary-color);
            border-color: rgba(201, 168, 76, 0.2);
        }
        .pagination .page-link:hover {
            background-color: var(--gold);
            color: var(--primary-color);
            border-color: var(--gold);
        }
        .pagination .page-item.active .page-link {
            background: var(--gold);
            border-color: var(--gold);
            color: #0A0A0A;
        }
        .pagination .page-item.disabled .page-link {
            background-color: #151515;
            color: #4a4a4a;
            border-color: rgba(201, 168, 76, 0.1);
        }
        .pagination svg, .pagination .icon {
            width: 16px;
            height: 16px;
        }

        /* Order Filter Tabs Styling */
        .order-filter-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        .order-filter-tabs a {
            color: var(--cream-color);
            background-color: var(--primary-color);
            padding: 0.5rem 1.2rem;
            text-decoration: none;
            border: 1px solid rgba(201, 168, 76, 0.3);
            border-radius: 4px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .order-filter-tabs a:hover,
        .order-filter-tabs a.active {
            background-color: var(--gold-color);
            color: var(--primary-color);
            border-color: var(--gold-color);
        }
        .order-filter-tabs a span {
            font-weight: bold;
        }

        /* Bulk Action Bar Styling */
        .bulk-action-bar {
            background-color: var(--primary-color);
            color: var(--cream-color);
            padding: 0.8rem 1.2rem;
            border-radius: 8px;
            border: 1px solid var(--gold-color);
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1rem;
            margin-bottom: 1.5rem;
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from { transform: translateY(10px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .bulk-action-bar select {
            background-color: #1a1a1a;
            color: var(--cream-color);
            border: 1px solid rgba(201, 168, 76, 0.4);
            padding: 0.4rem 0.8rem;
            border-radius: 4px;
        }
        .bulk-action-bar select:focus {
            outline: none;
            border-color: var(--gold-color);
        }
        .bulk-action-bar button {
            background-color: var(--gold-color);
            color: var(--primary-color);
            border: none;
            padding: 0.4rem 1.2rem;
            border-radius: 4px;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .bulk-action-bar button:hover {
            background-color: #b8963a;
        }

        /* Quick Status Select in table */
        .quick-status-select {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
            border: 1px solid rgba(201, 168, 76, 0.3);
            background-color: #1a1a1a;
            color: var(--cream-color);
            cursor: pointer;
        }
        .quick-status-select:focus {
            outline: none;
            border-color: var(--gold-color);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <div class="p-3 text-center border-bottom border-secondary">
                    <h4 class="navbar-brand">Samawa Fashion</h4>
                    <small class="text-muted">Admin Panel</small>
                </div>
                <nav class="nav flex-column mt-3">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                        <i class="fas fa-box"></i> Produk
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                        <i class="fas fa-tags"></i> Kategori
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                        <i class="fas fa-shopping-cart"></i> Pesanan
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" href="{{ route('admin.customers.index') }}">
                        <i class="fas fa-users"></i> User / Customer
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}" href="{{ route('admin.testimonials.index') }}">
                        <i class="fas fa-comment-dots"></i> Testimoni
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                        <i class="fas fa-chart-bar"></i> Laporan
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.settings.shipping') ? 'active' : '' }}" href="{{ route('admin.settings.shipping') }}">
                        <i class="fas fa-truck"></i> Pengaturan Kirim
                    </a>
                    <hr class="border-secondary">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="fas fa-home"></i> Website
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </nav>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 p-4">
                @yield('content')
            </div>
        </div>
    </div>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        AOS.init();
        
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
    
    @stack('scripts')
</body>
</html>
