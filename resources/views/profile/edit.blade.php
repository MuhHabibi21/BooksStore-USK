@extends('layouts.app')

@section('title', 'Profil Saya & Pengaturan Akun - BookStore')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pengaturan Profil & Password</li>
        </ol>
    </nav>

    <!-- Header Profil Pengguna -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 65px; height: 65px; font-size: 1.8rem;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                        <div class="text-muted small">
                            <i class="bi bi-envelope me-1"></i> {{ $user->email }}
                            <span class="mx-2">&bull;</span>
                            <i class="bi bi-telephone me-1"></i> {{ $user->phone ?? 'Belum ada nomor HP' }}
                        </div>
                    </div>
                </div>

                <div>
                    @if($user->isAdmin())
                        <span class="badge bg-danger fs-6 px-3 py-2">
                            <i class="bi bi-shield-lock-fill me-1"></i> Akun Administrator
                        </span>
                    @else
                        <span class="badge bg-primary fs-6 px-3 py-2">
                            <i class="bi bi-person-check-fill me-1"></i> Pelanggan / User
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- FORM 1: Ubah Biodata & Informasi Profil -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-person-lines-fill text-primary me-2"></i> Informasi Biodata & Kontak
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="phone" class="form-label fw-semibold mb-1">Nomor WhatsApp / HP</label>
                                <span class="text-muted small">Maks 15 Karakter</span>
                            </div>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 081234567890 atau +6281234567890" maxlength="15" inputmode="tel" oninput="this.value = this.value.replace(/[^\d+]/g, '').replace(/(?!^\+)[+]/g, '').slice(0, 15)">
                            <small class="text-muted">Hanya angka & tanda + (contoh format: +62 atau 08).</small>
                            @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="address" class="form-label fw-semibold">Alamat Pengiriman</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" placeholder="Tuliskan alamat lengkap pengiriman buku...">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan Profil
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- FORM 2: Ganti Password Akun -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-shield-lock text-danger me-2"></i> Keamanan & Ganti Password
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-semibold">Password Saat Ini (Lama) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required placeholder="Masukkan password saat ini">
                            </div>
                            @error('current_password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password Baru <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Min 8 karakter">
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label fw-semibold">Ulangi Konfirmasi Password Baru <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi sandi">
                            </div>
                        </div>

                        <!-- Kotak Indikator Kombinasi Sandi Wajib -->
                        <div class="p-3 rounded-3 bg-light border mb-4" id="passwordCriteriaBox">
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

                        <button type="submit" class="btn btn-warning w-100 py-2 fw-semibold text-dark">
                            <i class="bi bi-check2-circle me-1"></i> Perbarui Password Akun
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
        checkPasswordRules();
    }
</script>
@endpush
