@extends('layouts.frontend')

@php
    $cartItems            = $cartItems            ?? collect();
    $cartTotal            = $cartTotal            ?? 0;
    $freeShippingThreshold = $freeShippingThreshold ?? 500000;
    $provinces            = $provinces            ?? collect();
    $user                 = $user                 ?? null;
    $defaultAddress       = $defaultAddress       ?? null;
@endphp

@section('title', 'Checkout - Samawa Fashion')

@section('content')
<div class="py-5">
    <div class="container">
        <!-- Alerts -->
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="section-title">
            <h2>Checkout</h2>
            <div class="divider"></div>
        </div>
        
        <div class="row">
            <!-- Checkout Form Column -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm" style="border-radius: 10px;">
                    <div class="card-header bg-black text-gold" style="color: var(--gold-color); font-weight: 600; border-radius: 10px 10px 0 0;">
                        <i class="fas fa-shipping-fast me-2"></i> Informasi Pengiriman & Pembayaran
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('checkout.store') }}" method="POST">
                            @csrf
                            
                            <h5 class="mb-3 fw-semibold border-bottom pb-2">Data Penerima</h5>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="use_profile_data">
                                <label class="form-check-label small fw-semibold" for="use_profile_data">
                                    Gunakan data profil/alamat default saya
                                </label>
                            </div>

                            <div class="mb-3">
                                <label for="recipient_name" class="form-label">Nama Penerima <span class="text-danger">*</span></label>
                                <input type="text" name="recipient_name" id="recipient_name" class="form-control @error('recipient_name') is-invalid @enderror" value="{{ old('recipient_name', $defaultAddress->recipient_name ?? $user->name) }}" required placeholder="Nama lengkap penerima paket">
                                @error('recipient_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Nomor HP <span class="text-danger">*</span></label>
                                <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $defaultAddress->phone ?? $user->phone) }}" required placeholder="Nomor telepon aktif, cth: 0812345678">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <h5 class="mb-3 fw-semibold border-bottom pb-2 mt-4">Alamat Pengiriman</h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="province_select" class="form-label">Provinsi <span class="text-danger">*</span></label>
                                    <select id="province_select" name="province_id" class="form-select @error('province_id') is-invalid @enderror" required>
                                        <option value="">Pilih Provinsi</option>
                                        @foreach($provinces as $prov)
                                            <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="province_name" id="province_name">
                                    @error('province_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="city_select" class="form-label">Kota / Kabupaten <span class="text-danger">*</span></label>
                                    <select id="city_select" name="city_id" class="form-select @error('city_id') is-invalid @enderror" required disabled>
                                        <option value="">Pilih Kota (Pilih Provinsi dahulu)</option>
                                    </select>
                                    <input type="hidden" name="city_name" id="city_name">
                                    @error('city_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="district" class="form-label">Kecamatan <span class="text-danger">*</span></label>
                                    <input type="text" name="district" id="district" class="form-control @error('district') is-invalid @enderror" value="{{ old('district', $defaultAddress->district ?? '') }}" required placeholder="Nama Kecamatan">
                                    @error('district')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="postal_code" class="form-label">Kode Pos <span class="text-danger">*</span></label>
                                    <input type="text" name="postal_code" id="postal_code" class="form-control @error('postal_code') is-invalid @enderror" value="{{ old('postal_code', $defaultAddress->postal_code ?? '') }}" required placeholder="Cth: 12345">
                                    @error('postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="address_line" class="form-label">Alamat Lengkap (Jalan, No. Rumah, RT/RW, Kelurahan) <span class="text-danger">*</span></label>
                                <textarea name="address_line" id="address_line" class="form-control @error('address_line') is-invalid @enderror" rows="3" required placeholder="Nama jalan, nomor rumah, RT/RW, dan kelurahan">{{ old('address_line', $defaultAddress->address_line ?? $user->address) }}</textarea>
                                @error('address_line')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Pilihan Kurir Section -->
                            <h5 class="mb-3 fw-semibold border-bottom pb-2 mt-4">Pilihan Kurir & Ongkos Kirim</h5>
                            <div id="shipping-placeholder" class="alert alert-warning small mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                Silakan pilih Provinsi dan Kota/Kabupaten terlebih dahulu untuk menghitung ongkos kirim.
                            </div>
                            
                            <div id="shipping-rates-container" class="mb-4 p-3 border rounded bg-light" style="display:none; position: relative;">
                                <div class="text-center py-4" id="shipping-loading" style="display:none;">
                                    <div class="spinner-border text-gold" role="status" style="color: var(--gold-color);">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="text-muted small mt-2 mb-0">Menghitung ongkos kirim...</p>
                                </div>
                                <div id="shipping-rates-list" class="row">
                                    <!-- Radio options populated via AJAX -->
                                </div>
                            </div>

                            <input type="hidden" name="courier" id="selected_courier">
                            <input type="hidden" name="courier_service" id="selected_service">
                            <input type="hidden" name="shipping_cost" id="selected_shipping_cost" value="0">
                            <input type="hidden" name="estimated_delivery" id="selected_estimated_delivery">
                            
                            <h5 class="mb-3 fw-semibold border-bottom pb-2">Metode Pembayaran</h5>
                            
                            <div class="mb-4">
                                <div class="form-check mb-3 p-3 border rounded border-gold" style="border-color: var(--gold-color) !important; background-color: rgba(201, 168, 76, 0.05);">
                                    <input type="radio" name="payment_method" class="form-check-input ms-0 me-2" id="pay_midtrans" value="Midtrans" checked>
                                    <label class="form-check-label fw-semibold" for="pay_midtrans">
                                        <i class="fas fa-credit-card text-gold me-2" style="color: var(--gold-color);"></i> Pembayaran Online Otomatis (Midtrans)
                                    </label>
                                    <p class="text-muted small ms-4 mb-0 mt-1">Bayar secara instan menggunakan E-Wallet (Gopay, ShopeePay, QRIS), Virtual Account, kartu kredit, atau retail store.</p>
                                </div>

                                <div class="form-check mb-3 p-3 border rounded">
                                    <input type="radio" name="payment_method" class="form-check-input ms-0 me-2" id="pay_bca" value="BCA">
                                    <label class="form-check-label fw-semibold" for="pay_bca">
                                        <i class="fas fa-university text-gold me-2" style="color: var(--gold-color);"></i> Transfer Bank BCA (Verifikasi Manual)
                                    </label>
                                    <p class="text-muted small ms-4 mb-0 mt-1">Lakukan transfer ke rekening BCA Samawa Fashion. Bukti transfer harus diunggah setelah pemesanan.</p>
                                </div>

                                <div class="form-check mb-3 p-3 border rounded">
                                    <input type="radio" name="payment_method" class="form-check-input ms-0 me-2" id="pay_bri" value="BRI">
                                    <label class="form-check-label fw-semibold" for="pay_bri">
                                        <i class="fas fa-university text-gold me-2" style="color: var(--gold-color);"></i> Transfer Bank BRI (Verifikasi Manual)
                                    </label>
                                    <p class="text-muted small ms-4 mb-0 mt-1">Lakukan transfer ke rekening BRI Samawa Fashion. Bukti transfer harus diunggah setelah pemesanan.</p>
                                </div>

                                <div class="form-check mb-3 p-3 border rounded">
                                    <input type="radio" name="payment_method" class="form-check-input ms-0 me-2" id="pay_mandiri" value="Mandiri">
                                    <label class="form-check-label fw-semibold" for="pay_mandiri">
                                        <i class="fas fa-university text-gold me-2" style="color: var(--gold-color);"></i> Transfer Bank Mandiri (Verifikasi Manual)
                                    </label>
                                    <p class="text-muted small ms-4 mb-0 mt-1">Lakukan transfer ke rekening Mandiri Samawa Fashion. Bukti transfer harus diunggah setelah pemesanan.</p>
                                </div>

                                <div class="form-check p-3 border rounded">
                                    <input type="radio" name="payment_method" class="form-check-input ms-0 me-2" id="pay_cod" value="COD">
                                    <label class="form-check-label fw-semibold" for="pay_cod">
                                        <i class="fas fa-money-bill-wave text-gold me-2" style="color: var(--gold-color);"></i> Cash On Delivery (COD)
                                    </label>
                                    <p class="text-muted small ms-4 mb-0 mt-1">Bayar langsung secara tunai kepada kurir saat barang Anda sampai di alamat tujuan.</p>
                                </div>
                                
                                @error('payment_method')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="accept_terms" required style="cursor: pointer;">
                                <label class="form-check-label small fw-semibold cursor-pointer" for="accept_terms">
                                    Saya menyetujui <a href="{{ route('terms') }}" target="_blank" class="text-gold fw-bold" style="color: var(--gold-color);">Syarat & Ketentuan</a> yang berlaku di Samawa Fashion. *
                                </label>
                            </div>
                            
                            <button type="submit" id="btn-checkout-submit" class="btn-gold w-100 py-3 text-center" disabled>
                                <i class="fas fa-check me-2"></i> Buat Pesanan Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar Order Summary Column -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm" style="border-radius: 10px;">
                    <div class="card-header bg-black text-gold" style="color: var(--gold-color); font-weight: 600; border-radius: 10px 10px 0 0;">
                        <i class="fas fa-shopping-bag me-2"></i> Ringkasan Pesanan
                    </div>
                    <div class="card-body p-4">
                        @foreach($cart->items as $item)
                            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                                <div style="max-width: 70%;">
                                    <h6 class="mb-1 fw-semibold text-truncate">{{ $item->product->name }}</h6>
                                    <small class="text-muted">
                                        {{ $item->quantity }} x Rp {{ number_format($item->product->final_price, 0, ',', '.') }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <strong class="text-gold" style="color: var(--gold-color);">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        @endforeach
                        
                         <div class="d-flex justify-content-between mb-3 mt-4">
                            <span>Subtotal</span>
                            <span class="fw-semibold">Rp {{ number_format($cart->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Ongkos Kirim</span>
                            <span class="fw-semibold text-muted small text-end" id="summary-shipping-cost">Pilih alamat & kurir</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total Tagihan</strong>
                            <strong class="text-gold fs-5" style="color: var(--gold-color);" id="summary-total-tagihan">
                                Rp {{ number_format($cart->total, 0, ',', '.') }}
                            </strong>
                        </div>
                        
                        @if($cart->total >= \App\Models\Setting::getValue('shipping_free_min_spend', 500000))
                            <div class="alert alert-success small mt-3">
                                <i class="fas fa-gift me-2 text-success"></i>
                                Selamat! Anda memenuhi syarat untuk <strong>GRATIS ONGKIR</strong>!
                            </div>
                        @endif
                        
                        <div class="alert alert-info small mt-4 mb-0">
                            <i class="fas fa-info-circle me-2 text-primary"></i>
                            Barang yang dibeli tidak dapat ditukar atau dikembalikan kecuali terdapat cacat produksi bawaan pabrik.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const cartTotal = {{ $cart->total }};
    const freeShippingThreshold = {{ \App\Models\Setting::getValue('shipping_free_min_spend', 500000) }};
    const isFreeShipping = cartTotal >= freeShippingThreshold;

    // Guard: block form submit if courier not yet selected
    $('form[action="{{ route('checkout.store') }}"]').on('submit', function(e) {
        if ($('#selected_courier').val() === '') {
            e.preventDefault();
            alert('Mohon pilih Provinsi dan Kota terlebih dahulu, lalu pilih kurir pengiriman sebelum melanjutkan.');
            return false;
        }
    });

    $('#province_select').on('change', function() {
        const provId = $(this).val();
        const provName = $('#province_select option:selected').text();
        $('#province_name').val(provName);

        $('#city_select').empty().append('<option value="">Pilih Kota / Kabupaten</option>').prop('disabled', true);
        $('#shipping-rates-container').hide();
        $('#shipping-placeholder').show();
        $('#btn-checkout-submit').prop('disabled', true);
        $('#selected_courier').val('');
        $('#selected_service').val('');
        $('#selected_shipping_cost').val('0');
        $('#selected_estimated_delivery').val('');
        $('#summary-shipping-cost').text('Pilih alamat & kurir');
        $('#summary-total-tagihan').text('Rp ' + new Intl.NumberFormat('id-ID').format(cartTotal));

        if (!provId) return;

        $.ajax({
            url: '{{ route('checkout.cities') }}',
            type: 'GET',
            data: { province_id: provId },
            success: function(cities) {
                $('#city_select').prop('disabled', false);
                cities.forEach(function(city) {
                    const cityName = city.type + ' ' + city.city_name;
                    $('#city_select').append('<option value="' + city.city_id + '">' + cityName + '</option>');
                });
            },
            error: function(xhr) {
                console.error('Failed to load cities:', xhr);
            }
        });
    });

    $('#city_select').on('change', function() {
        const cityId = $(this).val();
        const cityName = $('#city_select option:selected').text();
        $('#city_name').val(cityName);
        
        if (!cityId) {
            $('#shipping-rates-container').hide();
            $('#shipping-placeholder').show();
            $('#btn-checkout-submit').prop('disabled', true);
            return;
        }

        $('#shipping-placeholder').hide();
        $('#shipping-rates-container').show();
        $('#shipping-loading').show();
        $('#shipping-rates-list').empty();
        $('#btn-checkout-submit').prop('disabled', true);

        $.ajax({
            url: '{{ route('checkout.shipping-cost') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                city_id: cityId
            },
            success: function(response) {
                $('#shipping-loading').hide();
                if (response.success && response.rates.length > 0) {
                    let ratesHtml = '';
                    
                    // Sort rates so that cheapest is first
                    response.rates.sort((a, b) => a.cost - b.cost);

                    response.rates.forEach(function(rate, idx) {
                        ratesHtml += `
                            <div class="col-12 mb-2">
                                <div class="form-check p-3 border rounded shadow-sm bg-white" style="border-radius: 8px; cursor:pointer;">
                                    <input type="radio" name="shipping_rate_radio" class="form-check-input ms-0 me-2" 
                                           id="rate_${rate.courier}_${rate.service}" 
                                           value="${rate.cost}" 
                                           data-courier="${rate.courier}" 
                                           data-service="${rate.service}" 
                                           data-etd="${rate.etd}"
                                           ${idx === 0 ? 'checked' : ''}>
                                    <label class="form-check-label fw-semibold w-100" for="rate_${rate.courier}_${rate.service}" style="cursor:pointer;">
                                        ${isFreeShipping ? '<span class="badge bg-success me-2">GRATIS ONGKIR</span>' : ''}
                                        ${rate.courier} ${rate.service} — <strong class="text-gold" style="color: var(--gold-color);">${isFreeShipping ? 'GRATIS' : 'Rp ' + new Intl.NumberFormat('id-ID').format(rate.cost)}</strong>
                                        <small class="text-muted ms-1">(${rate.etd})</small>
                                    </label>
                                </div>
                            </div>
                        `;
                    });

                    $('#shipping-rates-list').html(ratesHtml);
                    
                    // Auto-select the first (cheapest) rate after DOM is updated
                    const firstRadio = $('#shipping-rates-list input[name="shipping_rate_radio"]:first');
                    if (firstRadio.length > 0) {
                        firstRadio.prop('checked', true);
                        selectRate(firstRadio);
                    }
                } else {
                    // API returned empty — show named courier fallbacks
                    renderFallbackCouriers();
                }
            },
            error: function(xhr) {
                $('#shipping-loading').hide();
                console.error('Failed to load rates (network/server error):', xhr);
                // Network error — show named courier fallbacks
                renderFallbackCouriers();
            }
        });
    });

    /**
     * Render 3 named fallback couriers when RajaOngkir API is unavailable.
     * These still allow the admin to see which courier service was selected.
     */
    function renderFallbackCouriers() {
        const fallbackRates = [
            { courier: 'JNE',     service: 'REG', cost: 20000, etd: '2-3 hari' },
            { courier: 'J&T',     service: 'EZ',  cost: 18000, etd: '2-4 hari' },
            { courier: 'SiCepat', service: 'REG', cost: 19000, etd: '2-3 hari' },
        ];

        let html = '';
        fallbackRates.forEach(function(rate, idx) {
            const displayCost = isFreeShipping ? 'GRATIS' : 'Rp ' + new Intl.NumberFormat('id-ID').format(rate.cost);
            const inputId = 'rate_' + rate.courier.replace(/[^a-zA-Z0-9]/g, '_') + '_' + rate.service;
            html += `
                <div class="col-12 mb-2">
                    <div class="form-check p-3 border rounded shadow-sm bg-white" style="border-radius:8px; cursor:pointer;">
                        <input type="radio" name="shipping_rate_radio" class="form-check-input ms-0 me-2"
                               id="${inputId}" value="${rate.cost}"
                               data-courier="${rate.courier}" data-service="${rate.service}" data-etd="${rate.etd}"
                               ${idx === 0 ? 'checked' : ''}>
                        <label class="form-check-label fw-semibold w-100" for="${inputId}" style="cursor:pointer;">
                            ${isFreeShipping ? '<span class="badge bg-success me-2">GRATIS ONGKIR</span>' : ''}
                            <strong>${rate.courier} ${rate.service}</strong> —
                            <strong class="text-gold" style="color:var(--gold-color);">${displayCost}</strong>
                            <small class="text-muted ms-1">(${rate.etd}, estimasi)</small>
                        </label>
                    </div>
                </div>
            `;
        });

        $('#shipping-rates-list').html(html);

        // Auto-select the first fallback courier
        const firstFallback = $('#shipping-rates-list input[name="shipping_rate_radio"]:first');
        if (firstFallback.length > 0) {
            firstFallback.prop('checked', true);
            selectRate(firstFallback);
        }
    }

    $(document).on('change', 'input[name="shipping_rate_radio"]', function() {
        selectRate($(this));
    });

    function checkSubmitStatus() {
        const rateSelected = $('#selected_courier').val() !== '';
        const termsChecked = $('#accept_terms').is(':checked');
        $('#btn-checkout-submit').prop('disabled', !(rateSelected && termsChecked));
    }

    $('#accept_terms').on('change', function() {
        checkSubmitStatus();
    });

    function selectRate(element) {
        const courier = element.data('courier');
        const service = element.data('service');
        const cost = parseInt(element.val());
        const etd = element.data('etd');
        const finalCost = isFreeShipping ? 0 : cost;

        $('#selected_courier').val(courier);
        $('#selected_service').val(service);
        $('#selected_shipping_cost').val(finalCost);
        $('#selected_estimated_delivery').val(etd);

        // Update Order Summary
        $('#summary-shipping-cost').html(finalCost === 0 ? '<span class="badge bg-success">Gratis</span>' : 'Rp ' + new Intl.NumberFormat('id-ID').format(finalCost));
        $('#summary-total-tagihan').text('Rp ' + new Intl.NumberFormat('id-ID').format(cartTotal + finalCost));

        // Check submit status
        checkSubmitStatus();
    }

    $('#use_profile_data').on('change', function() {
        if ($(this).is(':checked')) {
            @if($defaultAddress)
                $('#recipient_name').val('{{ $defaultAddress->recipient_name }}');
                $('#phone').val('{{ $defaultAddress->phone }}');
                $('#postal_code').val('{{ $defaultAddress->postal_code }}');
                $('#address_line').val('{{ $defaultAddress->address_line }}');
                $('#district').val('{{ $defaultAddress->district }}');
            @else
                $('#recipient_name').val('{{ $user->name }}');
                $('#phone').val('{{ $user->phone }}');
                $('#address_line').val('{{ $user->address }}');
                $('#postal_code').val('');
                $('#district').val('');
            @endif
        } else {
            $('#recipient_name').val('');
            $('#phone').val('');
            $('#postal_code').val('');
            $('#address_line').val('');
            $('#district').val('');
        }
        
        // Reset location selects
        $('#province_select').val('').trigger('change');
    });

    // Initiate Checkout Analytics Event
    $(document).ready(function() {
        @php
            $trackingItems = [];
            foreach ($cartItems as $item) {
                $trackingItems[] = [
                    'item_id' => (string)$item->product_id,
                    'item_name' => $item->product->name,
                    'item_category' => $item->product->category->name,
                    'price' => (float)$item->product->final_price,
                    'quantity' => (int)$item->quantity
                ];
            }
        @endphp
        if (typeof window.trackInitiateCheckout === 'function') {
            window.trackInitiateCheckout(
                {{ (float)$cartTotal }},
                @json($trackingItems)
            );
        }
    });
});
</script>
@endpush
