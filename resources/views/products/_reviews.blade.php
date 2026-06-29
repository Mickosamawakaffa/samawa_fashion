@if($reviews->count() > 0)
    <div class="reviews-feed">
        @foreach($reviews as $review)
            <div class="review-item mb-4 pb-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h6 class="fw-bold mb-0 text-black">{{ $review->user->name }}</h6>
                        <div class="text-gold mt-1" style="color: var(--gold-color); font-size: 0.85rem;">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                            @endfor
                        </div>
                    </div>
                    <span class="text-muted small">{{ $review->created_at->format('d M Y') }}</span>
                </div>
                <p class="text-muted mb-2" style="font-size: 0.95rem; line-height: 1.6;">{{ $review->comment }}</p>
                
                @if($review->photo)
                    <div class="review-image-wrapper mt-2">
                        <a href="{{ Storage::url($review->photo) }}" target="_blank">
                            <img src="{{ Storage::url($review->photo) }}" alt="Foto ulasan" class="img-thumbnail" style="max-height: 100px; object-fit: cover;">
                        </a>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
    
    <!-- AJAX Pagination for Reviews -->
    <div class="d-flex justify-content-center mt-4" id="reviews-pagination-links">
        {{ $reviews->links('pagination::bootstrap-5') }}
    </div>
@else
    <div class="text-center py-5">
        <i class="far fa-comments fa-3x text-muted mb-3"></i>
        <p class="text-muted mb-0">Belum ada ulasan dengan rating ini.</p>
    </div>
@endif
