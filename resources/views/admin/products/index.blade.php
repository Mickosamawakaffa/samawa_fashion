@extends('admin.layout')

@section('title', 'Kelola Produk - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Kelola Produk</h2>
    <a href="{{ route('admin.products.create') }}" class="btn-gold">
        <i class="fas fa-plus me-2"></i> Tambah Produk
    </a>
</div>

{{-- Filter Buttons --}}
<div class="d-flex gap-2 mb-3">
    <a href="{{ route('admin.products.index') }}" class="btn btn-sm {{ !request('dummy') ? 'btn-dark' : 'btn-outline-dark' }}">
        <i class="fas fa-list me-1"></i> Semua Produk
    </a>
    @if($dummyCount > 0)
        <a href="{{ route('admin.products.index', ['dummy' => 1]) }}" class="btn btn-sm {{ request('dummy') ? 'btn-warning text-dark' : 'btn-outline-warning' }}">
            <i class="fas fa-image me-1"></i> Dummy Saja ({{ $dummyCount }})
        </a>
    @endif
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-box me-2"></i> Daftar Produk
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="productsTable">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        @php
                            $imgSrc = $product->primaryImage();
                            $isExternal = $imgSrc && str_starts_with($imgSrc, 'http');
                            $imgUrl = $isExternal ? $imgSrc : ($imgSrc ? Storage::url($imgSrc) : asset('images/no-image.jpg'));
                        @endphp
                        <tr>
                            <td>
                                <img src="{{ $imgUrl }}" 
                                     alt="{{ $product->name }}" 
                                     loading="lazy"
                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                            </td>
                            <td>
                                <strong>{{ $product->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $product->slug }}</small>
                            </td>
                            <td>{{ $product->category->name }}</td>
                            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                @if($product->is_dummy)
                                    <span class="badge bg-warning text-dark me-1"><i class="fas fa-image me-1"></i>DUMMY</span>
                                @endif
                                @if($product->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Nonaktif</span>
                                @endif
                                @if($product->is_best_seller)
                                    <span class="badge bg-warning text-dark ms-1">Best Seller</span>
                                @endif
                                @if($product->is_new_arrival)
                                    <span class="badge bg-info text-dark ms-1">New</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center mt-3">
            {{ $products->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

