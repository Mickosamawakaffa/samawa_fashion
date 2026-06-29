@extends('admin.layout')

@section('title', 'Tambah Kategori - Admin Samawa')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tambah Kategori</h1>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm" style="border-radius: 0;">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-gold" style="color: var(--gold-color);">Form Kategori Baru</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Masukkan nama kategori">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Deskripsi singkat kategori">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Gambar Kategori</label>
                <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                <small class="text-muted">Format: JPG, PNG, JPEG. Maksimal 2MB.</small>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                    <label class="form-check-label fw-bold" for="is_active">Aktifkan Kategori</label>
                </div>
            </div>

            <button type="submit" class="btn-gold px-4 py-2">
                <i class="fas fa-save me-1"></i> Simpan Kategori
            </button>
        </form>
    </div>
</div>
@endsection
