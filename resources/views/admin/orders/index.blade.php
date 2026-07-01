@extends('admin.layout')

@section('title', 'Kelola Pesanan - Admin Samawa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Kelola Pesanan</h1>
</div>

<!-- Order Filter Tabs -->
<div class="order-filter-tabs">
    <a href="?status=all" class="{{ !request('status') || request('status') === 'all' ? 'active' : '' }}">
        Semua
    </a>
    <a href="?status=pending" class="{{ request('status') === 'pending' ? 'active' : '' }}">
        Pending (<span>{{ $countPending }}</span>)
    </a>
    <a href="?status=processing" class="{{ request('status') === 'processing' ? 'active' : '' }}">
        Diproses (<span>{{ $countProcessing }}</span>)
    </a>
    <a href="?status=shipped" class="{{ request('status') === 'shipped' ? 'active' : '' }}">
        Dikirim (<span>{{ $countShipped }}</span>)
    </a>
    <a href="?status=delivered" class="{{ request('status') === 'delivered' ? 'active' : '' }}">
        Selesai (<span>{{ $countDelivered }}</span>)
    </a>
    <a href="?status=cancelled" class="{{ request('status') === 'cancelled' ? 'active' : '' }}">
        Dibatalkan (<span>{{ $countCancelled }}</span>)
    </a>
</div>

<!-- Bulk Action Bar -->
<div class="bulk-action-bar shadow-sm" style="display:none;">
    <div>
        <i class="fas fa-tasks text-gold me-2"></i>
        <span id="selectedCount">0</span> pesanan dipilih
    </div>
    <select id="bulkStatusSelect" class="form-select-sm d-inline-block">
        <option value="">-- Ubah Status Menjadi --</option>
        <option value="processing">Diproses</option>
        <option value="delivered">Selesai</option>
        <option value="cancelled">Dibatalkan</option>
    </select>
    <button id="applyBulkAction" class="btn btn-sm btn-gold">Terapkan ke Pesanan Terpilih</button>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 bg-black">
        <h6 class="m-0 font-weight-bold text-gold"><i class="fas fa-shopping-cart me-2"></i>Daftar Transaksi Pesanan</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th class="text-center" width="40"><input type="checkbox" id="selectAll"></th>
                        <th>Kode Pesanan</th>
                        <th>Customer</th>
                        <th>Tanggal Transaksi</th>
                        <th>Total Tagihan</th>
                        <th>Metode Bayar</th>
                        <th>Status Pesanan</th>
                        <th>Status Bayar</th>
                        <th class="text-center" width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="order-checkbox" value="{{ $order->id }}">
                            </td>
                            <td><strong class="text-gold">{{ $order->order_code }}</strong></td>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td><strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
                            <td><span class="text-uppercase small">{{ $order->payment_method }}</span></td>
                            <td>
                                <!-- DROPDOWN LANGSUNG DI TABEL, tanpa perlu buka detail -->
                                <select class="quick-status-select form-select-sm" data-order-id="{{ $order->id }}" data-original-value="{{ $order->status }}">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Diproses</option>
                                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Dikirim</option>
                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Selesai</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </td>
                            <td class="text-center payment-status-cell">
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
                            <td colspan="9" class="text-center py-4 text-muted">Belum ada transaksi pesanan masuk</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        const selectAll = $('#selectAll');
        const checkboxes = $('.order-checkbox');
        const bulkBar = $('.bulk-action-bar');
        const selectedCount = $('#selectedCount');

        // SweetAlert2 Toast configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Toggle bulk action bar visibility
        function updateBulkBar() {
            const checkedCount = $('.order-checkbox:checked').length;
            selectedCount.text(checkedCount);
            if (checkedCount > 0) {
                bulkBar.slideDown(200);
            } else {
                bulkBar.slideUp(200);
            }
        }

        // Select All handler
        selectAll.on('change', function() {
            checkboxes.prop('checked', this.checked);
            updateBulkBar();
        });

        // Individual checkbox handler
        checkboxes.on('change', function() {
            if (!this.checked) {
                selectAll.prop('checked', false);
            } else if ($('.order-checkbox:checked').length === checkboxes.length) {
                selectAll.prop('checked', true);
            }
            updateBulkBar();
        });

        // Quick update status directly in the table row
        $('.quick-status-select').on('change', function() {
            const selectEl = $(this);
            const orderId = selectEl.data('order-id');
            const originalVal = selectEl.data('original-value');
            const newVal = selectEl.val();

            // EXCEPTION: if changing to Shipped, redirect to details page for tracking number validation
            if (newVal === 'shipped') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Input Resi Wajib',
                    text: 'Untuk status "Dikirim", silakan isi nomor resi melalui halaman Detail/Kelola.',
                    confirmButtonText: 'Buka Detail',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: 'var(--gold-color)',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `/admin/orders/${orderId}`;
                    } else {
                        selectEl.val(originalVal);
                    }
                });
                return;
            }

            fetch(`/admin/orders/${orderId}/quick-status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify({ status: newVal })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    selectEl.data('original-value', newVal);
                    
                    // Update payment status badge dynamically if status is completed/delivered
                    if (newVal === 'delivered') {
                        const paymentCell = selectEl.closest('tr').find('.payment-status-cell');
                        paymentCell.html('<span class="badge bg-success px-2 py-1 text-capitalize">Lunas</span>');
                    }

                    Toast.fire({
                        icon: 'success',
                        title: 'Status berhasil diperbarui'
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.message || 'Gagal memperbarui status'
                    });
                    selectEl.val(originalVal);
                }
            })
            .catch(err => {
                console.error(err);
                Toast.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan sistem'
                });
                selectEl.val(originalVal);
            });
        });

        // Apply bulk actions
        $('#applyBulkAction').on('click', function() {
            const selectedIds = $('.order-checkbox:checked').map(function() {
                return $(this).val();
            }).get();
            const newStatus = $('#bulkStatusSelect').val();

            if (selectedIds.length === 0) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Pilih minimal satu pesanan'
                });
                return;
            }

            if (!newStatus) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Pilih status baru terlebih dahulu'
                });
                return;
            }

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Status ${selectedIds.length} pesanan terpilih akan diubah menjadi "${newStatus}".`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: 'var(--gold-color)',
                confirmButtonText: 'Ya, Terapkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/admin/orders/bulk-update-status', {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        body: JSON.stringify({
                            order_ids: selectedIds,
                            status: newStatus
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: `${data.count} status pesanan berhasil diperbarui secara massal!`,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: data.message || 'Terjadi kesalahan saat memperbarui status massal'
                            });
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan sistem'
                        });
                    });
                }
            });
        });
    });
</script>
@endpush
