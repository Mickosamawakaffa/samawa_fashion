@extends('layouts.frontend')

@php
    $cart = $cart ?? (object)['items' => collect(), 'total' => 0];
@endphp

@section('title', 'Keranjang - Samawa Fashion')

@section('content')
<div class="py-5">
    <div class="container">
        <div class="section-title">
            <h2>Keranjang Belanja</h2>
            <div class="divider"></div>
        </div>
        
        <div id="cart-content-container">
            @if($cart->items->count() > 0)
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Free Shipping Promo Progress Bar -->
                        @php
                            $freeShippingMin = (int)\App\Models\Setting::getValue('shipping_free_min_spend', 500000);
                            $remaining = $freeShippingMin - $cart->total;
                            $percent = min(100, max(0, ($cart->total / $freeShippingMin) * 100));
                        @endphp
                        <div class="card mb-4 border-0 shadow-sm" style="border-radius: 10px; background-color: #ffffff;">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-semibold mb-0" id="free-shipping-message">
                                        @if($remaining > 0)
                                            <i class="fas fa-truck text-gold me-2" style="color: var(--gold-color);"></i>
                                            Belanja <strong class="text-gold" style="color: var(--gold-color);">Rp <span id="free-shipping-remaining">{{ number_format($remaining, 0, ',', '.') }}</span></strong> lagi untuk <strong>Gratis Ongkir!</strong>
                                        @else
                                            <i class="fas fa-gift text-success me-2" style="color: var(--gold-color);"></i>
                                            Selamat! Anda mendapatkan <strong>Gratis Ongkir!</strong> 🎉
                                        @endif
                                    </h6>
                                    <span class="small text-muted fw-bold"><span id="free-shipping-percent">{{ round($percent) }}</span>%</span>
                                </div>
                                <div class="progress" style="height: 10px; background-color: #e9ecef; border-radius: 5px;">
                                    <div id="free-shipping-progress-bar" class="progress-bar" role="progressbar" 
                                         style="width: {{ $percent }}%; background-color: var(--gold-color);" 
                                         aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4 border-0 shadow-sm" style="border-radius: 10px;">
                            <div class="card-header bg-black text-gold" style="color: var(--gold-color); font-weight: 600; border-radius: 10px 10px 0 0;">
                                <i class="fas fa-shopping-cart me-2"></i> Produk di Keranjang
                            </div>
                            <div class="card-body p-4">
                                @foreach($cart->items as $item)
                                    <div class="row mb-4 pb-4 border-bottom align-items-center" id="cart-row-{{ $item->id }}">
                                        <div class="col-md-3 mb-3 mb-md-0">
                                            <img src="{{ $item->product->image ? Storage::url($item->product->image) : asset('images/no-image.jpg') }}" alt="{{ $item->product->name }}" class="img-fluid rounded shadow-sm" style="max-height: 100px; object-fit: cover;">
                                        </div>
                                        <div class="col-md-9">
                                            <div class="d-flex justify-content-between mb-2">
                                                <div>
                                                    <h5 class="fw-semibold">{{ $item->product->name }}</h5>
                                                    <p class="text-muted small mb-1">Kategori: {{ $item->product->category->name }}</p>
                                                    <p class="text-muted small">Harga Satuan: Rp {{ number_format($item->product->final_price, 0, ',', '.') }}</p>
                                                </div>
                                                <div class="text-end">
                                                    <h5 class="text-gold fw-bold item-subtotal" id="subtotal-{{ $item->id }}" style="color: var(--gold-color);">
                                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <div class="input-group" style="width: 130px; border-radius: 0;">
                                                    <button class="btn btn-outline-dark" style="border-radius: 0;" onclick="decrementQty({{ $item->id }})">-</button>
                                                    <input type="number" id="qty-input-{{ $item->id }}" class="form-control text-center bg-white" value="{{ $item->quantity }}" readonly style="border-left: 0; border-right: 0;">
                                                    <button class="btn btn-outline-dark" style="border-radius: 0;" onclick="incrementQty({{ $item->id }})">+</button>
                                                </div>
                                                <button class="btn btn-danger btn-sm px-3" style="border-radius: 0;" onclick="removeItem({{ $item->id }})">
                                                    <i class="fas fa-trash me-1"></i> Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm" style="border-radius: 10px;">
                            <div class="card-header bg-black text-gold" style="color: var(--gold-color); font-weight: 600; border-radius: 10px 10px 0 0;">
                                <i class="fas fa-calculator me-2"></i> Ringkasan Pesanan
                            </div>
                            <div class="card-body p-4">
                                @php
                                    $appliedVoucher = session('applied_voucher');
                                    $discount = $appliedVoucher ? $appliedVoucher['discount'] : 0;
                                    $subtotal = $cart->total;
                                    $shipping = $subtotal >= $freeShippingMin ? 0 : 15000;
                                    $totalPrice = max(0, $subtotal - $discount) + $shipping;
                                @endphp
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Subtotal</span>
                                    <span id="summary-subtotal" class="fw-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3 text-danger {{ $discount > 0 ? '' : 'd-none' }}" id="discount-row">
                                    <span>Diskon Voucher (<strong id="applied-code-label">{{ $appliedVoucher['code'] ?? '' }}</strong>)</span>
                                    <span id="summary-discount">-Rp {{ number_format($discount, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3 text-muted small">
                                    <span>Ongkos Kirim</span>
                                    <span id="shipping-cost-text">{{ $shipping === 0 ? 'Gratis' : 'Rp 15.000 (Flat)' }}</span>
                                </div>

                                <!-- Voucher Input -->
                                <div class="mb-3 border-top pt-3">
                                    <label for="voucher_code" class="form-label small fw-semibold">Gunakan Voucher Diskon</label>
                                    <div class="input-group">
                                        <input type="text" id="voucher_code" class="form-control" placeholder="Kode Voucher" value="{{ $appliedVoucher['code'] ?? '' }}" style="border-radius: 0;">
                                        <button class="btn btn-dark" type="button" id="btn-apply-voucher" style="border-radius: 0;">Pakai</button>
                                    </div>
                                    <div id="voucher-message" class="small mt-1" style="display: none;"></div>
                                </div>

                                <hr>
                                <div class="d-flex justify-content-between mb-4">
                                    <strong>Total</strong>
                                    <strong class="text-gold fs-5" id="summary-total" style="color: var(--gold-color);">
                                        Rp {{ number_format($totalPrice, 0, ',', '.') }}
                                    </strong>
                                </div>
                                <a href="{{ route('checkout.index') }}" class="btn-gold w-100 text-center d-block py-3 text-decoration-none">
                                    <i class="fas fa-credit-card me-2"></i> Checkout Sekarang
                                </a>
                                <a href="{{ route('products.index') }}" class="btn btn-outline-dark w-100 text-center d-block mt-2 py-2" style="border-radius: 0;">
                                    <i class="fas fa-arrow-left me-2"></i> Lanjut Belanja
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-5x text-muted mb-4 text-gold" style="color: var(--gold-color);"></i>
                    <h3 class="text-muted">Keranjang Anda Kosong</h3>
                    <p class="text-muted mb-4">Belum ada produk di keranjang belanja Anda</p>
                    <a href="{{ route('products.index') }}" class="btn-gold py-3 px-5 text-decoration-none">
                        <i class="fas fa-shopping-bag me-2"></i> Mulai Belanja
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const freeShippingThreshold = {{ \App\Models\Setting::getValue('shipping_free_min_spend', 500000) }};

    function incrementQty(itemId) {
        let input = $('#qty-input-' + itemId);
        let val = parseInt(input.value || input.val());
        updateQuantity(itemId, val + 1);
    }

    function decrementQty(itemId) {
        let input = $('#qty-input-' + itemId);
        let val = parseInt(input.value || input.val());
        if (val > 1) {
            updateQuantity(itemId, val - 1);
        }
    }

    function updateProgressBar(subtotalNum) {
        let remaining = freeShippingThreshold - subtotalNum;
        let percent = Math.min(100, Math.max(0, (subtotalNum / freeShippingThreshold) * 100));
        
        $('#free-shipping-percent').text(Math.round(percent));
        $('#free-shipping-progress-bar').css('width', percent + '%').attr('aria-valuenow', percent);
        
        if (remaining > 0) {
            $('#free-shipping-message').html(
                '<i class="fas fa-truck text-gold me-2" style="color: var(--gold-color);"></i> ' +
                'Belanja <strong class="text-gold" style="color: var(--gold-color);">Rp ' + new Intl.NumberFormat('id-ID').format(remaining) + '</strong> lagi untuk <strong>Gratis Ongkir!</strong>'
            );
        } else {
            $('#free-shipping-message').html(
                '<i class="fas fa-gift text-success me-2" style="color: var(--gold-color);"></i> ' +
                'Selamat! Anda mendapatkan <strong>Gratis Ongkir!</strong> 🎉'
            );
        }
    }

    function updateQuantity(itemId, quantity) {
        $.ajax({
            url: '{{ route('cart.update') }}',
            method: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                cart_item_id: itemId,
                quantity: quantity
            },
            success: function(response) {
                // Update input quantity
                $('#qty-input-' + itemId).val(quantity);
                
                // Update product subtotal
                $('#subtotal-' + itemId).text(response.subtotal);
                
                // Update summary subtotals & totals
                $('#summary-subtotal').text(response.total);
                
                // Calculate grand total (free shipping if subtotal > threshold)
                let subtotalNum = parseInt(response.total.replace(/[^0-9]/g, ''));
                let shippingCost = subtotalNum >= freeShippingThreshold ? 0 : 15000;
                let shippingStr = shippingCost === 0 ? 'Gratis' : 'Rp 15.000 (Flat)';
                $('#shipping-cost-text').text(shippingStr);
                let grandTotalStr = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotalNum + shippingCost);
                $('#summary-total').text(grandTotalStr);

                // Update Progress Bar
                updateProgressBar(subtotalNum);

                // Update navbar badge count
                if (response.cart_count > 0) {
                    $('#cart-badge-count').text(response.cart_count).removeClass('d-none');
                } else {
                    $('#cart-badge-count').addClass('d-none');
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan'
                });
            }
        });
    }
    
    function removeItem(itemId) {
        Swal.fire({
            icon: 'warning',
            title: 'Hapus Produk',
            text: 'Yakin ingin menghapus produk ini dari keranjang?',
            showCancelButton: true,
            confirmButtonColor: '#C9A84C',
            cancelButtonColor: '#0A0A0A',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('cart.remove') }}',
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                        cart_item_id: itemId
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // Remove row from DOM
                        $('#cart-row-' + itemId).fadeOut(300, function() {
                            $(this).remove();
                            
                            // Check if cart is empty after removal
                            if ($('[id^="cart-row-"]').length === 0) {
                                $('#cart-content-container').html(`
                                    <div class="text-center py-5">
                                        <i class="fas fa-shopping-cart fa-5x text-muted mb-4 text-gold" style="color: var(--gold-color);"></i>
                                        <h3 class="text-muted">Keranjang Anda Kosong</h3>
                                        <p class="text-muted mb-4">Belum ada produk di keranjang belanja Anda</p>
                                        <a href="{{ route('products.index') }}" class="btn-gold py-3 px-5 text-decoration-none">
                                            <i class="fas fa-shopping-bag me-2"></i> Mulai Belanja
                                        </a>
                                    </div>
                                `);
                            }
                        });

                        // Update summary totals
                        $('#summary-subtotal').text(response.total);
                        let subtotalNum = parseInt(response.total.replace(/[^0-9]/g, ''));
                        let shippingCost = subtotalNum >= freeShippingThreshold ? 0 : 15000;
                        let shippingStr = shippingCost === 0 ? 'Gratis' : 'Rp 15.000 (Flat)';
                        $('#shipping-cost-text').text(shippingStr);
                        let grandTotalStr = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotalNum + shippingCost);
                        $('#summary-total').text(grandTotalStr);

                        // Update Progress Bar
                        updateProgressBar(subtotalNum);

                        // Update navbar badge count
                        if (response.cart_count > 0) {
                            $('#cart-badge-count').text(response.cart_count).removeClass('d-none');
                        } else {
                            $('#cart-badge-count').addClass('d-none');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Terjadi kesalahan'
                        });
                    }
                });
            }
        });
    }

    // Voucher Apply AJAX logic
    $('#btn-apply-voucher').click(function(e) {
        e.preventDefault();
        let code = $('#voucher_code').val().trim();
        
        if (!code) {
            code = 'CLEAR_VOUCHER';
        }

        $.ajax({
            url: '{{ route('cart.apply_voucher') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                code: code
            },
            success: function(response) {
                if (code === 'CLEAR_VOUCHER') {
                    $('#voucher-message').removeClass('text-danger').addClass('text-success').text('Voucher berhasil dihapus.').show();
                    $('#discount-row').addClass('d-none');
                    $('#voucher_code').val('');
                } else {
                    $('#voucher-message').removeClass('text-danger').addClass('text-success').text(response.message).show();
                    $('#discount-row').removeClass('d-none');
                    $('#applied-code-label').text(response.voucher_code);
                    $('#summary-discount').text('-' + response.discount_formatted);
                }

                // Recalculate totals
                let subtotalNum = parseInt($('#summary-subtotal').text().replace(/[^0-9]/g, ''));
                let discountNum = response.discount_amount;
                let shippingCost = subtotalNum >= freeShippingThreshold ? 0 : 15000;
                let grandTotal = Math.max(0, subtotalNum - discountNum) + shippingCost;
                
                $('#summary-total').text('Rp ' + new Intl.NumberFormat('id-ID').format(grandTotal));
            },
            error: function(xhr) {
                $('#voucher-message').removeClass('text-success').addClass('text-danger').text(xhr.responseJSON?.message || 'Gagal menggunakan voucher.').show();
                $('#discount-row').addClass('d-none');
            }
        });
    });
</script>
@endpush
