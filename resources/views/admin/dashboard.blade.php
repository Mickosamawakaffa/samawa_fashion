@extends('admin.layout')

@section('title', 'Dashboard - Samawa Fashion')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0 fw-bold" style="font-family: 'Playfair Display', serif;">Overview Dashboard Admin</h2>
    <p class="text-muted mb-0"><i class="far fa-calendar-alt me-1"></i> {{ now()->format('l, d F Y') }}</p>
</div>

<!-- Stats Cards -->
<div class="row g-3">
    <div class="col-md-3">
        <div class="stat-card p-4 shadow-sm bg-black text-gold h-100" data-aos="fade-up" data-aos-delay="100" style="border-radius: 12px; border-left: 4px solid var(--gold-color);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1 text-muted text-uppercase small" style="color: #888 !important;">Total Produk</h6>
                    <div class="number fs-3 fw-bold">{{ $totalProducts }}</div>
                </div>
                <i class="fas fa-box fa-2x text-muted" style="color: #444 !important;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card p-4 shadow-sm bg-black text-gold h-100" data-aos="fade-up" data-aos-delay="200" style="border-radius: 12px; border-left: 4px solid var(--gold-color);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1 text-muted text-uppercase small" style="color: #888 !important;">Order Hari Ini</h6>
                    <div class="number fs-3 fw-bold">{{ $totalOrdersToday }}</div>
                </div>
                <i class="fas fa-shopping-cart fa-2x text-muted" style="color: #444 !important;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card p-4 shadow-sm bg-black text-gold h-100" data-aos="fade-up" data-aos-delay="300" style="border-radius: 12px; border-left: 4px solid var(--gold-color);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1 text-muted text-uppercase small" style="color: #888 !important;">Revenue Bulan Ini</h6>
                    <div class="number fs-4 fw-bold">Rp {{ number_format($totalRevenueMonth, 0, ',', '.') }}</div>
                </div>
                <i class="fas fa-money-bill-wave fa-2x text-muted" style="color: #444 !important;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card p-4 shadow-sm bg-black text-gold h-100" data-aos="fade-up" data-aos-delay="400" style="border-radius: 12px; border-left: 4px solid var(--gold-color);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1 text-muted text-uppercase small" style="color: #888 !important;">Total User</h6>
                    <div class="number fs-3 fw-bold">{{ $totalUsers }}</div>
                </div>
                <i class="fas fa-users fa-2x text-muted" style="color: #444 !important;"></i>
            </div>
        </div>
    </div>
</div>

<!-- Sales last 30 days Chart -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm" data-aos="fade-up" style="border-radius: 15px;">
            <div class="card-header bg-black text-gold p-3 fw-semibold" style="color: var(--gold-color); border-radius: 15px 15px 0 0;">
                <i class="fas fa-chart-line me-2"></i> Grafik Penjualan (30 Hari Terakhir)
            </div>
            <div class="card-body p-4">
                <canvas id="salesChart" height="240"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Low Stock & Recent Orders lists -->
<div class="row mt-4 g-4">
    <!-- Low Stock (stok < 5) -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100" data-aos="fade-up" data-aos-delay="100" style="border-radius: 15px;">
            <div class="card-header bg-black text-gold p-3 fw-semibold" style="color: var(--gold-color); border-radius: 15px 15px 0 0;">
                <i class="fas fa-exclamation-triangle me-2 text-warning"></i> Stok Menipis (Stok < 5)
            </div>
            <div class="card-body p-4">
                @if($lowStockProducts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Kategori</th>
                                    <th class="text-center">Sisa Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockProducts as $p)
                                    <tr>
                                        <td>
                                            <strong class="text-dark">{{ $p->name }}</strong>
                                        </td>
                                        <td>{{ $p->category->name }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-danger px-3 py-2 fw-bold" style="font-size: 0.85rem;">{{ $p->stock }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <p class="mb-0">Semua produk memiliki stok yang cukup.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Orders (5 orders) -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100" data-aos="fade-up" data-aos-delay="200" style="border-radius: 15px;">
            <div class="card-header bg-black text-gold p-3 fw-semibold" style="color: var(--gold-color); border-radius: 15px 15px 0 0;">
                <i class="fas fa-clock me-2"></i> 5 Pesanan Terbaru
            </div>
            <div class="card-body p-4">
                @if($recentOrders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td><strong class="text-black">#{{ $order->order_code }}</strong></td>
                                        <td>{{ $order->user->name }}</td>
                                        <td class="text-gold fw-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge-status badge-{{ $order->status }} d-inline-block px-2 py-1 text-center small text-capitalize" style="border-radius: 10px; font-size: 0.75rem;">
                                                @if($order->status === 'pending') Pending
                                                @elseif($order->status === 'processing') Diproses
                                                @elseif($order->status === 'shipped') Dikirim
                                                @elseif($order->status === 'delivered') Selesai
                                                @else Dibatalkan
                                                @endif
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-dark btn-sm px-2 py-1" style="border-radius: 0; font-size: 0.75rem;">
                                                Update
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-shopping-bag fa-3x mb-3 text-gold"></i>
                        <p class="mb-0">Belum ada transaksi pembelian masuk.</p>
                    </div>
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
            labels: {!! json_encode($salesLabels) !!},
            datasets: [{
                label: 'Omset Penjualan Harian (Rp)',
                data: {!! json_encode($salesValues) !!},
                borderColor: '#C9A84C',
                backgroundColor: 'rgba(201, 168, 76, 0.08)',
                borderWidth: 3,
                fill: true,
                tension: 0.35,
                pointBackgroundColor: '#0A0A0A',
                pointBorderColor: '#C9A84C',
                pointBorderWidth: 2,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        color: '#0A0A0A',
                        font: {
                            family: 'Jost',
                            size: 13
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#666',
                        font: {
                            family: 'Jost'
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#eaeaea'
                    },
                    ticks: {
                        color: '#666',
                        font: {
                            family: 'Jost'
                        },
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
