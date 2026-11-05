@extends('layouts.admin')

@section('title', 'Kelola Data Buku - BookStore')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Kelola Data Buku</h3>
        <p class="text-muted small mb-0">Daftar inventaris buku, penambahan judul baru, update data, dan kontrol stok</p>
    </div>
    <a href="{{ route('admin.books.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Buku Baru
    </a>
</div>

<!-- Filter & Pencarian Buku -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('admin.books.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-7">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="q" class="form-control" placeholder="Cari judul buku, pengarang, penerbit..." value="{{ request('q') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="category_id" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
                @if(request('q') || request('category_id'))
                    <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary" title="Reset filter"><i class="bi bi-arrow-counterclockwise"></i></a>
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
                    <th style="width: 70px;">Cover</th>
                    <th>Judul Buku</th>
                    <th>Kategori</th>
                    <th>Penulis</th>
                    <th>Harga</th>
                    <th class="text-center" style="width: 90px;">Stok</th>
                    <th class="text-center" style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $idx => $b)
                    <tr>
                        <td class="text-center">{{ $books->firstItem() + $idx }}</td>
                        <td>
                            @if($b->cover_image)
                                <img src="{{ asset('storage/' . $b->cover_image) }}" alt="Cover" class="rounded" style="width: 45px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-secondary-subtle text-muted rounded d-flex align-items-center justify-content-center" style="width: 45px; height: 60px; font-size: 1.2rem;">
                                    <i class="bi bi-book"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong class="text-dark d-block">{{ $b->title }}</strong>
                            <small class="text-muted">{{ $b->publisher }} ({{ $b->year }})</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-primary border">{{ $b->category->name ?? '-' }}</span>
                        </td>
                        <td>{{ $b->author }}</td>
                        <td class="fw-bold">Rp {{ number_format($b->price, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if($b->stock > 5)
                                <span class="badge bg-success">{{ $b->stock }}</span>
                            @elseif($b->stock > 0)
                                <span class="badge bg-warning text-dark">{{ $b->stock }} (Menipis)</span>
                            @else
                                <span class="badge bg-danger">0 (Habis)</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.books.edit', $b->id) }}" class="btn btn-sm btn-outline-warning" title="Edit Buku">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('admin.books.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku \"{{ $b->title }}\"?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Buku">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Tidak ada data buku yang sesuai filter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($books->hasPages())
        <div class="card-footer bg-white d-flex justify-content-center py-3">
            {{ $books->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
