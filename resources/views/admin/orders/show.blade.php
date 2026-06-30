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
                                 <td class="text-end fw-bold">Rp {{ number_format($order->total_price - $order->shipping_cost, 0, ',', '.') }}</td>
                             </tr>
                             <tr>
                                 <td colspan="3" class="text-end fw-bold">
                                     Ongkos Kirim 
                                     @if($order->courier)
                                         ({{ $order->courier }} - {{ $order->courier_service }})
                                     @endif
                                 </td>
                                 <td class="text-end fw-bold">
                                     @if($order->shipping_cost == 0)
                                         <span class="badge bg-success">Gratis Ongkir</span>
                                     @else
                                         Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                                     @endif
                                 </td>
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
                         <td>
                             @if($order->shippingAddress)
                                 {{ $order->shippingAddress->address_line }}<br>
                                 <strong>Kecamatan:</strong> {{ $order->shippingAddress->district }}<br>
                                 <strong>Kota/Kab:</strong> {{ $order->shippingAddress->city_name }}<br>
                                 <strong>Provinsi:</strong> {{ $order->shippingAddress->province_name }}
                             @else
                                 {{ $order->shipping_address }}
                             @endif
                         </td>
                     </tr>
                     <tr>
                         <th>Kota / Kabupaten</th>
                         <td>{{ $order->shippingAddress->city_name ?? $order->city }}</td>
                     </tr>
                     <tr>
                         <th>Kode Pos</th>
                         <td>{{ $order->shippingAddress->postal_code ?? $order->postal_code }}</td>
                     </tr>
                     @if($order->courier)
                     <tr>
                         <th>Kurir & Layanan</th>
                         <td>
                             <span class="badge bg-secondary text-uppercase text-white">{{ $order->courier }}</span> - {{ $order->courier_service }}
                             @if($order->estimated_delivery)
                                 <small class="text-muted">({{ $order->estimated_delivery }})</small>
                             @endif
                         </td>
                     </tr>
                     @endif
                     @if($order->tracking_number)
                     <tr>
                         <th>Nomor Resi</th>
                         <td><strong class="text-gold" style="color: var(--gold-color);">{{ $order->tracking_number }}</strong></td>
                     </tr>
                     @endif
                 </table>
            </div>
        </div>
    </div>

    <!-- Management Sidebar -->
    <div class="col-lg-4">
        <!-- Status Update Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-black text-gold">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-cog me-1"></i> Update Status & Pengiriman</h6>
            </div>
            <div class="card-body">
                 <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                     @csrf
                     @method('PUT')
                     
                     <div class="order-shipping-panel">
                         <div class="mb-3">
                             <label class="form-label fw-bold">Status Pesanan</label>
                             <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                 <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                 <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Diproses</option>
                                 <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Dikirim</option>
                                 <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Selesai</option>
                                 <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                             </select>
                             @error('status')
                                 <div class="invalid-feedback">{{ $message }}</div>
                             @enderror
                         </div>

                         <div class="mb-3">
                             <label class="form-label fw-bold">Kurir</label>
                             <input type="text" name="courier" class="form-control bg-light" value="{{ $order->courier }}" readonly>
                         </div>

                         <div class="mb-3" id="tracking_number_group">
                             <label class="form-label fw-bold">Nomor Resi <small class="text-danger fw-normal">(wajib sebelum "Dikirim")</small></label>
                             <input type="text" name="tracking_number" id="tracking_number" class="form-control @error('tracking_number') is-invalid @enderror" value="{{ old('tracking_number', $order->tracking_number) }}" placeholder="Masukkan nomor resi dari kurir">
                             @error('tracking_number')
                                 <div class="invalid-feedback d-block">{{ $message }}</div>
                             @enderror
                         </div>

                         <div class="mb-4">
                             <label class="form-label fw-bold">Status Pembayaran</label>
                             <select name="payment_status" class="form-select @error('payment_status') is-invalid @enderror">
                                 <option value="unpaid" {{ in_array($order->payment_status, ['pending', 'failed']) ? 'selected' : '' }}>Belum Bayar</option>
                                 <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Sudah Bayar</option>
                             </select>
                             @error('payment_status')
                                 <div class="invalid-feedback">{{ $message }}</div>
                             @enderror
                         </div>

                         <button type="submit" class="btn-gold w-100 py-2 fw-semibold">
                             <i class="fas fa-save me-1"></i> Simpan Perubahan
                         </button>
                     </div>
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
@push('scripts')
<script>
    $(document).ready(function() {
        function toggleTrackingInput() {
            const status = $('#status').val();
            if (status === 'processing' || status === 'shipped') {
                $('#tracking_number_group').slideDown();
            } else {
                $('#tracking_number_group').slideUp();
            }
        }
        
        $('#status').on('change', toggleTrackingInput);
        toggleTrackingInput();
    });
</script>
@endpush
@endsection
