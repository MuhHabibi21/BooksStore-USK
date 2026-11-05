@extends('layouts.admin')

@section('title', 'Dashboard Admin - BookStore')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Dashboard Administrator</h3>
        <p class="text-muted small mb-0">Ringkasan statistik data buku, pesanan user, dan aktivitas sistem</p>
    </div>
    <div class="text-muted small">
        <i class="bi bi-calendar3 me-1"></i> {{ date('d F Y') }}
    </div>
</div>

<!-- Kartu Statistik -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="p-3 bg-primary-subtle text-primary rounded-3 me-3">
                    <i class="bi bi-journal-bookmark fs-3"></i>
                </div>
                <div>
                    <span class="text-muted small">Total Judul Buku</span>
                    <h4 class="fw-bold mb-0">{{ $totalBooks }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="p-3 bg-success-subtle text-success rounded-3 me-3">
                    <i class="bi bi-cart-check fs-3"></i>
                </div>
                <div>
                    <span class="text-muted small">Total Pesanan</span>
                    <h4 class="fw-bold mb-0">{{ $totalOrders }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="p-3 bg-info-subtle text-info rounded-3 me-3">
                    <i class="bi bi-people fs-3"></i>
                </div>
                <div>
                    <span class="text-muted small">User Terdaftar</span>
                    <h4 class="fw-bold mb-0">{{ $totalUsers }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="p-3 bg-warning-subtle text-warning rounded-3 me-3">
                    <i class="bi bi-chat-left-dots fs-3"></i>
                </div>
                <div>
                    <span class="text-muted small">Pesan Belum Dibaca</span>
                    <h4 class="fw-bold mb-0">{{ $unreadMessages }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Tabel Pesanan Terbaru -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-bag-check text-primary me-2"></i> Pesanan Buku Terbaru dari User</h6>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary btn-sm">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th>No. Order</th>
                            <th>Pemesan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $ord)
                            <tr>
                                <td>
                                    <strong class="text-primary">{{ $ord->order_number }}</strong>
                                    <div class="small text-muted">{{ $ord->created_at->format('d/m/Y H:i') }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $ord->recipient_name }}</div>
                                    <div class="small text-muted">{{ $ord->user->email ?? '-' }}</div>
                                </td>
                                <td class="fw-bold">Rp {{ number_format($ord->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    @if($ord->status === 'completed')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($ord->status === 'shipped')
                                        <span class="badge bg-info text-dark">Dikirim</span>
                                    @elseif($ord->status === 'processing')
                                        <span class="badge bg-primary">Diproses</span>
                                    @elseif($ord->status === 'cancelled')
                                        <span class="badge bg-danger">Batal</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $ord->id) }}" class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted small">Belum ada data pesanan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pesan Masuk Terbaru dari Form Kontak -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-envelope text-primary me-2"></i> Pesan Masuk Terbaru</h6>
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary btn-sm">Semua</a>
            </div>
            <div class="list-group list-group-flush">
                @forelse($recentMessages as $msg)
                    <a href="{{ route('admin.contacts.show', $msg->id) }}" class="list-group-item list-group-item-action p-3">
                        <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                            <h6 class="mb-0 fw-semibold {{ !$msg->is_read ? 'text-primary' : 'text-dark' }}">
                                {{ $msg->name }}
                            </h6>
                            <small class="text-muted">{{ $msg->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="small fw-semibold text-secondary text-truncate">{{ $msg->subject }}</div>
                        <p class="mb-0 small text-muted text-truncate">{{ $msg->message }}</p>
                    </a>
                @empty
                    <div class="p-4 text-center text-muted small">Belum ada pesan masuk dari user</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
