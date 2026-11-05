@extends('layouts.admin')

@section('title', 'List Pengguna Terdaftar - BookStore')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">List User yang Sudah Terdaftar</h3>
        <p class="text-muted small mb-0">Daftar seluruh akun pengguna dan hak akses di aplikasi BookStore</p>
    </div>
</div>

<!-- Filter & Pencarian Pengguna -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('admin.users.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-7">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="q" class="form-control" placeholder="Cari nama, email, nomor HP pengguna..." value="{{ request('q') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select">
                    <option value="">Semua Peran (Role)</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Pelanggan / User</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
                @if(request('q') || request('role'))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary" title="Reset filter"><i class="bi bi-arrow-counterclockwise"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;" class="text-center">No</th>
                    <th>Nama Pengguna</th>
                    <th>Email</th>
                    <th>Peran (Role)</th>
                    <th>Kontak / HP</th>
                    <th>Alamat</th>
                    <th class="text-center" style="width: 120px;">Total Pesanan</th>
                    <th>Terdaftar Sejak</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $idx => $u)
                    <tr>
                        <td class="text-center">{{ $users->firstItem() + $idx }}</td>
                        <td>
                            <strong class="text-dark">{{ $u->name }}</strong>
                        </td>
                        <td>
                            <span class="text-muted">{{ $u->email }}</span>
                        </td>
                        <td>
                            @if($u->role === 'admin')
                                <span class="badge bg-danger">Administrator</span>
                            @else
                                <span class="badge bg-primary">User / Pembeli</span>
                            @endif
                        </td>
                        <td>{{ $u->phone ?? '-' }}</td>
                        <td class="small text-muted" style="max-width: 250px;">
                            {{ Str::limit($u->address ?? '-', 60) }}
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border">{{ $u->orders_count }} pesanan</span>
                        </td>
                        <td class="small text-muted">{{ $u->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Tidak ada user yang ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="card-footer bg-white d-flex justify-content-center py-3">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
