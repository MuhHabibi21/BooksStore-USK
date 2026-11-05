@extends('layouts.app')

@section('title', 'Keranjang Belanja - BookStore')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">Keranjang Belanja</li>
        </ol>
    </nav>

    <h3 class="fw-bold mb-4"><i class="bi bi-cart3 text-primary me-2"></i> Keranjang Belanja Anda</h3>

    @if(count($cart) > 0)
        <div class="row g-4">
            <!-- Tabel Item Keranjang -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="min-width: 250px;">Buku</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col" style="width: 140px;">Jumlah</th>
                                    <th scope="col">Subtotal</th>
                                    <th scope="col" class="text-center" style="width: 60px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $id => $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if(!empty($item['cover_image']))
                                                    <img src="{{ asset('storage/' . $item['cover_image']) }}" alt="{{ $item['title'] }}" class="rounded me-3" style="width: 50px; height: 65px; object-fit: cover;">
                                                @else
                                                    <div class="rounded bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 65px; font-size: 1.2rem;">
                                                        <i class="bi bi-book"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <a href="{{ route('books.show', $id) }}" class="fw-bold text-dark text-decoration-none d-block">
                                                        {{ $item['title'] }}
                                                    </a>
                                                    <small class="text-muted">{{ $item['author'] ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                        <td>
                                            <form action="{{ route('cart.update', $id) }}" method="POST" class="d-flex align-items-center gap-1">
                                                @csrf
                                                <input type="number" name="quantity" class="form-control form-control-sm text-center" value="{{ $item['quantity'] }}" min="1" max="{{ $item['stock'] ?? 99 }}">
                                                <button type="submit" class="btn btn-outline-secondary btn-sm" title="Perbarui jumlah">
                                                    <i class="bi bi-arrow-repeat"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="fw-bold text-primary">
                                            Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus dari keranjang" onclick="return confirm('Hapus buku ini dari keranjang?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer bg-white d-flex justify-content-between align-items-center p-3">
                        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Tambah Buku Lainnya
                        </a>
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-sm" onclick="return confirm('Kosongkan semua isi keranjang?')">
                                <i class="bi bi-x-circle me-1"></i> Kosongkan Keranjang
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Belanja & Checkout -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3 border-bottom pb-2">Ringkasan Belanja</h5>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Item</span>
                            <span class="fw-semibold">{{ array_sum(array_column($cart, 'quantity')) }} buku</span>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-semibold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>

                        <div class="d-flex justify-content-between mb-3 border-top pt-3">
                            <span class="fs-5 fw-bold">Total Tagihan</span>
                            <span class="fs-5 fw-bold text-primary">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>

                        <div class="alert alert-info py-2 small mb-3">
                            <i class="bi bi-truck me-1"></i> Metode Pembayaran: <strong>Payment at Delivery</strong> (Bayar saat buku diantar / sebelum dikirim).
                        </div>

                        @auth
                            <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 py-2 fw-semibold">
                                Lanjut ke Pembayaran <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        @else
                            <button type="button" class="btn btn-primary w-100 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#loginPromptModal">
                                Lanjut ke Pembayaran <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm py-5 text-center">
            <div class="card-body">
                <i class="bi bi-cart-x text-muted" style="font-size: 4rem;"></i>
                <h4 class="fw-bold mt-3">Keranjang Belanja Anda Masih Kosong</h4>
                <p class="text-muted small">Anda belum menambahkan koleksi buku apa pun ke dalam keranjang.</p>
                <a href="{{ route('home') }}" class="btn btn-primary px-4 mt-2">
                    <i class="bi bi-grid me-1"></i> Jelajahi Katalog Buku
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
