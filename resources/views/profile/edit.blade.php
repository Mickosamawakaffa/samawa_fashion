@extends('layouts.frontend')

@section('title', 'Profil Saya - Samawa Fashion')

@section('content')
<div class="py-5" style="background-color: #FAF6F0; min-height: 80vh;">
    <div class="container">
        <!-- Success Alert -->
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

        @if($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal',
                        text: '{{ $errors->first() }}'
                    });
                });
            </script>
        @endif

        <div class="row">
            <!-- Sidebar Panel -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm text-center p-4" style="border-radius: 15px; background: #0A0A0A; color: white;">
                    <div class="position-relative d-inline-block mx-auto mb-3">
                        <img src="{{ $user->avatar ? Storage::url($user->avatar) : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) . '?s=150&d=mp' }}" 
                             alt="{{ $user->name }}" 
                             class="rounded-circle border border-3 border-gold" 
                             style="width: 120px; height: 120px; object-fit: cover; border-color: var(--gold-color) !important;">
                    </div>
                    <h4 class="fw-bold mb-1" style="font-family: 'Playfair Display', serif; color: var(--gold-color);">{{ $user->name }}</h4>
                    <p class="text-muted small mb-3">{{ $user->email }}</p>
                    <span class="badge bg-gold text-black px-3 py-2 fw-semibold" style="font-size: 0.85rem; border-radius: 20px; background-color: var(--gold-color);">
                        {{ $user->role === 'admin' ? 'Administrator' : 'Member Premium' }}
                    </span>
                    
                    <div class="nav flex-column nav-pills mt-4 text-start gap-2" id="profile-tabs" role="tablist">
                        <button class="nav-link active tab-btn" id="personal-tab" data-bs-toggle="pill" data-bs-target="#personal-content" type="button" role="tab">
                            <i class="fas fa-user me-2"></i> Informasi Pribadi
                        </button>
                        <button class="nav-link tab-btn" id="address-tab" data-bs-toggle="pill" data-bs-target="#address-content" type="button" role="tab">
                            <i class="fas fa-map-marker-alt me-2"></i> Daftar Alamat
                        </button>
                        <button class="nav-link tab-btn" id="orders-tab" data-bs-toggle="pill" data-bs-target="#orders-content" type="button" role="tab">
                            <i class="fas fa-shopping-bag me-2"></i> Riwayat Pesanan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content Panel -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm p-4 bg-white" style="border-radius: 15px;">
                    <div class="tab-content" id="profile-tabs-content">
                        
                        <!-- Tab 1: Personal Info -->
                        <div class="tab-pane fade show active" id="personal-content" role="tabpanel">
                            <h3 class="fw-bold mb-4" style="font-family: 'Playfair Display', serif; border-bottom: 2px solid var(--gold-color); padding-bottom: 10px;">Informasi Pribadi</h3>
                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Nama Lengkap</label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required style="border-radius: 0;">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Alamat Email</label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required style="border-radius: 0;">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Nomor Handphone</label>
                                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" style="border-radius: 0;">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Foto Profil</label>
                                        <input type="file" name="avatar" class="form-control" style="border-radius: 0;">
                                    </div>
                                </div>

                                <div class="mt-5">
                                    <h5 class="fw-bold text-muted mb-3">Ubah Password <span class="small font-normal text-muted">(Kosongkan jika tidak ingin diubah)</span></h5>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Password Lama</label>
                                            <input type="password" name="current_password" class="form-control" style="border-radius: 0;">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Password Baru</label>
                                            <input type="password" name="password" class="form-control" style="border-radius: 0;">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                                            <input type="password" name="password_confirmation" class="form-control" style="border-radius: 0;">
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end mt-4">
                                    <button type="submit" class="btn btn-gold px-4 py-2" style="border-radius: 0; font-weight: 600;">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>

                        <!-- Tab 2: Addresses Management -->
                        <div class="tab-pane fade" id="address-content" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-4" style="border-bottom: 2px solid var(--gold-color); padding-bottom: 10px;">
                                <h3 class="fw-bold mb-0" style="font-family: 'Playfair Display', serif;">Daftar Alamat Pengiriman</h3>
                                <button class="btn btn-gold btn-sm" onclick="showAddAddressModal()" style="border-radius: 0; font-weight: 600; padding: 8px 15px;">
                                    <i class="fas fa-plus me-1"></i> Tambah Alamat
                                </button>
                            </div>

                            @if($addresses->count() > 0)
                                <div class="row">
                                    @foreach($addresses as $addr)
                                        <div class="col-md-6 mb-3">
                                            <div class="card h-100 {{ $addr->is_default ? 'border-gold' : 'border-light' }} shadow-sm" style="border-radius: 10px; position: relative;">
                                                <div class="card-body p-3">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <h6 class="fw-bold mb-0">{{ $addr->recipient_name }}</h6>
                                                        @if($addr->is_default)
                                                            <span class="badge bg-gold text-black" style="font-size: 0.75rem; border-radius: 3px;">Default</span>
                                                        @endif
                                                    </div>
                                                    <p class="text-muted small mb-1"><i class="fas fa-phone me-1"></i> {{ $addr->phone }}</p>
                                                    <p class="text-muted small mb-3">
                                                        {{ $addr->address_line }}, {{ $addr->city }}, {{ $addr->province }}, {{ $addr->postal_code }}
                                                    </p>
                                                    
                                                    <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top border-light">
                                                        <div>
                                                            @if(!$addr->is_default)
                                                                <form action="{{ route('profile.addresses.default', $addr->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-link text-decoration-none text-gold p-0 small fw-bold" style="font-size: 0.85rem;">Set Default</button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                        <div class="d-flex gap-2">
                                                            <button class="btn btn-outline-secondary btn-sm" onclick="showEditAddressModal({{ json_encode($addr) }})" style="border-radius: 0; font-size: 0.8rem; padding: 4px 8px;">
                                                                Edit
                                                            </button>
                                                            <form action="{{ route('profile.addresses.destroy', $addr->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-outline-danger btn-sm" style="border-radius: 0; font-size: 0.8rem; padding: 4px 8px;">
                                                                    Hapus
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-map-marked-alt text-muted mb-3" style="font-size: 3rem;"></i>
                                    <h5>Belum ada alamat pengiriman</h5>
                                    <p class="text-muted">Tambahkan alamat pengiriman untuk mempercepat checkout belanjaan Anda.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Tab 3: Order History -->
                        <div class="tab-pane fade" id="orders-content" role="tabpanel">
                            <h3 class="fw-bold mb-4" style="font-family: 'Playfair Display', serif; border-bottom: 2px solid var(--gold-color); padding-bottom: 10px;">Riwayat Pesanan</h3>
                            
                            @if($orders->count() > 0)
                                <div class="table-responsive">
                                    <table class="table align-middle">
                                        <thead>
                                            <tr>
                                                <th>Kode Order</th>
                                                <th>Tanggal</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($orders as $ord)
                                                <tr>
                                                    <td><strong class="text-black">#{{ $ord->order_code }}</strong></td>
                                                    <td>{{ $ord->created_at->format('d M Y') }}</td>
                                                    <td class="text-gold fw-bold">Rp {{ number_format($ord->total_price, 0, ',', '.') }}</td>
                                                    <td>
                                                        @if($ord->status === 'pending')
                                                            <span class="badge bg-warning text-dark">Pending</span>
                                                        @elseif($ord->status === 'processing')
                                                            <span class="badge bg-primary">Diproses</span>
                                                        @elseif($ord->status === 'shipped')
                                                            <span class="badge bg-info">Dikirim</span>
                                                        @elseif($ord->status === 'delivered')
                                                            <span class="badge bg-success">Selesai</span>
                                                        @else
                                                            <span class="badge bg-danger">Dibatalkan</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('orders.show', $ord->order_code) }}" class="btn btn-outline-dark btn-sm" style="border-radius: 0; font-size: 0.8rem; font-weight: 600;">
                                                            Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-shopping-bag text-muted mb-3" style="font-size: 3rem;"></i>
                                    <h5>Belum ada riwayat pesanan</h5>
                                    <p class="text-muted">Ayo belanja produk premium kami dan rasakan kemewahannya!</p>
                                    <a href="{{ route('products.index') }}" class="btn btn-gold mt-2" style="border-radius: 0; font-weight: 600;">Belanja Sekarang</a>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Alamat -->
<div class="modal fade" id="addressModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header bg-black text-gold">
                <h5 class="modal-title fw-bold" id="addressModalTitle" style="font-family: 'Playfair Display', serif;">Tambah Alamat Pengiriman</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addressForm" method="POST" action="">
                @csrf
                <input type="hidden" name="_method" id="addressFormMethod" value="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Penerima</label>
                        <input type="text" name="recipient_name" id="addr_name" class="form-control" required style="border-radius: 0;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nomor Handphone</label>
                        <input type="text" name="phone" id="addr_phone" class="form-control" required style="border-radius: 0;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat Lengkap</label>
                        <textarea name="address_line" id="addr_address" class="form-control" rows="3" required style="border-radius: 0;"></textarea>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Kota/Kabupaten</label>
                            <input type="text" name="city" id="addr_city" class="form-control" required style="border-radius: 0;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Provinsi</label>
                            <input type="text" name="province" id="addr_province" class="form-control" required style="border-radius: 0;">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode Pos</label>
                        <input type="text" name="postal_code" id="addr_postal" class="form-control" required style="border-radius: 0;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal" style="border-radius: 0;">Tutup</button>
                    <button type="submit" class="btn btn-gold" style="border-radius: 0; font-weight: 600;">Simpan Alamat</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .tab-btn {
        background: transparent;
        color: rgba(255,255,255,0.7) !important;
        border-radius: 0 !important;
        border: none;
        padding: 12px 20px;
        text-align: left;
        transition: all 0.3s ease;
        font-weight: 600;
    }
    .tab-btn:hover,
    .tab-btn.active {
        background-color: var(--gold-color) !important;
        color: black !important;
    }
    .border-gold {
        border: 2px solid var(--gold-color) !important;
    }
</style>
@endsection

@push('scripts')
<script>
    // Keep tab active on redirect (using hash parameter)
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');
        if (tab === 'alamat') {
            const trigger = document.querySelector('#address-tab');
            if (trigger) bootstrap.Tab.getOrCreateInstance(trigger).show();
        } else if (tab === 'pesanan') {
            const trigger = document.querySelector('#orders-tab');
            if (trigger) bootstrap.Tab.getOrCreateInstance(trigger).show();
        }
    });

    function showAddAddressModal() {
        document.getElementById('addressModalTitle').textContent = 'Tambah Alamat Pengiriman';
        document.getElementById('addressForm').action = "{{ route('profile.addresses.store') }}";
        document.getElementById('addressFormMethod').value = "POST";
        
        document.getElementById('addr_name').value = '';
        document.getElementById('addr_phone').value = '';
        document.getElementById('addr_address').value = '';
        document.getElementById('addr_city').value = '';
        document.getElementById('addr_province').value = '';
        document.getElementById('addr_postal').value = '';
        
        new bootstrap.Modal(document.getElementById('addressModal')).show();
    }

    function showEditAddressModal(address) {
        document.getElementById('addressModalTitle').textContent = 'Edit Alamat Pengiriman';
        document.getElementById('addressForm').action = `/profile/addresses/${address.id}`;
        document.getElementById('addressFormMethod').value = "PUT";
        
        document.getElementById('addr_name').value = address.recipient_name;
        document.getElementById('addr_phone').value = address.phone;
        document.getElementById('addr_address').value = address.address_line;
        document.getElementById('addr_city').value = address.city;
        document.getElementById('addr_province').value = address.province;
        document.getElementById('addr_postal').value = address.postal_code;
        
        new bootstrap.Modal(document.getElementById('addressModal')).show();
    }
</script>
@endpush
