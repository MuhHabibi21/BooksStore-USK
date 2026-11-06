@extends('layouts.admin')

@section('title', 'Detail Pesan Masuk - BookStore')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Kotak Masuk
    </a>
    <h3 class="fw-bold mb-0">Detail Pesan Masuk</h3>
    <p class="text-muted small mb-0">Diterima pada {{ $message->created_at->format('d F Y, H:i') }} WIB</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4 p-md-5">
        <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-4">
            <div>
                <h4 class="fw-bold mb-1">{{ $message->subject }}</h4>
                <div class="text-muted small">
                    Dari: <strong>{{ $message->name }}</strong> &lt;{{ $message->email }}&gt;
                    @if($message->user)
                        <span class="badge bg-primary-subtle text-primary ms-2">Akun Terdaftar</span>
                    @endif
                </div>
            </div>
            <div>
                <form action="{{ route('admin.contacts.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Hapus pesan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-trash me-1"></i> Hapus Pesan
                    </button>
                </form>
            </div>
        </div>

        <div class="p-3 bg-light rounded-3 mb-4 border">
            <h6 class="fw-semibold text-muted mb-2"><i class="bi bi-chat-quote-fill me-1"></i> Isi Pesan Pengguna:</h6>
            <div class="fs-6 text-dark lh-lg">
                {!! nl2br(e($message->message)) !!}
            </div>
        </div>

        @if($message->admin_reply)
            <!-- Kotak Balasan yang Sudah Dikirim -->
            <div class="card border-success-subtle bg-success-subtle mb-4 shadow-sm">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                        <h6 class="fw-bold text-success mb-0">
                            <i class="bi bi-check2-circle me-1"></i> Balasan yang Sudah Dikirim ke Pengguna:
                        </h6>
                        <small class="text-muted">
                            Dikirim pada: <strong>{{ $message->replied_at ? $message->replied_at->format('d F Y, H:i') : '-' }} WIB</strong>
                        </small>
                    </div>
                    <div class="p-3 bg-white rounded-3 text-dark lh-base border border-success-subtle">
                        {!! nl2br(e($message->admin_reply)) !!}
                    </div>
                </div>
            </div>
        @endif

        <!-- Formulir Balas Pesan Admin -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="fw-bold mb-0 text-primary">
                    <i class="bi bi-reply-all-fill me-1"></i> {{ $message->admin_reply ? 'Perbarui Pesan Balasan Admin' : 'Kirim Pesan Balasan ke Pengguna' }}
                </h6>
            </div>
            <div class="card-body p-3 p-md-4">
                <form action="{{ route('admin.contacts.reply', $message->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="admin_reply" class="form-label fw-semibold">Tulis Balasan:</label>
                        <textarea name="admin_reply" id="admin_reply" class="form-control" rows="4" required placeholder="Tuliskan respon atau jawaban Anda untuk pengguna ini...">{{ old('admin_reply', $message->admin_reply) }}</textarea>
                        <small class="text-muted">Pesan balasan ini akan langsung tampil di halaman <strong>Kontak Admin</strong> milik pengguna saat mereka membuka web.</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4 fw-bold">
                            <i class="bi bi-send-fill me-1"></i> {{ $message->admin_reply ? 'Kirim Ulang / Perbarui Balasan' : 'Kirim Balasan' }}
                        </button>
                        <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary">
                            Kembali ke List
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
