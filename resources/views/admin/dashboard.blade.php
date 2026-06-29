@extends('admin.layout')

@section('title', 'Dashboard - Samawa Fashion')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Dashboard</h2>
    <p class="text-muted mb-0">{{ now()->format('l, d F Y') }}</p>
</div>

<!-- Stats Cards -->
<div class="row">
    <div class="col-md-3">
        <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Total Produk</h6>
                    <div class="number">{{ $totalProducts }}</div>
                </div>
                <i class="fas fa-box icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Total Customer</h6>
                    <div class="number">{{ $totalCustomers }}</div>
                </div>
                <i class="fas fa-users icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" data-aos="fade-up" data-aos-delay="300">
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
        <div class="stat-card" data-aos="fade-up" data-aos-delay="400">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Pendapatan</h6>
                    <div class="number">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                </div>
                <i class="fas fa-money-bill-wave icon"></i>
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
                <i class="fas fa-fire me-2"></i> Produk Terlaris
            </div>
            <div class="card-body">
                @if($bestSellingProducts->count() > 0)
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th class="text-end">Terjual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bestSellingProducts as $item)
                                <tr>
                                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                                    <td class="text-end">{{ $item->count }}</td>
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
    <div class="col-md-6">
        <div class="card" data-aos="fade-up" data-aos-delay="200">
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
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td>{{ $order->order_code }}</td>
                                        <td>{{ $order->user->name }}</td>
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
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                @foreach($monthlySales as $sale)
                    '{{ $sale->month }}',
                @endforeach
            ],
            datasets: [{
                label: 'Penjualan (Rp)',
                data: [
                    @foreach($monthlySales as $sale)
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
</script>
@endpush
