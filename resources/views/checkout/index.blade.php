@extends('layouts.app')

@section('title', 'Pembayaran & Checkout - BookStore')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none">Keranjang</a></li>
            <li class="breadcrumb-item active" aria-current="page">Checkout & Pembayaran</li>
        </ol>
    </nav>

    <h3 class="fw-bold mb-4"><i class="bi bi-credit-card-2-front text-primary me-2"></i> Konfirmasi Pesanan & Pembayaran</h3>

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="row g-4">
            <!-- Informasi Pengiriman -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0"><i class="bi bi-geo-alt text-primary me-2"></i> 1. Informasi Penerima & Pengiriman</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label for="recipient_name" class="form-label fw-semibold">Nama Penerima <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('recipient_name') is-invalid @enderror" id="recipient_name" name="recipient_name" value="{{ old('recipient_name', $user->name) }}" required placeholder="Nama lengkap penerima buku">
                            @error('recipient_name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="recipient_phone" class="form-label fw-semibold">Nomor WhatsApp / HP Penerima <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('recipient_phone') is-invalid @enderror" id="recipient_phone" name="recipient_phone" value="{{ old('recipient_phone', $user->phone) }}" required placeholder="08xxxxxxxxxx">
                            @error('recipient_phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="delivery_address" class="form-label fw-semibold">Alamat Lengkap Pengiriman <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('delivery_address') is-invalid @enderror" id="delivery_address" name="delivery_address" rows="3" required placeholder="Tuliskan nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota/kabupaten...">{{ old('delivery_address', $user->address) }}</textarea>
                            @error('delivery_address')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label for="notes" class="form-label fw-semibold">Catatan Khusus untuk Kurir (Opsional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Contoh: Titipkan di satpam jika tidak ada orang di rumah.">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Metode Pembayaran -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0"><i class="bi bi-wallet2 text-primary me-2"></i> 2. Metode Pembayaran</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="form-check p-3 border rounded-3 mb-2 bg-light">
                            <input class="form-check-input ms-0 me-3" type="radio" name="payment_method" id="pay_delivery" value="Payment at Delivery" checked>
                            <label class="form-check-label w-100" for="pay_delivery">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong class="text-primary fs-6">Payment at Delivery (COD)</strong>
                                    <span class="badge bg-success">Direkomendasikan</span>
                                </div>
                                <p class="text-muted small mb-0 mt-1">Pembayaran buku diselesaikan secara tunai atau serah terima langsung sebelum/saat buku dikirimkan ke alamat Anda.</p>
                            </label>
                        </div>

                        <div class="form-check p-3 border rounded-3 text-muted">
                            <input class="form-check-input ms-0 me-3" type="radio" name="payment_method" id="pay_transfer" value="Bank Transfer">
                            <label class="form-check-label w-100" for="pay_transfer">
                                <strong>Transfer Bank / Virtual Account</strong>
                                <p class="small mb-0 mt-1">Metode alternatif transfer manual ke rekening toko buku.</p>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Rincian Pesanan -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm sticky-top" style="top: 80px;">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0"><i class="bi bi-receipt text-primary me-2"></i> Rincian Pesanan</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="list-group list-group-flush mb-3">
                            @foreach($cart as $item)
                                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="my-0 fw-bold">{{ $item['title'] }}</h6>
                                        <small class="text-muted">{{ $item['quantity'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}</small>
                                    </div>
                                    <span class="text-muted fw-semibold">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between border-top pt-3 mb-2">
                            <span class="text-muted">Biaya Pengiriman</span>
                            <span class="text-success fw-semibold">GRATIS (Promo Khusus)</span>
                        </div>

                        <div class="d-flex justify-content-between border-top pt-3 mb-4">
                            <span class="fs-5 fw-bold">Total Pembayaran</span>
                            <span class="fs-5 fw-bold text-primary">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold fs-6">
                            <i class="bi bi-check-circle-fill me-1"></i> Buat Pesanan Sekarang
                        </button>

                        <div class="text-center mt-3">
                            <a href="{{ route('cart.index') }}" class="text-muted text-decoration-none small">
                                <i class="bi bi-arrow-left me-1"></i> Kembali ke Keranjang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
