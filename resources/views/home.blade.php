@extends('layouts.app')

@section('title', 'Katalog Buku - Toko Buku Online BookStore')

@section('content')
<div class="container">
    <!-- Banner Selamat Datang Toko Buku -->
    <div class="card border-0 text-white rounded-3 shadow-sm mb-4 overflow-hidden" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
        <div class="card-body p-4 p-md-5">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase mb-2">
                        <i class="bi bi-shop me-1"></i> Toko Buku Online Resmi
                    </span>
                    <h1 class="display-6 fw-bold mb-2">Selamat Datang di BookStore</h1>
                    <p class="lead mb-3 opacity-90">Beli buku fisik original favorit Anda secara online dengan metode mudah <strong>Payment at Delivery (Bayar Saat Diantar)</strong>.</p>
                    <div class="d-flex flex-wrap gap-2 text-white small">
                        <span><i class="bi bi-patch-check-fill text-warning me-1"></i> 100% Koleksi Asli</span>
                        <span class="mx-2">&bull;</span>
                        <span><i class="bi bi-box-seam text-warning me-1"></i> Pengemasan Aman & Rapi</span>
                        <span class="mx-2">&bull;</span>
                        <span><i class="bi bi-truck text-warning me-1"></i> Bayar di Tempat (COD)</span>
                    </div>
                </div>
                <div class="col-lg-4 d-none d-lg-block text-center">
                    <i class="bi bi-journal-album display-1 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- SIDEBAR FILTER BUKU (Kategori, Rentang Harga, Ketersediaan) -->
        <div class="col-lg-3">
            <div class="filter-sidebar shadow-sm mb-4 sticky-top" style="top: 80px; z-index: 10;">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-funnel-fill text-primary me-1"></i> Filter Buku
                    </h5>
                    @if(request('category') || request('price_range') || request('in_stock') || request('q') || request('sort'))
                        <a href="{{ route('home') }}" class="text-danger text-decoration-none small fw-semibold" title="Hapus semua filter">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </a>
                    @endif
                </div>

                <!-- 1. Filter Kategori Buku -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-uppercase small text-muted mb-2">Kategori Buku:</label>
                    <div class="d-flex flex-column">
                        <a href="{{ route('home', array_merge(request()->except('category', 'page'))) }}" 
                           class="category-filter-item {{ !request('category') ? 'active' : '' }}">
                            <span><i class="bi bi-grid-fill me-2"></i> Semua Buku</span>
                            <span class="badge bg-secondary rounded-pill">{{ $totalAllBooks }}</span>
                        </a>

                        @foreach($categories as $cat)
                            <a href="{{ route('home', array_merge(request()->except('page'), ['category' => $cat->id])) }}" 
                               class="category-filter-item {{ request('category') == $cat->id ? 'active' : '' }}">
                                <span><i class="bi bi-bookmark me-2"></i> {{ $cat->name }}</span>
                                <span class="badge bg-light text-dark rounded-pill">{{ $cat->books_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <form action="{{ route('home') }}" method="GET">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif

                    <!-- 2. Filter Rentang Harga -->
                    <div class="mb-4 border-top pt-3">
                        <label class="form-label fw-bold text-uppercase small text-muted mb-2">Rentang Harga:</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="price_range" id="price_all" value="" {{ !request('price_range') ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label small" for="price_all">Semua Harga</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="price_range" id="price_under_50" value="under_50" {{ request('price_range') == 'under_50' ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label small" for="price_under_50">&lt; Rp 50.000</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="price_range" id="price_50_100" value="50_100" {{ request('price_range') == '50_100' ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label small" for="price_50_100">Rp 50.000 - Rp 100.000</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="price_range" id="price_above_100" value="above_100" {{ request('price_range') == 'above_100' ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label small" for="price_above_100">&gt; Rp 100.000</label>
                        </div>
                    </div>

                    <!-- 3. Filter Stok Tersedia -->
                    <div class="mb-4 border-top pt-3">
                        <label class="form-label fw-bold text-uppercase small text-muted mb-2">Ketersediaan:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="in_stock" id="in_stock" value="1" {{ request('in_stock') == '1' ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label small" for="in_stock">
                                Hanya buku yang ada stok
                            </label>
                        </div>
                    </div>

                    <!-- 4. Tombol Reset / Terapkan -->
                    <div class="d-grid gap-2 border-top pt-3">
                        <button type="submit" class="btn btn-primary btn-sm fw-semibold">Terapkan Filter</button>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">Reset Semua Filter</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- ETALASE JUALAN BUKU (Main Content) -->
        <div class="col-lg-9">
            <!-- Header Etalase & Pengurutan -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <h4 class="fw-bold mb-1 text-dark">
                                @if($selectedCategory)
                                    <span class="text-muted small d-block">Kategori Buku:</span>
                                    {{ $selectedCategory->name }}
                                @elseif(request('q'))
                                    Hasil Pencarian: "{{ request('q') }}"
                                @else
                                    Katalog Semua Buku
                                @endif
                            </h4>
                            <p class="text-muted small mb-0">Menampilkan <strong>{{ $books->total() }}</strong> judul buku yang siap dipesan</p>
                        </div>

                        <!-- Dropdown Urutan / Sort -->
                        <form action="{{ route('home') }}" method="GET" class="d-flex align-items-center gap-2">
                            @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            @if(request('q'))
                                <input type="hidden" name="q" value="{{ request('q') }}">
                            @endif
                            @if(request('price_range'))
                                <input type="hidden" name="price_range" value="{{ request('price_range') }}">
                            @endif

                            <label class="text-muted small text-nowrap" for="sortSelect">Urutkan:</label>
                            <select name="sort" id="sortSelect" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga: Terendah ke Tertinggi</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga: Tertinggi ke Terendah</option>
                                <option value="title_az" {{ request('sort') == 'title_az' ? 'selected' : '' }}>Judul (A-Z)</option>
                            </select>
                        </form>
                    </div>

                    <!-- Badge Filter Aktif -->
                    @if(request('category') || request('q') || request('price_range') || request('in_stock'))
                        <div class="d-flex flex-wrap gap-2 align-items-center mt-3 pt-3 border-top">
                            <span class="text-muted small">Filter aktif:</span>
                            @if($selectedCategory)
                                <span class="badge bg-primary px-2 py-1">
                                    Kategori: {{ $selectedCategory->name }}
                                    <a href="{{ route('home', array_merge(request()->except('category', 'page'))) }}" class="text-white text-decoration-none ms-1">&times;</a>
                                </span>
                            @endif
                            @if(request('q'))
                                <span class="badge bg-info text-dark px-2 py-1">
                                    Cari: "{{ request('q') }}"
                                    <a href="{{ route('home', array_merge(request()->except('q', 'page'))) }}" class="text-dark text-decoration-none ms-1">&times;</a>
                                </span>
                            @endif
                            @if(request('price_range'))
                                <span class="badge bg-secondary px-2 py-1">
                                    Harga: {{ request('price_range') }}
                                    <a href="{{ route('home', array_merge(request()->except('price_range', 'page'))) }}" class="text-white text-decoration-none ms-1">&times;</a>
                                </span>
                            @endif
                            @if(request('in_stock'))
                                <span class="badge bg-success px-2 py-1">
                                    Stok Ada
                                    <a href="{{ route('home', array_merge(request()->except('in_stock', 'page'))) }}" class="text-white text-decoration-none ms-1">&times;</a>
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- GRID DAFTAR BUKU -->
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4 mb-4">
                @forelse($books as $book)
                    <div class="col">
                        <div class="book-wrapper shadow-sm">
                            <!-- Cover Buku dengan Efek 3D Buku Fisik -->
                            <div class="book-cover-container">
                                @if($book->cover_image)
                                    <a href="{{ route('books.show', $book->id) }}">
                                        <img src="{{ asset('storage/' . $book->cover_image) }}" class="book-cover-img" alt="{{ $book->title }}">
                                    </a>
                                @else
                                    <a href="{{ route('books.show', $book->id) }}" class="text-decoration-none">
                                        <div class="book-cover-img bg-dark text-white d-flex align-items-center justify-content-center p-3 text-center">
                                            <div>
                                                <i class="bi bi-book fs-1 d-block mb-2"></i>
                                                <span class="small fw-bold">{{ $book->title }}</span>
                                            </div>
                                        </div>
                                    </a>
                                @endif

                                <!-- Badge Status Stok di Pojok Cover -->
                                <div class="position-absolute top-0 end-0 m-2">
                                    @if($book->stock > 0)
                                        <span class="badge bg-success shadow-sm">Stok: {{ $book->stock }}</span>
                                    @else
                                        <span class="badge bg-danger shadow-sm">Habis</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Detail Buku & Penjualan -->
                            <div class="p-3 d-flex flex-column flex-grow-1">
                                <!-- Kategori & Rating -->
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="badge bg-light text-primary border small">{{ $book->category->name ?? 'Buku' }}</span>
                                    <span class="text-warning small fw-bold">
                                        <i class="bi bi-star-fill"></i> 4.9
                                    </span>
                                </div>

                                <!-- Judul Buku -->
                                <h6 class="fw-bold mb-1 text-truncate mt-1" title="{{ $book->title }}">
                                    <a href="{{ route('books.show', $book->id) }}" class="text-dark text-decoration-none hover-primary">
                                        {{ $book->title }}
                                    </a>
                                </h6>

                                <!-- Penulis & Penerbit -->
                                <p class="text-muted small mb-2">
                                    Penulis: <strong>{{ $book->author }}</strong><br>
                                    <span class="opacity-75">{{ $book->publisher }} ({{ $book->year }})</span>
                                </p>

                                <!-- Harga & Aksi Beli -->
                                <div class="mt-auto pt-2 border-top">
                                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                                        <span class="text-muted small">Harga:</span>
                                        <span class="fs-5 fw-bold text-primary">Rp {{ number_format($book->price, 0, ',', '.') }}</span>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <a href="{{ route('books.show', $book->id) }}" class="btn btn-outline-secondary btn-sm flex-fill" title="Lihat Sinopsis">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>

                                        @if(Auth::check() && Auth::user()->isAdmin())
                                            <a href="{{ route('admin.books.edit', $book->id) }}" class="btn btn-outline-warning btn-sm flex-fill fw-semibold" title="Edit Buku (Admin)">
                                                <i class="bi bi-pencil-square me-1"></i> Edit
                                            </a>
                                        @else
                                            @if($book->stock > 0)
                                                @auth
                                                    <form action="{{ route('cart.add', $book->id) }}" method="POST" class="flex-fill">
                                                        @csrf
                                                        <button type="submit" class="btn btn-primary btn-sm w-100 fw-semibold">
                                                            <i class="bi bi-cart-plus me-1"></i> Beli
                                                        </button>
                                                    </form>
                                                @else
                                                    <button type="button" class="btn btn-primary btn-sm flex-fill fw-semibold" data-bs-toggle="modal" data-bs-target="#loginPromptModal">
                                                        <i class="bi bi-cart-plus me-1"></i> Beli
                                                    </button>
                                                @endauth
                                            @else
                                                <button class="btn btn-secondary btn-sm flex-fill" disabled>
                                                    Habis
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 py-5 text-center">
                        <div class="card border-0 shadow-sm p-5">
                            <i class="bi bi-book text-muted mb-3" style="font-size: 3.5rem;"></i>
                            <h4 class="fw-bold text-dark">Tidak Ada Buku yang Sesuai</h4>
                            <p class="text-muted small">Kategori atau kriteria filter yang Anda pilih saat ini belum memiliki koleksi buku.</p>
                            <div>
                                <a href="{{ route('home') }}" class="btn btn-primary px-4">
                                    <i class="bi bi-grid me-1"></i> Tampilkan Semua Koleksi Buku
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Navigasi Halaman Pagination -->
            @if($books->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $books->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
