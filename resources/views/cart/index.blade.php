@extends('layouts.frontend')

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
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Subtotal</span>
                                    <span id="summary-subtotal" class="fw-semibold">Rp {{ number_format($cart->total, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3 text-muted small">
                                    <span>Ongkos Kirim</span>
                                    <span id="shipping-cost-text">{{ $cart->total > 500000 ? 'Gratis (Belanja > Rp 500.000)' : 'Rp 15.000 (Flat)' }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-4">
                                    <strong>Total</strong>
                                    <strong class="text-gold fs-5" id="summary-total" style="color: var(--gold-color);">
                                        Rp {{ number_format($cart->total + ($cart->total > 500000 ? 0 : 15000), 0, ',', '.') }}
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
                
                // Calculate grand total (free shipping if subtotal > 500.000)
                let subtotalNum = parseInt(response.total.replace(/[^0-9]/g, ''));
                let shippingCost = subtotalNum > 500000 ? 0 : 15000;
                let shippingStr = shippingCost === 0 ? 'Gratis (Belanja > Rp 500.000)' : 'Rp 15.000 (Flat)';
                $('#shipping-cost-text').text(shippingStr);
                let grandTotalStr = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotalNum + shippingCost);
                $('#summary-total').text(grandTotalStr);

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
                        let shippingCost = subtotalNum > 500000 ? 0 : 15000;
                        let shippingStr = shippingCost === 0 ? 'Gratis (Belanja > Rp 500.000)' : 'Rp 15.000 (Flat)';
                        $('#shipping-cost-text').text(shippingStr);
                        let grandTotalStr = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotalNum + shippingCost);
                        $('#summary-total').text(grandTotalStr);

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
</script>
@endpush
