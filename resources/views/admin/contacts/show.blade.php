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

        <div class="p-3 bg-light rounded-3 mb-4">
            <h6 class="fw-semibold text-muted mb-2">Isi Pesan:</h6>
            <div class="fs-6 text-dark lh-lg">
                {!! nl2br(e($message->message)) !!}
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="mailto:{{ $message->email }}?subject=Re: {{ urlencode($message->subject) }}" class="btn btn-primary">
                <i class="bi bi-reply-fill me-1"></i> Balas melalui Email
            </a>
            <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary">
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection
