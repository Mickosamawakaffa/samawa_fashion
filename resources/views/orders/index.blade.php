@extends('layouts.frontend')

@section('title', 'Riwayat Pesanan - Samawa Fashion')

@section('content')
<div class="py-5" style="background-color: #FAF6F0; min-height: 80vh;">
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

        <div class="section-title" data-aos="fade-up">
            <h2>Pesanan Saya</h2>
            <div class="divider"></div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-header bg-black text-gold p-3 d-flex align-items-center" style="color: var(--gold-color); font-weight: 600; border-radius: 15px 15px 0 0;">
                        <i class="fas fa-history me-2"></i> Daftar Pesanan Anda
                    </div>
                    <div class="card-body p-4">
                        
                        <!-- Status Filter Tabs -->
                        <ul class="nav nav-tabs mb-4" id="orderStatusTabs" style="border-bottom: 2px solid #dee2e6;">
                            <li class="nav-item">
                                <a class="nav-link {{ $status === 'all' ? 'active bg-black text-gold' : 'text-dark' }}" href="{{ route('orders.index', ['status' => 'all']) }}" style="font-weight: 600;">Semua</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $status === 'pending' ? 'active bg-black text-gold' : 'text-dark' }}" href="{{ route('orders.index', ['status' => 'pending']) }}" style="font-weight: 600;">Pending</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $status === 'processing' ? 'active bg-black text-gold' : 'text-dark' }}" href="{{ route('orders.index', ['status' => 'processing']) }}" style="font-weight: 600;">Diproses</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $status === 'shipped' ? 'active bg-black text-gold' : 'text-dark' }}" href="{{ route('orders.index', ['status' => 'shipped']) }}" style="font-weight: 600;">Dikirim</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $status === 'delivered' ? 'active bg-black text-gold' : 'text-dark' }}" href="{{ route('orders.index', ['status' => 'delivered']) }}" style="font-weight: 600;">Selesai</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $status === 'cancelled' ? 'active bg-black text-gold' : 'text-dark' }}" href="{{ route('orders.index', ['status' => 'cancelled']) }}" style="font-weight: 600;">Dibatalkan</a>
                            </li>
                        </ul>

                        @if($orders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Kode Pesanan</th>
                                            <th>Tanggal</th>
                                            <th>Total Tagihan</th>
                                            <th>Metode Pembayaran</th>
                                            <th>Status Pesanan</th>
                                            <th>Status Bayar</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                            <tr>
                                                <td><strong class="text-black">#{{ $order->order_code }}</strong></td>
                                                <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                                <td><strong class="text-gold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
                                                <td>{{ $order->payment_method === 'COD' ? 'Cash on Delivery (COD)' : 'Transfer Bank ' . $order->payment_method }}</td>
                                                <td>
                                                    <span class="badge-status badge-{{ $order->status }} d-inline-block px-3 py-2 text-center text-capitalize">
                                                        @if($order->status === 'pending')
                                                            Pending
                                                        @elseif($order->status === 'processing')
                                                            Diproses
                                                        @elseif($order->status === 'shipped')
                                                            Dikirim
                                                        @elseif($order->status === 'delivered')
                                                            Selesai
                                                        @else
                                                            Dibatalkan
                                                        @endif
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($order->payment_status === 'paid')
                                                        <span class="badge bg-success px-3 py-2">Lunas</span>
                                                    @elseif($order->payment_status === 'refunded')
                                                        <span class="badge bg-info px-3 py-2 text-white">Dikembalikan</span>
                                                    @else
                                                        <span class="badge bg-warning text-black px-3 py-2">Belum Bayar</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center gap-1">
                                                        <a href="{{ route('orders.show', $order->order_code) }}" class="btn btn-outline-dark btn-sm px-3" style="border-radius: 0; font-weight: 600;">
                                                            Detail
                                                        </a>
                                                        @if($order->payment_method !== 'COD' && $order->payment_status === 'pending' && !$order->payment)
                                                            <a href="{{ route('payment.upload', $order->id) }}" class="btn btn-gold btn-sm px-3" style="border-radius: 0; font-weight: 600;">
                                                                Bayar
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $orders->appends(['status' => $status])->links('pagination::bootstrap-5') }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-box-open fa-5x text-muted mb-4 text-gold" style="color: var(--gold-color);"></i>
                                <h4 class="text-muted">Tidak ada pesanan</h4>
                                <p class="text-muted mb-4">Tidak ditemukan transaksi dengan status ini.</p>
                                <a href="{{ route('products.index') }}" class="btn btn-gold py-3 px-5 text-decoration-none" style="border-radius: 0; font-weight: 600;">
                                    <i class="fas fa-shopping-bag me-2"></i> Jelajahi Produk
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .badge-status {
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.8rem;
    }
    .badge-pending { background-color: #ffc107; color: #000; }
    .badge-processing { background-color: #17a2b8; color: #fff; }
    .badge-shipped { background-color: #6f42c1; color: #fff; } /* Purple for shipped */
    .badge-delivered { background-color: #28a745; color: #fff; }
    .badge-cancelled { background-color: #dc3545; color: #fff; }
    
    .nav-tabs .nav-link {
        border: none;
        border-bottom: 2px solid transparent;
        color: #495057;
        padding: 10px 20px;
    }
    .nav-tabs .nav-link.active {
        border-color: var(--gold-color) !important;
        background-color: #0A0A0A !important;
        color: var(--gold-color) !important;
    }
</style>
@endsection
