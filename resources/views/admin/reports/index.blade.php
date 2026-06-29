@extends('admin.layout')

@section('title', 'Laporan Penjualan - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Laporan Penjualan</h2>
    <a href="{{ route('admin.reports.export', request()->all()) }}" class="btn-gold">
        <i class="fas fa-download me-2"></i> Export CSV
    </a>
</div>

<!-- Date Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.index') }}">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn-gold w-100">
                        <i class="fas fa-filter me-2"></i> Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Stats Cards -->
<div class="row">
    <div class="col-md-3">
        <div class="stat-card" data-aos="fade-up">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Total Pendapatan</h6>
                    <div class="number">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                </div>
                <i class="fas fa-money-bill-wave icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Total Pesanan</h6>
                    <div class="number">{{ $totalOrders }}</div>
                </div>
                <i class="fas fa-shopping-cart icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Pesanan Selesai</h6>
                    <div class="number">{{ $completedOrders }}</div>
                </div>
                <i class="fas fa-check-circle icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" data-aos="fade-up" data-aos-delay="300">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Total Customer</h6>
                    <div class="number">{{ $totalCustomers }}</div>
                </div>
                <i class="fas fa-users icon"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card" data-aos="fade-up">
            <div class="card-header">
                <i class="fas fa-chart-line me-2"></i> Grafik Penjualan Bulanan
            </div>
            <div class="card-body">
                <canvas id="salesChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card" data-aos="fade-up" data-aos-delay="100">
            <div class="card-header">
                <i class="fas fa-tags me-2"></i> Penjualan per Kategori
            </div>
            <div class="card-body">
                <canvas id="categoryChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card" data-aos="fade-up" data-aos-delay="200">
            <div class="card-header">
                <i class="fas fa-fire me-2"></i> Produk Terlaris
            </div>
            <div class="card-body">
                @if($topProducts->count() > 0)
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th class="text-end">Terjual</th>
                                <th class="text-end">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topProducts as $product)
                                <tr>
                                    <td>{{ $product->product_name }}</td>
                                    <td class="text-end">{{ $product->total_sold }}</td>
                                    <td class="text-end">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted text-center">Belum ada data penjualan</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card" data-aos="fade-up">
            <div class="card-header">
                <i class="fas fa-clock me-2"></i> Pesanan Terbaru
            </div>
            <div class="card-body">
                @if($recentOrders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Customer</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td>{{ $order->order_code }}</td>
                                        <td>{{ $order->user->name }}</td>
                                        <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                        <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge-status badge-{{ $order->status }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">Belum ada pesanan</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Monthly Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: [
                @foreach($salesByMonth as $sale)
                    '{{ $sale->month }}',
                @endforeach
            ],
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: [
                    @foreach($salesByMonth as $sale)
                        {{ $sale->total }},
                    @endforeach
                ],
                borderColor: '#C9A84C',
                backgroundColor: 'rgba(201, 168, 76, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    // Category Sales Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryChart = new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: [
                @foreach($salesByCategory as $category)
                    '{{ $category->category_name }}',
                @endforeach
            ],
            datasets: [{
                data: [
                    @foreach($salesByCategory as $category)
                        {{ $category->total_revenue }},
                    @endforeach
                ],
                backgroundColor: [
                    '#C9A84C',
                    '#0A0A0A',
                    '#F5F0E8',
                    '#6c757d',
                    '#17a2b8',
                    '#28a745',
                    '#dc3545',
                    '#ffc107',
                    '#007bff',
                    '#6610f2'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'right'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.parsed;
                            return label + ': Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
