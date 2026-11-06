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
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="contoh@domain.com">
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="phone" class="form-label fw-semibold mb-1">Nomor HP / WhatsApp</label>
                                <span class="text-muted small">Maks 15 Karakter</span>
                            </div>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 081234567890 atau +6281234567890" maxlength="15" inputmode="tel" oninput="this.value = this.value.replace(/[^\d+]/g, '').replace(/(?!^\+)[+]/g, '').slice(0, 15)">
                            <small class="text-muted">Hanya angka & tanda + (contoh format: +62 atau 08).</small>
                            @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-semibold">Kata Sandi <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Min 8 karakter">
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi sandi">
                            </div>
                        </div>

                        <!-- Kotak Indikator Kombinasi Sandi Wajib -->
                        <div class="p-3 rounded-3 bg-light border mb-3" id="passwordCriteriaBox">
                            <div class="small fw-semibold text-secondary mb-2">Kombinasi sandi wajib:</div>
                            <div class="row g-2 small">
                                <div class="col-6">
                                    <span id="rule-min" class="text-muted d-flex align-items-center">
                                        <i class="bi bi-circle me-1"></i> <span>Min. 8 Karakter</span>
                                    </span>
                                </div>
                                <div class="col-6">
                                    <span id="rule-upper" class="text-muted d-flex align-items-center">
                                        <i class="bi bi-circle me-1"></i> <span>Huruf Besar (A-Z)</span>
                                    </span>
                                </div>
                                <div class="col-6">
                                    <span id="rule-lower" class="text-muted d-flex align-items-center">
                                        <i class="bi bi-circle me-1"></i> <span>Huruf Kecil (a-z)</span>
                                    </span>
                                </div>
                                <div class="col-6">
                                    <span id="rule-symbol" class="text-muted d-flex align-items-center">
                                        <i class="bi bi-circle me-1"></i> <span>Angka & Simbol (@#$%)</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label fw-semibold">Alamat Pengiriman</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2" placeholder="Alamat lengkap jalan, RT/RW, kota, provinsi">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                            <i class="bi bi-check-circle me-1"></i> Buat Akun
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <span class="text-muted small">Sudah punya akun?</span>
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
    // Validasi interaktif checklist password
    const pwdInput = document.getElementById('password');
    if (pwdInput) {
        function checkPasswordRules() {
            const val = pwdInput.value || '';
            updateRule('rule-min', val.length >= 8);
            updateRule('rule-upper', /[A-Z]/.test(val));
            updateRule('rule-lower', /[a-z]/.test(val));
            updateRule('rule-symbol', /[0-9]/.test(val) && /[^a-zA-Z0-9]/.test(val));
        }

        function updateRule(elementId, isValid) {
            const el = document.getElementById(elementId);
            if (!el) return;
            const icon = el.querySelector('i');
            if (isValid) {
                el.classList.remove('text-muted');
                el.classList.add('text-success', 'fw-semibold');
                if (icon) icon.className = 'bi bi-check-circle-fill me-1 text-success';
            } else {
                el.classList.add('text-muted');
                el.classList.remove('text-success', 'fw-semibold');
                if (icon) icon.className = 'bi bi-circle me-1 text-muted';
            }
        }

        pwdInput.addEventListener('input', checkPasswordRules);
        // Check in case browser auto-filled
        checkPasswordRules();
    }

    // Mencegah kembali ke form registrasi saat menekan tombol backward browser jika sudah login (BFCache handling)
    window.addEventListener('pageshow', function (event) {
        if (event.persisted || (window.performance && (window.performance.navigation?.type === 2 || window.performance.getEntriesByType('navigation')[0]?.type === 'back_forward'))) {
            window.location.reload();
        }
    });
</script>
@endpush
