<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BookStore - Toko Buku Online Terpercaya')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            color: #212529;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        main {
            flex: 1;
        }
        .navbar-brand {
            font-weight: 800;
            letter-spacing: 0.5px;
            font-size: 1.4rem;
        }
        /* Realistic Book Styling */
        .book-wrapper {
            position: relative;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            transition: all 0.25s ease-in-out;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .book-wrapper:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.12);
            border-color: #cbd5e1;
        }
        .book-cover-container {
            position: relative;
            background: #e2e8f0;
            padding: 1rem;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 260px;
        }
        .book-cover-img {
            height: 230px;
            width: 160px;
            object-fit: cover;
            border-radius: 4px 6px 6px 4px;
            box-shadow: -4px 4px 10px rgba(0, 0, 0, 0.25), 
                        inset -2px 0 4px rgba(0,0,0,0.2);
            transition: transform 0.2s ease;
        }
        .book-wrapper:hover .book-cover-img {
            transform: scale(1.03);
        }
        .filter-sidebar {
            background: #fff;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 1.25rem;
        }
        .category-filter-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            color: #475569;
            text-decoration: none;
            font-size: 0.92rem;
            margin-bottom: 2px;
            transition: background 0.15s;
        }
        .category-filter-item:hover {
            background: #f1f5f9;
            color: #0d6efd;
        }
        .category-filter-item.active {
            background: #0d6efd;
            color: #fff;
            font-weight: 600;
        }
        .category-filter-item.active .badge {
            background: #fff !important;
            color: #0d6efd !important;
        }

        /* ===== Mobile Responsive Styles (Tampilan HP) ===== */
        .category-scroll-container {
            display: flex;
            overflow-x: auto;
            white-space: nowrap;
            gap: 0.5rem;
            padding: 0.25rem 0.25rem 0.5rem 0.25rem;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
        }
        .category-scroll-container::-webkit-scrollbar {
            height: 4px;
        }
        .category-scroll-container::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 4px;
        }
        .category-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.45rem 0.9rem;
            border-radius: 50rem;
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            background-color: #fff;
            color: #475569;
            border: 1px solid #cbd5e1;
            transition: all 0.2s ease;
            flex-shrink: 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .category-pill:hover, .category-pill.active {
            background-color: #0d6efd;
            color: #fff;
            border-color: #0d6efd;
        }
        .category-pill.active .badge {
            background-color: #fff !important;
            color: #0d6efd !important;
        }

        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: #0b5ed7;
                padding: 1rem;
                border-radius: 0.5rem;
                margin-top: 0.5rem;
            }
            .navbar-nav .nav-link {
                padding: 0.6rem 0.5rem;
                border-radius: 0.25rem;
            }
            .navbar-nav .nav-link:hover {
                background: rgba(255, 255, 255, 0.1);
            }
            .navbar-nav .btn {
                width: 100%;
                margin-top: 0.35rem;
                margin-bottom: 0.35rem;
                text-align: center;
            }
        }

        @media (max-width: 767.98px) {
            .navbar-brand {
                font-size: 1.25rem;
            }
            .book-cover-container {
                min-height: 180px;
                padding: 0.5rem;
            }
            .book-cover-img {
                height: 155px;
                width: 110px;
                max-width: 100%;
            }
            .filter-sidebar {
                padding: 1rem;
            }
        }

        @media (max-width: 575.98px) {
            .container {
                padding-left: 14px;
                padding-right: 14px;
            }
            .book-cover-container {
                min-height: 165px;
                padding: 0.35rem;
            }
            .book-cover-img {
                height: 140px;
                width: 98px;
            }
            .book-wrapper h6 {
                font-size: 0.9rem;
            }
            .book-wrapper p {
                font-size: 0.75rem;
            }
            .book-wrapper .btn {
                font-size: 0.8rem;
                padding: 0.25rem 0.4rem;
            }
            .book-wrapper .fs-5 {
                font-size: 0.95rem !important;
            }
        }
    </style>
</head>
<body>
    <!-- Top Announcement Bar -->
    <div class="bg-dark text-white py-1 px-3 small border-bottom">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-truck me-1 text-warning"></i> 
                <span><span class="d-none d-sm-inline">Toko Buku Online Terlengkap &bull; </span>Melayani <strong>Payment at Delivery (COD)</strong> ke Seluruh Alamat</span>
            </div>
            <div class="d-none d-md-block text-light opacity-75">
                <i class="bi bi-shield-check me-1 text-success"></i> 100% Koleksi Buku Asli & Terpercaya
            </div>
        </div>
    </div>

    <!-- Main Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm py-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <i class="bi bi-book-half fs-3 text-warning"></i>
                <span>BookStore</span>
            </a>

            <!-- Search Form in Navbar -->
            <form action="{{ route('home') }}" method="GET" class="d-none d-lg-flex mx-4 flex-grow-1" style="max-width: 500px;">
                <div class="input-group">
                    <input type="text" name="q" class="form-control border-0" placeholder="Cari judul buku, penulis, atau penerbit..." value="{{ request('q') }}">
                    <button class="btn btn-warning text-dark fw-bold px-3" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>

            <!-- Tombol Keranjang Cepat & Toggler Hamburger di Tampilan HP -->
            <div class="d-flex align-items-center gap-2 d-lg-none">
                @if(!Auth::check() || !Auth::user()->isAdmin())
                    @php
                        $mobileCartCount = array_sum(array_column(session('cart', []), 'quantity'));
                    @endphp
                    <a class="btn btn-warning text-dark position-relative btn-sm px-2 py-1 shadow-sm" href="{{ route('cart.index') }}" title="Keranjang Belanja">
                        <i class="bi bi-cart3 fs-6"></i>
                        @if($mobileCartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light" style="font-size: 0.65rem;">
                                {{ $mobileCartCount }}
                            </span>
                        @endif
                    </a>
                @endif
                <button class="navbar-toggler border-0 p-1" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <!-- Mobile Search -->
                <form action="{{ route('home') }}" method="GET" class="d-lg-none my-2">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="Cari buku..." value="{{ request('q') }}">
                        <button class="btn btn-warning" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active fw-bold' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-grid me-1"></i> Semua Buku
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active fw-bold' : '' }}" href="{{ route('about') }}">
                            <i class="bi bi-info-circle me-1"></i> Tentang Kami
                        </a>
                    </li>
                    @if(!Auth::check() || !Auth::user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('contact') ? 'active fw-bold' : '' }}" href="{{ route('contact') }}">
                                <i class="bi bi-envelope me-1"></i> Kontak Admin
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active fw-bold' : '' }}" href="{{ route('admin.contacts.index') }}">
                                <i class="bi bi-chat-left-text me-1"></i> Pesan Masuk
                            </a>
                        </li>
                    @endif
                </ul>

                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    @if(!Auth::check() || !Auth::user()->isAdmin())
                        <!-- Cart Button with Counter (Khusus User / Tamu) -->
                        @php
                            $cartCount = array_sum(array_column(session('cart', []), 'quantity'));
                        @endphp
                        <li class="nav-item">
                            <a class="btn btn-light text-primary position-relative fw-semibold px-3" href="{{ route('cart.index') }}">
                                <i class="bi bi-cart3 me-1"></i> Keranjang
                                @if($cartCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">
                                        {{ $cartCount }}
                                    </span>
                                @endif
                            </a>
                        </li>
                    @endif

                    @guest
                        @if(request()->routeIs('login') || request()->is('login*'))
                            <li class="nav-item">
                                <a class="btn btn-warning text-dark fw-bold px-3 shadow-sm" href="{{ route('login') }}">Masuk</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white fw-semibold" href="{{ route('register') }}">Daftar</a>
                            </li>
                        @elseif(request()->routeIs('register') || request()->is('register*'))
                            <li class="nav-item">
                                <a class="nav-link text-white fw-semibold" href="{{ route('login') }}">Masuk</a>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-warning text-dark fw-bold px-3 shadow-sm" href="{{ route('register') }}">Daftar</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link text-white fw-semibold" href="{{ route('login') }}">Masuk</a>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-warning text-dark fw-bold px-3" href="{{ route('register') }}">Daftar</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white d-flex align-items-center gap-2" href="#" id="userMenu" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle fs-5"></i>
                                <span>{{ Auth::user()->name }}</span>
                                @if(Auth::user()->isAdmin())
                                    <span class="badge bg-warning text-dark">Admin</span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                @if(Auth::user()->isAdmin())
                                    <li>
                                        <a class="dropdown-item fw-semibold text-primary" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2"></i> Panel Dashboard Admin
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.books.index') }}">
                                            <i class="bi bi-journal-bookmark me-2"></i> Kelola Data Buku
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.orders.index') }}">
                                            <i class="bi bi-cart-check me-2"></i> List Pesanan User
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.contacts.index') }}">
                                            <i class="bi bi-chat-left-text me-2"></i> Kotak Pesan Masuk
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                            <i class="bi bi-person-gear me-2 text-secondary"></i> Profil & Password Saya
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                @else
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                            <i class="bi bi-person-gear me-2 text-secondary"></i> Profil & Password Saya
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('orders.index') }}">
                                            <i class="bi bi-bag-check me-2 text-secondary"></i> Pesanan Saya
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> Keluar (Logout)
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <div class="fw-bold mb-1"><i class="bi bi-exclamation-circle-fill me-1"></i> Mohon periksa kesalahan input berikut:</div>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-top py-4 mt-auto">
        <div class="container text-muted small">
            <div class="row g-4 align-items-center">
                <div class="col-md-5">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-book-half fs-4 text-primary"></i>
                        <span class="fs-5 fw-bold text-dark">BookStore</span>
                    </div>
                    <p class="mb-0">Platform Toko Buku Online lengkap dan terpercaya. Membantu mencerdaskan bangsa dengan kemudahan akses terhadap ribuan koleksi buku berkualitas.</p>
                </div>
                <div class="col-md-3">
                    <h6 class="fw-bold text-dark mb-2">Layanan & Keamanan</h6>
                    <ul class="list-unstyled mb-0">
                        <li><i class="bi bi-check2 text-success me-1"></i> Payment at Delivery (COD)</li>
                        <li><i class="bi bi-check2 text-success me-1"></i> 100% Produk Buku Asli</li>
                        <li><i class="bi bi-check2 text-success me-1"></i> Pengiriman Cepat & Rapi</li>
                    </ul>
                </div>
                <div class="col-md-4 text-md-end">
                    <p class="mb-1"><strong>Pusat Layanan Pelanggan</strong></p>
                    <p class="mb-2 text-muted">Buka Setiap Hari: 08.00 - 21.00 WIB</p>
                    <div>
                        <a href="{{ route('home') }}" class="text-decoration-none text-muted me-3">Katalog Buku</a>
                        <a href="{{ route('about') }}" class="text-decoration-none text-muted me-3">Tentang Kami</a>
                        <a href="{{ route('contact') }}" class="text-decoration-none text-muted">Kontak Admin</a>
                    </div>
                </div>
            </div>
            <hr class="my-3">
            <div class="text-center">
                &copy; {{ date('Y') }} <strong>BookStore</strong>. Seluruh hak cipta dilindungi.
            </div>
        </div>
    </footer>

    <!-- Modal Peringatan Belum Login -->
    @guest
    <div class="modal fade" id="loginPromptModal" tabindex="-1" aria-labelledby="loginPromptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body text-center p-4 pt-1">
                    <div class="mb-3">
                        <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 75px; height: 75px;">
                            <i class="bi bi-box-arrow-in-right fs-1"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-2">Anda belum login, yuk login dulu</h4>
                    <p class="text-muted mb-4 small">Silakan masuk ke akun Anda terlebih dahulu untuk menambahkan buku ke keranjang belanja atau melakukan checkout pesanan.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('login') }}" class="btn btn-primary py-2 fw-bold fs-6">
                            <i class="bi bi-person-check-fill me-1"></i> Masuk Sekarang (Login)
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-secondary py-2 small">
                            Belum punya akun? Daftar Akun Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endguest

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
