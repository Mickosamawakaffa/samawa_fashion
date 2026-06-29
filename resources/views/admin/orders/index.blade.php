@extends('admin.layout')

@section('title', 'Kelola Pesanan - Admin Samawa')

@section('content')
<h1 class="h3 mb-4 text-gray-800">Kelola Pesanan</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-gold" style="color: var(--gold-color);">Daftar Transaksi Pesanan</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Kode Pesanan</th>
                        <th>Customer</th>
                        <th>Tanggal Transaksi</th>
                        <th>Total Tagihan</th>
                        <th>Metode Bayar</th>
                        <th class="text-center">Status Pesanan</th>
                        <th class="text-center">Status Bayar</th>
                        <th class="text-center" width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td><strong class="text-gold">{{ $order->order_code }}</strong></td>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td><strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
                            <td><span class="text-uppercase small">{{ $order->payment_method }}</span></td>
                            <td class="text-center">
                                <span class="badge-status badge-{{ $order->status }} d-inline-block px-3 py-1 text-capitalize fw-semibold small">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($order->payment_status === 'paid')
                                    <span class="badge bg-success px-2 py-1 text-capitalize">Lunas</span>
                                @elseif($order->payment_status === 'refunded')
                                    <span class="badge bg-info px-2 py-1 text-capitalize">Dikembalikan</span>
                                @else
                                    <span class="badge bg-warning text-black px-2 py-1 text-capitalize">Pending</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-dark btn-sm" style="border-radius: 0;">
                                    <i class="fas fa-eye"></i> Detail / Kelola
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Belum ada transaksi pesanan masuk</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
