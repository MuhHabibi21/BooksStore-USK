@extends('layouts.app')

@section('title', $book->title . ' - Toko Buku BookStore')

@section('content')
<div class="container py-3">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda Toko</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('home', ['category' => $book->category_id]) }}" class="text-decoration-none">
                    {{ $book->category->name }}
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($book->title, 35) }}</li>
        </ol>
    </nav>

    <div class="card shadow-sm border-0 mb-5 overflow-hidden">
        <div class="card-body p-4 p-md-5">
            <div class="row g-5">
                <!-- Cover Buku Fisik 3D -->
                <div class="col-lg-4 text-center">
                    <div class="p-4 bg-light rounded-4 d-inline-block shadow-sm">
                        @if($book->cover_image)
                            <img src="{{ asset('storage/' . $book->cover_image) }}" 
                                 class="img-fluid rounded" 
                                 alt="{{ $book->title }}" 
                                 style="max-height: 420px; width: 280px; object-fit: cover; box-shadow: -8px 8px 20px rgba(0,0,0,0.3);">
                        @else
                            <div class="rounded bg-dark text-white p-5 d-flex align-items-center justify-content-center" 
                                 style="height: 380px; width: 260px; box-shadow: -8px 8px 20px rgba(0,0,0,0.3);">
                                <div>
                                    <i class="bi bi-book fs-1 d-block mb-3"></i>
                                    <h5 class="fw-bold">{{ $book->title }}</h5>
                                    <p class="small mb-0 opacity-75">{{ $book->author }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-3 text-muted small">
                        <i class="bi bi-shield-check text-success me-1"></i> Garansi 100% Produk Buku Asli
                    </div>
                </div>

                <!-- Informasi & Aksi Pembelian -->
                <div class="col-lg-8">
                    <!-- Kategori & Rating -->
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <a href="{{ route('home', ['category' => $book->category_id]) }}" class="badge bg-primary text-decoration-none px-3 py-2">
                            {{ $book->category->name }}
                        </a>
                        <span class="text-warning small fw-bold">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <span class="text-muted ms-1">(4.9/5 &bull; 85 ulasan pembaca)</span>
                        </span>
                    </div>

                    <h2 class="fw-bold text-dark mb-1">{{ $book->title }}</h2>
                    <h5 class="text-muted fw-normal mb-4">
                        Penulis: <strong class="text-dark">{{ $book->author }}</strong> &bull; Penerbit: <span class="text-secondary">{{ $book->publisher }}</span>
                    </h5>

                    <!-- Box Harga & Stok -->
                    <div class="p-4 bg-light rounded-3 mb-4 border">
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <span class="text-muted small d-block">Harga Buku:</span>
                                <div class="d-flex align-items-baseline gap-2">
                                    <span class="display-6 fw-bold text-primary">Rp {{ number_format($book->price, 0, ',', '.') }}</span>
                                </div>
                                <div class="mt-2">
                                    @if($book->stock > 0)
                                        <span class="badge bg-success-subtle text-success border border-success px-2 py-1">
                                            <i class="bi bi-check-circle me-1"></i> Stok Tersedia ({{ $book->stock }} buku)
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1">
                                            <i class="bi bi-x-circle me-1"></i> Stok Buku Sedang Habis
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                @if(Auth::check() && Auth::user()->isAdmin())
                                    <div class="alert alert-warning py-3 small mb-0 border">
                                        <div class="fw-bold mb-1"><i class="bi bi-shield-lock me-1"></i> Mode Administrator</div>
                                        <p class="mb-2 text-muted">Akun Admin hanya digunakan untuk mengontrol inventaris buku dan pesanan pengguna.</p>
                                        <a href="{{ route('admin.books.edit', $book->id) }}" class="btn btn-warning btn-sm w-100 fw-bold">
                                            <i class="bi bi-pencil-square me-1"></i> Edit Buku di Panel Admin
                                        </a>
                                    </div>
                                @else
                                    @if($book->stock > 0)
                                        @auth
                                            <form action="{{ route('cart.add', $book->id) }}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="quantity" class="form-label small fw-semibold text-muted">Jumlah Pembelian:</label>
                                                    <div class="input-group" style="max-width: 140px;">
                                                        <input type="number" id="quantity" name="quantity" class="form-control text-center" value="1" min="1" max="{{ $book->stock }}">
                                                    </div>
                                                </div>

                                                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                                                    <i class="bi bi-cart-plus-fill me-1"></i> Masukkan ke Keranjang
                                                </button>
                                            </form>
                                        @else
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold text-muted">Jumlah Pembelian:</label>
                                                <div class="input-group" style="max-width: 140px;">
                                                    <input type="number" class="form-control text-center" value="1" min="1" max="{{ $book->stock }}">
                                                </div>
                                            </div>

                                            <button type="button" class="btn btn-primary w-100 py-2 fw-bold" data-bs-toggle="modal" data-bs-target="#loginPromptModal">
                                                <i class="bi bi-cart-plus-fill me-1"></i> Masukkan ke Keranjang
                                            </button>
                                        @endauth
                                    @else
                                        <div class="alert alert-secondary mb-0 small">
                                            Buku ini sedang habis. Silakan hubungi admin melalui menu <strong>Kontak Admin</strong> untuk menanyakan jadwal restock.
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Banner Payment at Delivery -->
                    <div class="alert alert-info d-flex align-items-center gap-3 py-3 mb-4">
                        <i class="bi bi-truck fs-2 text-primary"></i>
                        <div class="small">
                            <strong>Dukungan Pembayaran Payment at Delivery:</strong><br>
                            Anda dapat memesan buku sekarang dan menyelesaikan pembayaran langsung saat buku diantarkan oleh kurir ke alamat tujuan.
                        </div>
                    </div>

                    <!-- Spesifikasi Buku -->
                    <h5 class="fw-bold border-bottom pb-2 mb-3">Spesifikasi Detail Buku</h5>
                    <div class="row row-cols-2 g-2 small mb-4">
                        <div class="col"><span class="text-muted">Kategori:</span> <strong>{{ $book->category->name }}</strong></div>
                        <div class="col"><span class="text-muted">Tahun Rilis:</span> <strong>{{ $book->year }}</strong></div>
                        <div class="col"><span class="text-muted">Penulis:</span> <strong>{{ $book->author }}</strong></div>
                        <div class="col"><span class="text-muted">Penerbit:</span> <strong>{{ $book->publisher }}</strong></div>
                        <div class="col"><span class="text-muted">Bahasa:</span> <strong>Bahasa Indonesia</strong></div>
                        <div class="col"><span class="text-muted">Kondisi:</span> <strong class="text-success">Baru & Segel Original</strong></div>
                    </div>

                    <!-- Sinopsis Lengkap -->
                    <h5 class="fw-bold border-bottom pb-2 mb-3">Sinopsis & Deskripsi Buku</h5>
                    <div class="text-secondary lh-lg">
                        {!! nl2br(e($book->description ?? 'Belum ada deskripsi untuk buku ini.')) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rekomendasi Buku Lainnya -->
    @if($relatedBooks->count() > 0)
        <div class="mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">Rekomendasi Buku Terkait</h4>
                <a href="{{ route('home', ['category' => $book->category_id]) }}" class="btn btn-outline-primary btn-sm">Lihat Semua Kategori Ini</a>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
                @foreach($relatedBooks as $rel)
                    <div class="col">
                        <div class="book-wrapper shadow-sm">
                            <div class="book-cover-container" style="min-height: 220px;">
                                @if($rel->cover_image)
                                    <a href="{{ route('books.show', $rel->id) }}">
                                        <img src="{{ asset('storage/' . $rel->cover_image) }}" class="book-cover-img" style="height: 180px; width: 125px;" alt="{{ $rel->title }}">
                                    </a>
                                @endif
                            </div>
                            <div class="p-3 d-flex flex-column flex-grow-1">
                                <h6 class="fw-bold mb-1 text-truncate">
                                    <a href="{{ route('books.show', $rel->id) }}" class="text-dark text-decoration-none">
                                        {{ $rel->title }}
                                    </a>
                                </h6>
                                <p class="text-muted small mb-2">{{ $rel->author }}</p>
                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <strong class="text-primary">Rp {{ number_format($rel->price, 0, ',', '.') }}</strong>
                                    <a href="{{ route('books.show', $rel->id) }}" class="btn btn-outline-secondary btn-sm">Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
