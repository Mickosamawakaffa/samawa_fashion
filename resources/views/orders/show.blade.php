@extends('layouts.frontend')

@section('title', 'Detail Pesanan - Samawa Fashion')

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

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Pesanan Saya</a></li>
                <li class="breadcrumb-item active">#{{ $order->order_code }}</li>
            </ol>
        </nav>
        
        <!-- Timeline Progress -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4" style="font-family: 'Playfair Display', serif;">Status Progress Pesanan</h5>
                
                <div class="row text-center justify-content-between position-relative timeline-container">
                    <!-- Progress Line background -->
                    <div class="position-absolute top-50 start-50 translate-middle w-75 d-none d-md-block" style="height: 3px; background-color: #dee2e6; z-index: 1; margin-top: -15px;"></div>
                    
                    @php
                        $statuses = ['pending', 'processing', 'shipped', 'delivered'];
                        $currentIndex = array_search($order->status, $statuses);
                        if ($order->status === 'cancelled') {
                            $currentIndex = -1;
                        }
                    @endphp

                    @if($order->status === 'cancelled')
                        <div class="col-12 py-3">
                            <div class="alert alert-danger mb-0">
                                <i class="fas fa-times-circle me-2"></i> Pesanan Ini Telah Dibatalkan.
                            </div>
                        </div>
                    @else
                        <!-- Step 1: Pending -->
                        <div class="col-md-3 col-6 mb-3 position-relative" style="z-index: 2;">
                            <div class="timeline-dot mx-auto mb-2 {{ $currentIndex >= 0 ? 'bg-gold text-black fw-bold' : 'bg-light text-muted' }}" style="width: 40px; height: 40px; border-radius: 50%; line-height: 40px; border: 2px solid var(--gold-color);">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <span class="small fw-bold d-block">Pending</span>
                            <span class="text-muted small">Menunggu Pembayaran</span>
                        </div>
                        
                        <!-- Step 2: Processing -->
                        <div class="col-md-3 col-6 mb-3 position-relative" style="z-index: 2;">
                            <div class="timeline-dot mx-auto mb-2 {{ $currentIndex >= 1 ? 'bg-gold text-black fw-bold' : 'bg-light text-muted' }}" style="width: 40px; height: 40px; border-radius: 50%; line-height: 40px; border: 2px solid {{ $currentIndex >= 1 ? 'var(--gold-color)' : '#dee2e6' }};">
                                <i class="fas fa-cog"></i>
                            </div>
                            <span class="small fw-bold d-block">Diproses</span>
                            <span class="text-muted small">Sedang Dikemas</span>
                        </div>
                        
                        <!-- Step 3: Shipped -->
                        <div class="col-md-3 col-6 mb-3 position-relative" style="z-index: 2;">
                            <div class="timeline-dot mx-auto mb-2 {{ $currentIndex >= 2 ? 'bg-gold text-black fw-bold' : 'bg-light text-muted' }}" style="width: 40px; height: 40px; border-radius: 50%; line-height: 40px; border: 2px solid {{ $currentIndex >= 2 ? 'var(--gold-color)' : '#dee2e6' }};">
                                <i class="fas fa-shipping-fast"></i>
                            </div>
                            <span class="small fw-bold d-block">Dikirim</span>
                            <span class="text-muted small">Dalam Perjalanan</span>
                        </div>
                        
                        <!-- Step 4: Delivered -->
                        <div class="col-md-3 col-6 mb-3 position-relative" style="z-index: 2;">
                            <div class="timeline-dot mx-auto mb-2 {{ $currentIndex >= 3 ? 'bg-gold text-black fw-bold' : 'bg-light text-muted' }}" style="width: 40px; height: 40px; border-radius: 50%; line-height: 40px; border: 2px solid {{ $currentIndex >= 3 ? 'var(--gold-color)' : '#dee2e6' }};">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <span class="small fw-bold d-block">Selesai</span>
                            <span class="text-muted small">Barang Diterima</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Details Column -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-header bg-black text-gold p-3" style="color: var(--gold-color); font-weight: 600; border-radius: 15px 15px 0 0;">
                        <i class="fas fa-shopping-bag me-2"></i> Produk yang Dipesan
                    </div>
                    <div class="card-body p-4">
                        @foreach($order->items as $item)
                            <div class="row mb-3 pb-3 border-bottom align-items-center">
                                <div class="col-md-2 col-4">
                                    <img src="{{ $item->product->image ? Storage::url($item->product->image) : asset('images/no-image.jpg') }}" alt="{{ $item->product->name }}" class="img-fluid rounded shadow-sm" style="max-height: 80px; object-fit: cover;">
                                </div>
                                <div class="col-md-10 col-8">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="fw-semibold mb-1">{{ $item->product->name }}</h6>
                                            <p class="text-muted small mb-0">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                        <div>
                                            <strong class="text-gold" style="color: var(--gold-color);">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        @php
                            $shippingCost = $order->total_price > 500000 ? 0 : 15000;
                            $subtotal = $order->total_price - $shippingCost;
                        @endphp
                        <div class="d-flex justify-content-between mt-4">
                            <span>Subtotal</span>
                            <span class="fw-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Ongkos Kirim</span>
                            <span class="fw-semibold">{{ $shippingCost == 0 ? 'Gratis' : 'Rp 15.000' }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total Tagihan</strong>
                            <strong class="text-gold fs-5" style="color: var(--gold-color);">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Recipient Info Card -->
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-header bg-black text-gold p-3" style="color: var(--gold-color); font-weight: 600; border-radius: 15px 15px 0 0;">
                        <i class="fas fa-map-marker-alt me-2"></i> Informasi Pengiriman
                    </div>
                    <div class="card-body p-4">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td width="30%" class="text-muted small">Nama Penerima</td>
                                <td><strong>{{ $order->recipient_name }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted small">Nomor HP</td>
                                <td>{{ $order->phone }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted small">Alamat Lengkap</td>
                                <td>{{ $order->shipping_address }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted small">Kota / Kabupaten</td>
                                <td>{{ $order->city }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted small">Kode Pos</td>
                                <td>{{ $order->postal_code }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Status & Action Sidebar Column -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-header bg-black text-gold p-3" style="color: var(--gold-color); font-weight: 600; border-radius: 15px 15px 0 0;">
                        <i class="fas fa-info-circle me-2"></i> Status Pesanan
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <span class="text-muted small d-block mb-1">Status Pengiriman:</span>
                            <span class="badge-status badge-{{ $order->status }} d-inline-block px-3 py-2 text-capitalize fw-bold">
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
                        </div>
                        <div class="mb-4">
                            <span class="text-muted small d-block mb-1">Status Pembayaran:</span>
                            @if($order->payment_status === 'paid')
                                <span class="badge bg-success px-3 py-2 text-capitalize fw-bold">Lunas</span>
                            @elseif($order->payment_status === 'refunded')
                                <span class="badge bg-info px-3 py-2 text-capitalize fw-bold text-white">Dikembalikan</span>
                            @else
                                <span class="badge bg-warning text-black px-3 py-2 text-capitalize fw-bold">Belum Bayar</span>
                            @endif
                        </div>
                        <div class="mb-4">
                            <span class="text-muted small d-block">Metode Pembayaran:</span>
                            <strong>{{ $order->payment_method === 'COD' ? 'Cash on Delivery (COD)' : 'Transfer Bank ' . $order->payment_method }}</strong>
                        </div>
                        <div class="text-muted small">
                            <span class="d-block">Tanggal Transaksi:</span>
                            <strong>{{ $order->created_at->format('d M Y H:i') }}</strong>
                        </div>

                        <!-- Cancellation action -->
                        @if($order->status === 'pending')
                            <form action="{{ route('orders.cancel', $order->order_code) }}" method="POST" class="mt-4" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Stok produk akan dikembalikan.')">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger w-100 py-2" style="border-radius: 0; font-weight: 600;">
                                    <i class="fas fa-times me-1"></i> Batalkan Order
                                </button>
                            </form>
                        @endif

                        <!-- Confirmation action -->
                        @if($order->status === 'shipped')
                            <form action="{{ route('orders.confirm_received', $order->order_code) }}" method="POST" class="mt-4" onsubmit="return confirm('Konfirmasi bahwa pesanan sudah Anda terima dengan baik?')">
                                @csrf
                                <button type="submit" class="btn btn-success w-100 py-2 text-white" style="border-radius: 0; font-weight: 600;">
                                    <i class="fas fa-check-circle me-1"></i> Konfirmasi Barang Diterima
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Pay Now / Upload Proof payment section -->
                @if($order->payment_method !== 'COD' && $order->status !== 'cancelled')
                    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                        <div class="card-header bg-black text-gold p-3" style="color: var(--gold-color); font-weight: 600; border-radius: 15px 15px 0 0;">
                            <i class="fas fa-file-invoice-dollar me-2"></i> Konfirmasi Pembayaran
                        </div>
                        <div class="card-body p-4 text-center">
                            @if($order->payment)
                                <i class="fas fa-clock fa-3x text-warning mb-3"></i>
                                <h6>Bukti Pembayaran Terkirim</h6>
                                <p class="text-muted small">Bukti transfer Anda telah dikirim dan saat ini sedang dalam proses verifikasi oleh tim keuangan kami.</p>
                                <div class="bg-light p-2 rounded text-truncate border">
                                    <a href="{{ asset('storage/' . $order->payment->proof_image) }}" target="_blank" class="small text-decoration-none text-gold fw-semibold">
                                        <i class="fas fa-image me-1"></i> Lihat Bukti Transfer
                                    </a>
                                </div>
                            @else
                                <i class="fas fa-university fa-3x text-muted mb-3"></i>
                                <h6>Belum Melakukan Transfer?</h6>
                                <p class="text-muted small">Silakan transfer sebesar <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong> ke rekening Bank {{ $order->payment_method }}.</p>
                                <a href="{{ route('payment.upload', $order->id) }}" class="btn-gold d-block w-100 py-2 text-center text-decoration-none fw-semibold mb-2">
                                    <i class="fas fa-upload me-1"></i> Upload Bukti Sekarang
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .badge-status {
        border-radius: 20px;
        font-size: 0.85rem;
    }
    .badge-pending { background-color: #ffc107; color: #000; }
    .badge-processing { background-color: #17a2b8; color: #fff; }
    .badge-shipped { background-color: #6f42c1; color: #fff; } /* Shipped purple */
    .badge-delivered { background-color: #28a745; color: #fff; }
    .badge-cancelled { background-color: #dc3545; color: #fff; }
    
    .timeline-dot {
        transition: all 0.3s ease;
    }
    .bg-gold {
        background-color: var(--gold-color) !important;
        color: black !important;
    }
</style>
@endsection
