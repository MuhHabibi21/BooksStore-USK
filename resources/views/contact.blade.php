@extends('layouts.app')

@section('title', 'Hubungi Admin (Contact Us) - BookStore')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">Kontak Admin</li>
        </ol>
    </nav>

    <div class="row g-4 justify-content-center">
        <!-- Informasi Kontak -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt-fill text-primary me-2"></i> Kantor Operasional</h5>
                    <p class="text-muted small">
                        <strong>BookStore Indonesia</strong><br>
                        Jl. MT. Haryono Kavling 52, Graha BookStore<br>
                        Jakarta Selatan, DKI Jakarta 12770
                    </p>

                    <hr class="my-4">

                    <h5 class="fw-bold mb-3"><i class="bi bi-clock-fill text-primary me-2"></i> Jam Kerja Layanan</h5>
                    <p class="text-muted small mb-1"><strong>Senin - Jumat:</strong> 08.00 - 17.00 WIB</p>
                    <p class="text-muted small"><strong>Sabtu:</strong> 09.00 - 15.00 WIB</p>

                    <hr class="my-4">

                    <h5 class="fw-bold mb-3"><i class="bi bi-headset text-primary me-2"></i> Saluran Cepat</h5>
                    <p class="text-muted small mb-1"><i class="bi bi-envelope me-2"></i> admin@bookstore.com</p>
                    <p class="text-muted small"><i class="bi bi-whatsapp me-2"></i> +62 812-3456-7890</p>
                </div>
            </div>
        </div>

        <!-- Formulir Kirim Pesan ke Admin -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <h4 class="fw-bold mb-2">Kirim Pesan ke Admin</h4>
                    <p class="text-muted small mb-4">Punya pertanyaan seputar ketersediaan buku, pesanan, atau kerja sama? Silakan tuliskan pesan Anda pada formulir di bawah ini.</p>

                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', Auth::user()->name ?? '') }}" required placeholder="Nama Anda">
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', Auth::user()->email ?? '') }}" required placeholder="nama@email.com">
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="subject" class="form-label fw-semibold">Subjek Pesan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" required placeholder="Contoh: Menanyakan Stok Buku / Bantuan Transaksi">
                                @error('subject')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="message" class="form-label fw-semibold">Isi Pesan <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" required placeholder="Tuliskan detail pertanyaan atau masukan Anda di sini...">{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">
                                    <i class="bi bi-send-fill me-1"></i> Kirim Pesan Sekarang
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(isset($myMessages) && $myMessages->count() > 0)
        <!-- Riwayat Pesan & Balasan Admin -->
        <div class="row justify-content-center mt-5" id="riwayat-pesan">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                    <h4 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-chat-dots-fill text-primary me-2"></i> Riwayat Pesan Anda & Balasan Admin
                    </h4>
                    <span class="badge bg-secondary-subtle text-secondary border">{{ $myMessages->count() }} Pesan</span>
                </div>

                <div class="vstack gap-3">
                    @foreach($myMessages as $msg)
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">{{ $msg->subject }}</h6>
                                    <small class="text-muted">Dikirim pada: {{ $msg->created_at->format('d M Y, H:i') }} WIB</small>
                                </div>
                                <div>
                                    @if($msg->admin_reply)
                                        <span class="badge bg-success px-3 py-2">
                                            <i class="bi bi-check2-all me-1"></i> Dibalas oleh Admin
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark px-3 py-2">
                                            <i class="bi bi-clock-history me-1"></i> Menunggu Balasan Admin
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body p-3 p-md-4">
                                <!-- Pesan yang Dikirim User -->
                                <div class="p-3 bg-light rounded-3 mb-3 border">
                                    <span class="fw-bold small text-muted d-block mb-1">
                                        <i class="bi bi-person-fill text-secondary me-1"></i> Pesan Anda:
                                    </span>
                                    <div class="text-dark lh-base">
                                        {!! nl2br(e($msg->message)) !!}
                                    </div>
                                </div>

                                <!-- Balasan dari Admin -->
                                @if($msg->admin_reply)
                                    <div class="card border-success-subtle bg-success-subtle shadow-sm">
                                        <div class="card-body p-3 p-md-4">
                                            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                                <strong class="text-success">
                                                    <i class="bi bi-shield-check me-1"></i> Balasan dari Administrator:
                                                </strong>
                                                <small class="text-muted">
                                                    {{ $msg->replied_at ? $msg->replied_at->format('d M Y, H:i') : '' }} WIB
                                                </small>
                                            </div>
                                            <div class="p-3 bg-white rounded-3 text-dark lh-base border border-success-subtle">
                                                {!! nl2br(e($msg->admin_reply)) !!}
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-warning py-2 px-3 small mb-0 d-flex align-items-center gap-2">
                                        <i class="bi bi-hourglass-split fs-5"></i>
                                        <div>Pesan Anda telah sampai ke admin dan sedang menunggu tanggapan/balasan dari tim kami.</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
