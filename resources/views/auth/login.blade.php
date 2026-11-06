@extends('layouts.app')

@section('title', 'Masuk Akun - BookStore')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-6 col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3 p-sm-4">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-lock text-primary fs-1"></i>
                        <h4 class="fw-bold mt-2">Masuk ke Akun Anda</h4>
                        <p class="text-muted small">Silakan masukkan email dan password untuk melanjutkan</p>
                    </div>

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="contoh@domain.com">
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Masukkan password">
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label text-muted small" for="remember">
                                Ingat saya di perangkat ini
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <span class="text-muted small">Belum punya akun?</span>
                        <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-semibold small">Daftar Akun Baru</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Mencegah kembali ke form login saat menekan tombol backward browser jika sudah login (BFCache handling)
    window.addEventListener('pageshow', function (event) {
        if (event.persisted || (window.performance && (window.performance.navigation?.type === 2 || window.performance.getEntriesByType('navigation')[0]?.type === 'back_forward'))) {
            window.location.reload();
        }
    });
</script>
@endpush
