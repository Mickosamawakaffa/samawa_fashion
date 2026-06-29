@extends('admin.layout')

@section('title', 'Detail Pesanan - Admin Samawa')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detail Pesanan: {{ $order->order_code }}</h1>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm" style="border-radius: 0;">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <!-- Items & Shipping Info -->
    <div class="col-lg-8 mb-4">
        <!-- Products Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-black text-gold">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-shopping-bag me-1"></i> Produk Dipesan</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th width="150" class="text-end">Harga Satuan</th>
                                <th width="100" class="text-center">Jumlah</th>
                                <th width="150" class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="img-thumbnail me-2" style="max-width: 50px;">
                                            @endif
                                            <div>
                                                <strong>{{ $item->product->name }}</strong>
                                                <br><small class="text-muted">Kategori: {{ $item->product->category->name }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end fw-bold text-gold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Subtotal Produk</td>
                                <td class="text-end fw-bold">Rp {{ number_format($order->total_price - 15000, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Ongkos Kirim (Flat)</td>
                                <td class="text-end fw-bold">Rp 15.000</td>
                            </tr>
                            <tr class="table-dark">
                                <td colspan="3" class="text-end fw-bold">Total Pembayaran</td>
                                <td class="text-end fw-bold text-warning">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Shipping & Recipient Address -->
        <div class="card shadow">
            <div class="card-header py-3 bg-black text-gold">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-map-marker-alt me-1"></i> Informasi Penerima & Alamat Pengiriman</h6>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Nama Penerima</th>
                        <td><strong>{{ $order->recipient_name }}</strong></td>
                    </tr>
                    <tr>
                        <th>Nomor HP</th>
                        <td>{{ $order->phone }}</td>
                    </tr>
                    <tr>
                        <th>Alamat Lengkap</th>
                        <td>{{ $order->shipping_address }}</td>
                    </tr>
                    <tr>
                        <th>Kota / Kabupaten</th>
                        <td>{{ $order->city }}</td>
                    </tr>
                    <tr>
                        <th>Kode Pos</th>
                        <td>{{ $order->postal_code }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Management Sidebar -->
    <div class="col-lg-4">
        <!-- Status Update Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-black text-gold">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-cog me-1"></i> Kelola Transaksi</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Status Pesanan</label>
                        <select name="status" id="status" class="form-select">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="payment_status" class="form-label fw-bold">Status Pembayaran</label>
                        <select name="payment_status" id="payment_status" class="form-select">
                            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid (Lunas)</option>
                            <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-gold w-100 py-2 fw-semibold">
                        <i class="fas fa-save me-1"></i> Simpan Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Payment Verification Proof Card -->
        @if($order->payment_method !== 'COD')
            <div class="card shadow">
                <div class="card-header py-3 bg-black text-gold">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-file-invoice-dollar me-1"></i> Bukti Transfer Pembayaran</h6>
                </div>
                <div class="card-body text-center">
                    @if($order->payment)
                        <div class="mb-3">
                            <span class="text-muted d-block small mb-1">Bukti Transfer diupload pada:</span>
                            <strong>{{ $order->payment->created_at->format('d M Y H:i') }}</strong>
                        </div>
                        <div class="mb-3">
                            <a href="{{ asset('storage/' . $order->payment->proof_image) }}" target="_blank">
                                <img src="{{ asset('storage/' . $order->payment->proof_image) }}" alt="Proof" class="img-fluid img-thumbnail" style="max-height: 250px;">
                            </a>
                        </div>
                        <a href="{{ asset('storage/' . $order->payment->proof_image) }}" target="_blank" class="btn btn-outline-dark btn-sm w-100" style="border-radius: 0;">
                            <i class="fas fa-external-link-alt"></i> Lihat Ukuran Penuh
                        </a>
                    @else
                        <div class="py-4 text-muted">
                            <i class="fas fa-receipt fa-3x mb-3 text-gold" style="color: var(--gold-color);"></i>
                            <p class="small mb-0">Belum ada bukti transfer diupload oleh customer</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
