@extends('layouts.frontend')

@section('title', 'Kategori Produk - Samawa Fashion')

@section('content')
<div class="py-5">
    <div class="container">
        <!-- Section Header -->
        <div class="section-title" data-aos="fade-up">
            <h2>Kategori Produk</h2>
            <div class="divider"></div>
        </div>

        <!-- Categories Grid -->
        <div class="row">
            @forelse($categories as $category)
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="category-card" onclick="window.location.href='{{ route('products.index', ['category' => $category->id]) }}'">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                        @else
                            <img src="https://via.placeholder.com/400x250?text={{ urlencode($category->name) }}" alt="{{ $category->name }}">
                        @endif
                        <div class="category-overlay">
                            <div class="text-center">
                                <h3 class="category-name mb-2">{{ $category->name }}</h3>
                                <p class="text-white-50 px-3 small">{{ $category->description }}</p>
                                <span class="btn btn-outline-light btn-sm mt-2" style="border-radius: 0;">Lihat Produk</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-tags fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Tidak ada kategori ditemukan</h4>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
