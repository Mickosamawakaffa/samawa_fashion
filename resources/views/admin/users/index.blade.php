@extends('admin.layout')

@section('title', 'Kelola User - Admin Samawa')

@section('content')
<h1 class="h3 mb-4 text-gray-800">Kelola User</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-gold" style="color: var(--gold-color);">Daftar User Terdaftar</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Nomor HP</th>
                        <th>Alamat</th>
                        <th width="120" class="text-center">Role</th>
                        <th width="200" class="text-center">Ubah Role</th>
                        <th width="100" class="text-center">Hapus</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td><span class="small text-muted">{{ Str::limit($user->address ?? '-', 60) }}</span></td>
                            <td class="text-center">
                                @if($user->isAdmin())
                                    <span class="badge bg-danger px-3 py-1">Admin</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-1">User</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.customers.update', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <div class="input-group input-group-sm">
                                        <select name="role" class="form-select form-select-sm" style="border-radius: 0;">
                                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                        <button type="submit" class="btn btn-gold btn-sm" style="border-radius: 0;">Ubah</button>
                                    </div>
                                </form>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.customers.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" style="border-radius: 0;" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
