@extends('admin.layout')

@section('title', 'Kelola Kategori - Admin Samawa')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <h1 class="h3 mb-0 text-gray-800">Kelola Kategori</h1>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('admin.categories.create') }}" class="btn-gold d-inline-block text-decoration-none">
            <i class="fas fa-plus me-1"></i> Tambah Kategori
        </a>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-gold" style="color: var(--gold-color);">Daftar Kategori Produk</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="80">Gambar</th>
                        <th>Nama Kategori</th>
                        <th>Slug</th>
                        <th>Deskripsi</th>
                        <th width="100" class="text-center">Status</th>
                        <th width="150" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="img-thumbnail" style="max-width: 70px;">
                                @else
                                    <span class="text-muted small">No Image</span>
                                @endif
                            </td>
                            <td><strong>{{ $category->name }}</strong></td>
                            <td><code>{{ $category->slug }}</code></td>
                            <td><span class="text-muted small">{{ Str::limit($category->description, 80) }}</span></td>
                            <td class="text-center">
                                @if($category->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Non-Aktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-warning btn-sm" style="border-radius: 0;">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini? Semua produk dalam kategori ini juga akan terhapus.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" style="border-radius: 0;">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada data kategori</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection
