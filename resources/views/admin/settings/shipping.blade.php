@extends('admin.layout')

@section('title', 'Pengaturan Pengiriman - Admin Samawa')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-truck me-2"></i> Pengaturan Pengiriman</h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-black text-gold" style="background-color: var(--primary-color); color: var(--gold-color);">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-sliders-h me-1"></i> Konfigurasi Kurir & Ekspedisi</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.shipping.update') }}" method="POST">
                    @csrf
                    
                    <!-- Toko Origin (Asal Pengiriman) -->
                    <h5 class="fw-bold mb-3 border-bottom pb-2 text-gray-800">Kota Asal Pengiriman Toko</h5>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="origin_province" class="form-label fw-bold">Provinsi Asal</label>
                            <select id="origin_province" name="shipping_origin_province" class="form-select @error('shipping_origin_province') is-invalid @enderror" required>
                                <option value="">Pilih Provinsi</option>
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov->id }}" {{ $originProvince == $prov->id ? 'selected' : '' }}>
                                        {{ $prov->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('shipping_origin_province')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="origin_city" class="form-label fw-bold">Kota / Kabupaten Asal</label>
                            <select id="origin_city" name="shipping_origin_city" class="form-select @error('shipping_origin_city') is-invalid @enderror" required {{ empty($cities) ? 'disabled' : '' }}>
                                <option value="">Pilih Kota Asal</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ $originCity == $city->id ? 'selected' : '' }}>
                                        {{ $city->type }} {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('shipping_origin_city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Aktifkan Kurir Ekspedisi -->
                    <h5 class="fw-bold mb-3 border-bottom pb-2 text-gray-800">Ekspedisi Aktif</h5>
                    
                    <div class="mb-4">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="shipping_courier_jne" id="courier_jne" value="1" {{ $courierJne ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="courier_jne">
                                <i class="fas fa-truck-moving text-primary me-2"></i> JNE (Jalur Nugraha Ekakurir)
                            </label>
                        </div>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="shipping_courier_jnt" id="courier_jnt" value="1" {{ $courierJnt ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="courier_jnt">
                                <i class="fas fa-truck-moving text-danger me-2"></i> J&T Express
                            </label>
                        </div>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="shipping_courier_sicepat" id="courier_sicepat" value="1" {{ $courierSicepat ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="courier_sicepat">
                                <i class="fas fa-truck-moving text-warning me-2"></i> SiCepat Ekspres
                            </label>
                        </div>
                    </div>

                    <!-- Parameter Pengiriman Lainnya -->
                    <h5 class="fw-bold mb-3 border-bottom pb-2 text-gray-800">Parameter Tambahan</h5>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="free_shipping_min" class="form-label fw-bold">Minimal Belanja Gratis Ongkir (Rupiah)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">Rp</span>
                                <input type="number" name="shipping_free_min_spend" id="free_shipping_min" class="form-control @error('shipping_free_min_spend') is-invalid @enderror" value="{{ old('shipping_free_min_spend', $freeShippingThreshold) }}" required min="0">
                            </div>
                            @error('shipping_free_min_spend')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted">Set ke 0 untuk menonaktifkan promo gratis ongkir otomatis.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="default_weight" class="form-label fw-bold">Berat Default Produk (Gram)</label>
                            <div class="input-group">
                                <input type="number" name="shipping_default_weight" id="default_weight" class="form-control @error('shipping_default_weight') is-invalid @enderror" value="{{ old('shipping_default_weight', $defaultWeight) }}" required min="1">
                                <span class="input-group-text bg-light">gram</span>
                            </div>
                            @error('shipping_default_weight')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted">Digunakan sebagai cadangan jika produk di keranjang tidak memiliki nilai berat.</div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-gold w-100 py-3 fw-bold">
                        <i class="fas fa-save me-1"></i> Simpan Pengaturan Pengiriman
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow">
            <div class="card-header py-3 bg-black text-gold">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-info-circle me-1"></i> Catatan Integrasi</h6>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-3">Sistem pengiriman Samawa Fashion saat ini terintegrasi dengan <strong>RajaOngkir API Starter</strong>.</p>
                <div class="alert alert-info small py-2 mb-3">
                    <i class="fas fa-key me-1"></i> <strong>RajaOngkir API Key</strong> saat ini dikonfigurasi melalui file environment <code>.env</code>.
                </div>
                <ul class="small text-muted ps-3 mb-0">
                    <li class="mb-2"><strong>Asal Toko:</strong> Digunakan sebagai parameter asal (origin) untuk menghitung ongkos kirim.</li>
                    <li class="mb-2"><strong>Ekspedisi:</strong> Menonaktifkan ekspedisi di sini akan menyembunyikan pilihan tersebut dari customer di halaman checkout.</li>
                    <li><strong>Cache:</strong> Untuk menghemat kuota API RajaOngkir, daftar provinsi dan kota disimpan di cache sistem selama 24 jam.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#origin_province').on('change', function() {
        const provId = $(this).val();
        $('#origin_city').empty().append('<option value="">Pilih Kota Asal</option>').prop('disabled', true);
        
        if (!provId) return;

        $.ajax({
            url: '{{ route('checkout.cities') }}',
            type: 'GET',
            data: { province_id: provId },
            success: function(cities) {
                $('#origin_city').prop('disabled', false);
                cities.forEach(function(city) {
                    const cityName = city.type + ' ' + city.city_name;
                    $('#origin_city').append('<option value="' + city.city_id + '">' + cityName + '</option>');
                });
            },
            error: function(xhr) {
                console.error('Failed to load cities:', xhr);
            }
        });
    });
});
</script>
@endpush
