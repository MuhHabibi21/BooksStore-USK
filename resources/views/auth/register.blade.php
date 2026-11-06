@extends('layouts.app')

@section('title', 'Daftar Akun Baru - BookStore')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3 p-sm-4">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-plus text-primary fs-1"></i>
                        <h4 class="fw-bold mt-2">Pendaftaran Akun Pengguna</h4>
                        <p class="text-muted small">Lengkapi data di bawah ini untuk membuat akun baru</p>
                    </div>

                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="Nama lengkap Anda">
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com">
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Min. 6 karakter">
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label fw-semibold">Nomor WhatsApp / HP</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 081234567890" maxlength="13" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            <small class="text-muted">Hanya angka, antara 10 hingga 13 digit (maks. 13 angka).</small>
                            @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label fw-semibold">Alamat Pengiriman</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2" placeholder="Alamat lengkap jalan, RT/RW, kota, provinsi">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                            <i class="bi bi-check-circle me-1"></i> Daftar Sekarang
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <span class="text-muted small">Sudah memiliki akun?</span>
                        <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-semibold small">Masuk di sini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Mencegah kembali ke form registrasi saat menekan tombol backward browser jika sudah login (BFCache handling)
    window.addEventListener('pageshow', function (event) {
        if (event.persisted || (window.performance && (window.performance.navigation?.type === 2 || window.performance.getEntriesByType('navigation')[0]?.type === 'back_forward'))) {
            window.location.reload();
        }
    });
</script>
@endpush
