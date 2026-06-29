@extends('admin.layout')

@section('title', 'Kelola Testimoni - Admin Samawa')

@section('content')
<h1 class="h3 mb-4 text-gray-800">Kelola Testimoni</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-gold" style="color: var(--gold-color);">Daftar Ulasan & Testimoni</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="150">Nama</th>
                        <th width="100">Role</th>
                        <th width="120" class="text-center">Rating</th>
                        <th>Pesan / Ulasan</th>
                        <th width="130" class="text-center">Status</th>
                        <th width="200" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($testimonials as $testimonial)
                        <tr>
                            <td><strong>{{ $testimonial->name }}</strong></td>
                            <td>{{ $testimonial->role ?? 'Customer' }}</td>
                            <td class="text-center">
                                <div class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $testimonial->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                            </td>
                            <td><p class="mb-0 text-muted small">"{{ $testimonial->message }}"</p></td>
                            <td class="text-center">
                                @if($testimonial->is_approved)
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-warning text-black">Menunggu</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(!$testimonial->is_approved)
                                    <form action="{{ route('admin.testimonials.approve', $testimonial->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" style="border-radius: 0;">
                                            <i class="fas fa-check"></i> Setujui
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?')">
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
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada ulasan/testimoni dari user</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $testimonials->links() }}
        </div>
    </div>
</div>
@endsection
