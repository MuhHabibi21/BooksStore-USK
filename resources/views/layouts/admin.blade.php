<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - BookStore')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f5f9;
            color: #334155;
            min-height: 100vh;
        }
        .admin-sidebar {
            min-height: calc(100vh - 56px);
            background-color: #ffffff;
            border-right: 1px solid #e2e8f0;
        }
        .admin-sidebar .nav-link {
            color: #475569;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin-bottom: 0.25rem;
            font-weight: 500;
        }
        .admin-sidebar .nav-link:hover, .admin-sidebar .nav-link.active {
            color: #0d6efd;
            background-color: #eff6ff;
        }
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>
    <!-- Topbar -->
    <nav class="navbar navbar-expand navbar-dark bg-dark sticky-top">
        <div class="container-fluid px-2 px-md-3">
            <div class="d-flex align-items-center">
                <!-- Tombol Buka Menu Sidebar Khusus Mobile -->
                <button class="btn btn-outline-light btn-sm d-md-none me-2 px-2 py-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminMobileSidebar" aria-controls="adminMobileSidebar">
                    <i class="bi bi-list fs-5"></i>
                </button>

                <a class="navbar-brand fw-bold d-flex align-items-center gap-2 mb-0" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-shield-lock-fill text-warning"></i>
                    <span class="d-none d-sm-inline">BookStore Admin</span>
                    <span class="d-sm-none">Admin</span>
                </a>
            </div>

            <div class="ms-auto d-flex align-items-center gap-1 gap-md-2">
                <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-warning text-white px-2 py-1" title="Edit Profil">
                    <i class="bi bi-person-gear"></i> <span class="d-none d-sm-inline">Profil</span>
                </a>
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-light px-2 py-1" target="_blank" title="Buka Toko">
                    <i class="bi bi-shop"></i> <span class="d-none d-sm-inline">Toko</span>
                </a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger px-2 py-1" title="Keluar">
                        <i class="bi bi-box-arrow-right"></i> <span class="d-none d-sm-inline">Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Offcanvas Sidebar Khusus Layar HP / Mobile -->
    <div class="offcanvas offcanvas-start bg-dark text-white d-md-none" tabindex="-1" id="adminMobileSidebar" aria-labelledby="adminMobileSidebarLabel">
        <div class="offcanvas-header border-bottom border-secondary">
            <h5 class="offcanvas-title fw-bold text-warning" id="adminMobileSidebarLabel">
                <i class="bi bi-shield-lock-fill me-2"></i> BookStore Admin
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-3">
            <div class="text-uppercase text-secondary fw-bold small mb-2">Menu Utama</div>
            <ul class="nav flex-column mb-3">
                <li class="nav-item mb-1">
                    <a class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'bg-primary rounded' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link text-white {{ request()->routeIs('admin.categories.*') ? 'bg-primary rounded' : '' }}" href="{{ route('admin.categories.index') }}">
                        <i class="bi bi-tags me-2"></i> Kategori Buku
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link text-white {{ request()->routeIs('admin.books.*') ? 'bg-primary rounded' : '' }}" href="{{ route('admin.books.index') }}">
                        <i class="bi bi-journal-bookmark me-2"></i> Data Buku
                    </a>
                </li>
            </ul>

            <div class="text-uppercase text-secondary fw-bold small mb-2">Transaksi & Pengguna</div>
            <ul class="nav flex-column">
                <li class="nav-item mb-1">
                    <a class="nav-link text-white {{ request()->routeIs('admin.orders.*') ? 'bg-primary rounded' : '' }}" href="{{ route('admin.orders.index') }}">
                        <i class="bi bi-cart-check me-2"></i> Pesanan User
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link text-white {{ request()->routeIs('admin.users.*') ? 'bg-primary rounded' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="bi bi-people me-2"></i> User Terdaftar
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link text-white {{ request()->routeIs('admin.contacts.*') ? 'bg-primary rounded' : '' }}" href="{{ route('admin.contacts.index') }}">
                        <i class="bi bi-chat-left-text me-2"></i> Pesan Masuk
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 p-3 admin-sidebar d-none d-md-block">
                <div class="text-uppercase text-muted fw-bold small px-2 mb-2">Menu Utama</div>
                <ul class="nav flex-column mb-4">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                            <i class="bi bi-tags me-2"></i> Kategori Buku
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.books.*') ? 'active' : '' }}" href="{{ route('admin.books.index') }}">
                            <i class="bi bi-journal-bookmark me-2"></i> Data Buku
                        </a>
                    </li>
                </ul>

                <div class="text-uppercase text-muted fw-bold small px-2 mb-2">Transaksi & Pengguna</div>
                <ul class="nav flex-column mb-4">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                            <i class="bi bi-cart-check me-2"></i> Pesanan User
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                            <i class="bi bi-people me-2"></i> User Terdaftar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}" href="{{ route('admin.contacts.index') }}">
                            <i class="bi bi-chat-left-text me-2"></i> Pesan Masuk
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Content Area -->
            <main class="col-md-9 col-lg-10 ms-sm-auto px-md-4 py-4">
                <!-- Mobile Navigation Bar for small screens -->
                <div class="d-md-none mb-3">
                    <div class="d-flex overflow-auto pb-1 gap-1" style="white-space: nowrap;">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm {{ request()->routeIs('admin.dashboard') ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-sm {{ request()->routeIs('admin.categories.*') ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="bi bi-tags me-1"></i> Kategori
                        </a>
                        <a href="{{ route('admin.books.index') }}" class="btn btn-sm {{ request()->routeIs('admin.books.*') ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="bi bi-journal-bookmark me-1"></i> Buku
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm {{ request()->routeIs('admin.orders.*') ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="bi bi-cart-check me-1"></i> Pesanan
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm {{ request()->routeIs('admin.users.*') ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="bi bi-people me-1"></i> User
                        </a>
                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm {{ request()->routeIs('admin.contacts.*') ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="bi bi-chat-left-text me-1"></i> Pesan
                        </a>
                    </div>
                </div>

                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                        <div>{{ session('error') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="fw-bold mb-1"><i class="bi bi-exclamation-circle-fill me-1"></i> Terjadi kesalahan input:</div>
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
