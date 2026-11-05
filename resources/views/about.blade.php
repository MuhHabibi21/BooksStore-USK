@extends('layouts.app')

@section('title', 'Tentang Kami (About Us) - BookStore')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tentang Kami</li>
        </ol>
    </nav>

    <div class="row g-4 align-items-center mb-5">
        <div class="col-lg-7">
            <span class="badge bg-primary-subtle text-primary px-3 py-2 mb-2 fw-semibold">Tentang BookStore</span>
            <h2 class="fw-bold mb-3">Solusi Belanja Buku Terpercaya untuk Masa Depan Pendidikan & Literasi</h2>
            <p class="text-secondary lead">BookStore hadir sebagai platform katalog dan pemesanan buku daring modern yang memudahkan pembaca, pelajar, serta akademisi mendapatkan literatur berkualitas dari berbagai penerbit terkemuka di Indonesia.</p>
            <p class="text-secondary">Kami berkomitmen untuk menyediakan koleksi buku lengkap mulai dari buku pemrograman & teknologi, sains, sastra fiksi, bisnis ekonomi, hingga buku pengembangan kepribadian dengan sistem pemesanan yang mudah, aman, dan metode pembayaran transparan.</p>
        </div>
        <div class="col-lg-5">
            <div class="card bg-primary text-white border-0 shadow p-4 rounded-4">
                <h4 class="fw-bold mb-3"><i class="bi bi-star-fill text-warning me-2"></i> Kenapa Memilih BookStore?</h4>
                <p class="small opacity-75 mb-3">Kami selalu mengutamakan kepuasan pecinta buku dengan menghadirkan pengalaman belanja online yang nyaman, cepat, dan amanah.</p>
                <ul class="list-unstyled small mb-0">
                    <li class="mb-2"><i class="bi bi-check2-circle text-warning me-2"></i> <strong>100% Buku Asli:</strong> Seluruh produk dipasok langsung dari penerbit resmi nasional dan internasional.</li>
                    <li class="mb-2"><i class="bi bi-check2-circle text-warning me-2"></i> <strong>Pengemasan Ekstra:</strong> Setiap buku dibungkus dengan kardus dan bubble wrap tebal tanpa biaya tambahan.</li>
                    <li class="mb-2"><i class="bi bi-check2-circle text-warning me-2"></i> <strong>Payment at Delivery:</strong> Bayar dengan nyaman saat buku tiba di alamat Anda.</li>
                    <li><i class="bi bi-check2-circle text-warning me-2"></i> <strong>Garansi Buku Rusak:</strong> Layanan penggantian barang gratis bila buku yang diterima cacat produksi.</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Keunggulan Layanan -->
    <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
        <div class="col">
            <div class="card h-100 border-0 shadow-sm p-3 text-center">
                <div class="card-body">
                    <div class="p-3 bg-light rounded-circle d-inline-block text-primary mb-3">
                        <i class="bi bi-check2-all fs-2"></i>
                    </div>
                    <h5 class="fw-bold">100% Buku Asli</h5>
                    <p class="text-muted small mb-0">Seluruh buku dipasok langsung dari distributor resmi dan penerbit kredibel nasional maupun internasional.</p>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100 border-0 shadow-sm p-3 text-center">
                <div class="card-body">
                    <div class="p-3 bg-light rounded-circle d-inline-block text-success mb-3">
                        <i class="bi bi-truck fs-2"></i>
                    </div>
                    <h5 class="fw-bold">Payment at Delivery</h5>
                    <p class="text-muted small mb-0">Kemudahan pembayaran saat buku diantar / sebelum pesanan dikirim ke alamat rumah Anda.</p>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card h-100 border-0 shadow-sm p-3 text-center">
                <div class="card-body">
                    <div class="p-3 bg-light rounded-circle d-inline-block text-warning mb-3">
                        <i class="bi bi-headset fs-2"></i>
                    </div>
                    <h5 class="fw-bold">Layanan Ramah Admin</h5>
                    <p class="text-muted small mb-0">Hubungi langsung staf admin kami kapan pun melalui fitur formulir kontak yang responsif.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
