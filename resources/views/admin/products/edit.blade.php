@extends('admin.layout')

@php
    $product    = $product    ?? null;
    $categories = $categories ?? collect();
@endphp

@section('title', 'Edit Produk - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Edit Produk</h2>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i> Kembali
    </a>
</div>

@if($product->is_dummy)
<div class="alert alert-warning d-flex align-items-center mb-4">
    <i class="fas fa-exclamation-triangle me-3 fa-lg"></i>
    <div>
        <strong>Produk ini masih menggunakan data DUMMY.</strong><br>
        Upload foto asli dan perbarui detail produk untuk mengganti data sementara.
    </div>
    <form action="{{ route('admin.products.clearDummy', $product) }}" method="POST" class="ms-auto">
        @csrf
        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus semua foto dummy? Anda perlu upload foto baru setelah ini.')">
            <i class="fas fa-trash me-1"></i> Hapus Semua Foto Dummy
        </button>
    </form>
</div>
@endif

<div class="card">
    <div class="card-header">
        <i class="fas fa-edit me-2"></i> Form Edit Produk
    </div>
    <div class="card-body">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required value="{{ old('name', $product->name) }}">
                        @error('name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="5" required>{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="price" class="form-control" required min="0" step="0.01" value="{{ old('price', $product->price) }}">
                                @error('price')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Diskon (%)</label>
                                <input type="number" name="discount" class="form-control" min="0" max="100" step="0.01" value="{{ old('discount', $product->discount) }}">
                                @error('discount')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Stok <span class="text-danger">*</span></label>
                                <input type="number" name="stock" class="form-control" required min="0" value="{{ old('stock', $product->stock) }}">
                                @error('stock')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Berat (kg) <span class="text-danger">*</span></label>
                                <input type="number" name="weight" class="form-control" required min="0" step="0.01" value="{{ old('weight', $product->weight) }}">
                                @error('weight')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ukuran (pisahkan dengan koma)</label>
                        <input type="text" name="sizes[]" class="form-control" placeholder="S, M, L, XL" value="{{ old('sizes') ? implode(',', old('sizes')) : (is_array($product->sizes) ? implode(',', $product->sizes) : '') }}">
                        @error('sizes')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Warna (pisahkan dengan koma)</label>
                        <input type="text" name="colors[]" class="form-control" placeholder="Hitam, Putih, Merah" value="{{ old('colors') ? implode(',', old('colors')) : (is_array($product->colors) ? implode(',', $product->colors) : '') }}">
                        @error('colors')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    {{-- Photo Tips --}}
                    <div class="alert alert-info small mb-3">
                        <i class="fas fa-lightbulb me-1"></i> <strong>Tips:</strong> Upload minimal 3 foto (tampak depan, detail bahan, dipakai).<br>
                        Background putih/polos dengan cahaya terang akan terlihat lebih profesional.
                    </div>

                    {{-- Existing Gallery Images --}}
                    @if($product->images->count() > 0)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Foto Saat Ini ({{ $product->images->count() }}/6)</label>
                            <div class="row g-2" id="existing-gallery">
                                @foreach($product->images as $img)
                                    <div class="col-6 position-relative" id="img-card-{{ $img->id }}">
                                        @php
                                            $isExternal = str_starts_with($img->image_path, 'http');
                                            $src = $isExternal ? $img->image_path : Storage::url($img->image_path);
                                        @endphp
                                        <div class="border rounded overflow-hidden" style="height: 120px;">
                                            <img src="{{ $src }}" alt="Gallery" loading="lazy" class="w-100 h-100" style="object-fit: cover;">
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-1">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="primary_image_id" value="{{ $img->id }}" id="primary-{{ $img->id }}" {{ $img->is_primary ? 'checked' : '' }}>
                                                <label class="form-check-label small" for="primary-{{ $img->id }}">Utama</label>
                                            </div>
                                            <label class="form-check small text-danger" style="cursor: pointer;">
                                                <input type="checkbox" name="delete_images[]" value="{{ $img->id }}" class="form-check-input form-check-input-sm">
                                                <span class="small">Hapus</span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Upload New Images --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tambah Foto Baru</label>
                        <input type="file" name="images[]" id="newImagesInput" class="form-control" multiple accept="image/jpeg,image/png,image/jpg">
                        <small class="text-muted">JPG/PNG, maks 3MB per foto, maks 6 foto total</small>
                        @error('images')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        {{-- Preview Container --}}
                        <div class="row g-2 mt-2" id="new-preview-container"></div>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="isActive" {{ $product->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">
                                Aktif
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_best_seller" class="form-check-input" id="isBestSeller" {{ $product->is_best_seller ? 'checked' : '' }}>
                            <label class="form-check-label" for="isBestSeller">
                                Best Seller
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_new_arrival" class="form-check-input" id="isNewArrival" {{ $product->is_new_arrival ? 'checked' : '' }}>
                            <label class="form-check-label" for="isNewArrival">
                                New Arrival
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-end">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn-gold">
                    <i class="fas fa-save me-2"></i> Update Produk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview new images before upload
    document.getElementById('newImagesInput').addEventListener('change', function(e) {
        const container = document.getElementById('new-preview-container');
        container.innerHTML = '';
        
        const files = e.target.files;
        if (files.length > 6) {
            alert('Maksimal 6 foto!');
            this.value = '';
            return;
        }

        Array.from(files).forEach((file, index) => {
            if (!file.type.match('image.*')) return;
            
            const reader = new FileReader();
            reader.onload = function(ev) {
                const col = document.createElement('div');
                col.className = 'col-6';
                col.innerHTML = `
                    <div class="border rounded overflow-hidden" style="height: 100px;">
                        <img src="${ev.target.result}" class="w-100 h-100" style="object-fit: cover;" alt="Preview">
                    </div>
                    <small class="text-muted">${file.name.substring(0, 20)}...</small>
                `;
                container.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endpush
