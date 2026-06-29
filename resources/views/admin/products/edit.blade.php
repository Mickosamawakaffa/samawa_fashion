@extends('admin.layout')

@section('title', 'Edit Produk - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Edit Produk</h2>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i> Kembali
    </a>
</div>

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
                    <div class="mb-3">
                        <label class="form-label">Gambar Utama</label>
                        <input type="file" name="main_image" class="form-control" accept="image/*">
                        @error('main_image')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        @if($product->main_image)
                            <img src="{{ asset('storage/' . $product->main_image) }}" alt="Current Image" class="img-thumbnail mt-2" style="max-width: 200px;">
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Galeri Gambar</label>
                        <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                        @error('images')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        @if($product->images->count() > 0)
                            <div class="row mt-2">
                                @foreach($product->images as $image)
                                    <div class="col-4 mb-2">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Gallery" class="img-thumbnail" style="max-width: 100%;">
                                    </div>
                                @endforeach
                            </div>
                        @endif
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
