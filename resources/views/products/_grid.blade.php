@if($products->count() > 0)
    <div class="row">
        @foreach($products as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="product-card" style="position: relative; overflow: visible;">
                    <div class="product-image" style="position: relative; overflow: hidden; height: 300px; border-radius: 10px 10px 0 0;">
                        <img src="{{ $product->image ? Storage::url($product->image) : asset('images/no-image.jpg') }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                        
                        <!-- Badges Stack -->
                        <div class="product-badge-container">
                            @if($product->stock == 0)
                                <span class="product-badge bg-secondary text-white">Stok Habis</span>
                            @endif
                            @if($product->discount > 0)
                                <span class="product-badge bg-danger text-white">Diskon {{ $product->discount }}%</span>
                            @endif
                            @if($product->is_featured)
                                <span class="product-badge">Featured</span>
                            @elseif($product->is_best_seller)
                                <span class="product-badge">Best Seller</span>
                            @endif
                        </div>
                        
                        <!-- Action overlay on hover -->
                        <div class="product-actions">
                            <button onclick="toggleWishlist({{ $product->id }}, this)" title="Add to Wishlist" class="wishlist-btn" {{ $product->stock == 0 ? 'disabled' : '' }}>
                                <i class="{{ auth()->check() && auth()->user()->wishlist->contains('product_id', $product->id) ? 'fas' : 'far' }} fa-heart text-danger"></i>
                            </button>
                            <button onclick="addToCart({{ $product->id }})" title="Add to Cart" {{ $product->stock == 0 ? 'disabled' : '' }}>
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                            <a href="{{ route('products.show', $product->slug) }}" class="btn btn-gold" style="width: auto; border-radius: 20px; padding: 8px 20px; font-size: 0.85rem; font-weight: 600;">
                                Detail
                            </a>
                        </div>
                    </div>
                    <div class="product-info" style="padding: 20px; background: white; border-radius: 0 0 10px 10px;">
                        <p class="product-category" style="color: var(--gray-color); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;">{{ $product->category->name }}</p>
                        <a href="{{ route('products.show', $product->slug) }}">
                            <h5 class="product-name text-truncate" style="font-size: 1.1rem; font-weight: 600; color: var(--primary-color); margin-bottom: 10px; transition: all 0.3s ease;">{{ $product->name }}</h5>
                        </a>
                        <div class="product-price" style="font-size: 1.2rem; font-weight: 700; color: var(--gold-color);">
                            Rp {{ number_format($product->final_price, 0, ',', '.') }}
                            @if($product->discount > 0)
                                <span class="product-old-price" style="text-decoration: line-through; color: var(--gray-color); font-size: 0.9rem; margin-left: 10px;">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Forced Bootstrap 5 Pagination -->
    <div class="d-flex justify-content-center mt-4 ajax-pagination">
        {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
@else
    <div class="text-center py-5">
        <i class="fas fa-search fa-4x text-muted mb-3 text-gold" style="color: var(--gold-color);"></i>
        <h4 class="text-muted fw-bold">🔍 Produk tidak ditemukan</h4>
        <p class="text-muted small mb-4">Coba ubah filter atau kata kunci pencarianmu</p>
        <button type="button" class="btn btn-gold px-4 py-2" onclick="document.getElementById('reset-filters').click()" style="font-weight: 600;">Riset Filter</button>
    </div>
@endif
