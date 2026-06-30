@extends('admin.layout')

@section('title', 'Tambah Produk - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Tambah Produk</h2>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-plus me-2"></i> Form Tambah Produk
    </div>
    <div class="card-body">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="createProductForm">
            @csrf
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                        @error('name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                        <textarea name="description" class="form-control" rows="5" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="price" class="form-control" required min="0" step="0.01" value="{{ old('price') }}">
                                @error('price')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Diskon (%)</label>
                                <input type="number" name="discount" class="form-control" min="0" max="100" step="0.01" value="{{ old('discount') ?? 0 }}">
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
                                <input type="number" name="stock" class="form-control" required min="0" value="{{ old('stock') }}">
                                @error('stock')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Berat (kg) <span class="text-danger">*</span></label>
                                <input type="number" name="weight" class="form-control" required min="0" step="0.01" value="{{ old('weight') }}">
                                @error('weight')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ukuran (pisahkan dengan koma)</label>
                        <input type="text" name="sizes[]" class="form-control" placeholder="S, M, L, XL" value="{{ old('sizes') ? implode(',', old('sizes')) : '' }}">
                        @error('sizes')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Warna (pisahkan dengan koma)</label>
                        <input type="text" name="colors[]" class="form-control" placeholder="Hitam, Putih, Merah" value="{{ old('colors') ? implode(',', old('colors')) : '' }}">
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

                    <div class="mb-3">
                        <label class="form-label fw-bold">Foto Produk <span class="text-danger">*</span> <small class="text-muted fw-normal">(min 3, maks 6)</small></label>
                        <input type="file" name="images[]" id="createImagesInput" class="form-control" multiple accept="image/jpeg,image/png,image/jpg" required>
                        <small class="text-muted">JPG/PNG, maks 3MB per foto</small>
                        @error('images')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror

                        {{-- Preview + Primary Selection --}}
                        <div class="row g-2 mt-2" id="create-preview-container"></div>
                        <input type="hidden" name="primary_image" id="primaryImageInput" value="0">
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="isActive" checked>
                            <label class="form-check-label" for="isActive">
                                Aktif
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_best_seller" class="form-check-input" id="isBestSeller">
                            <label class="form-check-label" for="isBestSeller">
                                Best Seller
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_new_arrival" class="form-check-input" id="isNewArrival">
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
                    <i class="fas fa-save me-2"></i> Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview images with primary selection (radio buttons)
    document.getElementById('createImagesInput').addEventListener('change', function(e) {
        const container = document.getElementById('create-preview-container');
        container.innerHTML = '';
        
        const files = e.target.files;
        if (files.length < 3) {
            container.innerHTML = '<div class="col-12"><div class="text-danger small"><i class="fas fa-exclamation-circle me-1"></i> Minimal 3 foto diperlukan</div></div>';
        }
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
                    <div class="form-check mt-1">
                        <input class="form-check-input" type="radio" name="primary_image_radio" value="${index}" id="primary-new-${index}" ${index === 0 ? 'checked' : ''} onchange="document.getElementById('primaryImageInput').value = this.value">
                        <label class="form-check-label small" for="primary-new-${index}">Foto Utama</label>
                    </div>
                `;
                container.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    });
</script>
@endpush
