@extends('admin.layout')

@php
    $product = $product ?? null;
@endphp

@section('title', 'Detail Produk - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Detail Produk</h2>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-image me-2"></i> Gambar Produk
            </div>
            <div class="card-body">
                @php
                    $showImg = $product->primaryImage();
                    $showIsExt = $showImg && str_starts_with($showImg, 'http');
                    $showUrl = $showIsExt ? $showImg : ($showImg ? Storage::url($showImg) : asset('images/no-image.jpg'));
                @endphp
                <img src="{{ $showUrl }}" alt="{{ $product->name }}" class="img-fluid mb-3" loading="lazy" style="max-height: 350px; object-fit: cover;">
                @if($product->images->count() > 0)
                    <h6 class="mt-3">Galeri ({{ $product->images->count() }} foto)</h6>
                    <div class="row">
                        @foreach($product->images as $image)
                            @php
                                $gIsExt = str_starts_with($image->image_path, 'http');
                                $gUrl = $gIsExt ? $image->image_path : Storage::url($image->image_path);
                            @endphp
                            <div class="col-4 mb-2">
                                <img src="{{ $gUrl }}" alt="Gallery" loading="lazy" class="img-thumbnail" style="height: 100px; object-fit: cover;">
                                @if($image->is_primary)
                                    <span class="badge bg-success d-block mt-1">Utama</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-info-circle me-2"></i> Informasi Produk
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="30%">Nama</th>
                        <td>{{ $product->name }}</td>
                    </tr>
                    <tr>
                        <th>Slug</th>
                        <td>{{ $product->slug }}</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>{{ $product->category->name }}</td>
                    </tr>
                    <tr>
                        <th>Harga</th>
                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Diskon</th>
                        <td>{{ $product->discount }}%</td>
                    </tr>
                    <tr>
                        <th>Harga Final</th>
                        <td class="text-gold" style="color: var(--gold-color); font-weight: bold;">
                            Rp {{ number_format($product->final_price, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <th>Stok</th>
                        <td>{{ $product->stock }}</td>
                    </tr>
                    <tr>
                        <th>Berat</th>
                        <td>{{ $product->weight }} kg</td>
                    </tr>
                    <tr>
                        <th>Ukuran</th>
                        <td>{{ is_array($product->sizes) ? implode(', ', $product->sizes) : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Warna</th>
                        <td>{{ is_array($product->colors) ? implode(', ', $product->colors) : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($product->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Best Seller</th>
                        <td>
                            @if($product->is_best_seller)
                                <span class="badge bg-warning text-dark">Ya</span>
                            @else
                                <span class="badge bg-secondary">Tidak</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>New Arrival</th>
                        <td>
                            @if($product->is_new_arrival)
                                <span class="badge bg-info text-dark">Ya</span>
                            @else
                                <span class="badge bg-secondary">Tidak</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat</th>
                        <td>{{ $product->created_at->format('d F Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Diupdate</th>
                        <td>{{ $product->updated_at->format('d F Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <i class="fas fa-align-left me-2"></i> Deskripsi
            </div>
            <div class="card-body">
                <p>{{ $product->description }}</p>
            </div>
        </div>
        
        <div class="mt-3">
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i> Edit
            </a>
            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus produk ini?')">
                    <i class="fas fa-trash me-2"></i> Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
