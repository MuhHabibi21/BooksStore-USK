@extends('layouts.admin')

@section('title', 'Pesan Kontak Masuk - BookStore')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Pesan Masuk dari Pengguna</h3>
        <p class="text-muted small mb-0">Pesan yang dikirimkan user/pengunjung melalui formulir "Contact to Admin"</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;" class="text-center">No</th>
                    <th>Pengirim</th>
                    <th>Subjek</th>
                    <th>Isi Pesan</th>
                    <th>Status</th>
                    <th>Waktu Masuk</th>
                    <th class="text-center" style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $idx => $msg)
                    <tr class="{{ !$msg->is_read ? 'table-light fw-semibold' : '' }}">
                        <td class="text-center">{{ $messages->firstItem() + $idx }}</td>
                        <td>
                            <div>{{ $msg->name }}</div>
                            <small class="text-muted">{{ $msg->email }}</small>
                        </td>
                        <td>{{ $msg->subject }}</td>
                        <td class="small text-muted" style="max-width: 300px;">
                            {{ Str::limit($msg->message, 70) }}
                        </td>
                        <td>
                            @if($msg->admin_reply)
                                <span class="badge bg-success"><i class="bi bi-check2-all me-1"></i> Sudah Dibalas</span>
                            @elseif(!$msg->is_read)
                                <span class="badge bg-warning text-dark"><i class="bi bi-envelope-fill me-1"></i> Baru</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border">Dibaca</span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $msg->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.contacts.show', $msg->id) }}" class="btn btn-sm btn-outline-primary" title="Buka Pesan">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <form action="{{ route('admin.contacts.destroy', $msg->id) }}" method="POST" onsubmit="return confirm('Hapus pesan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Pesan">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada pesan yang masuk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($messages->hasPages())
        <div class="card-footer bg-white d-flex justify-content-center py-3">
            {{ $messages->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
