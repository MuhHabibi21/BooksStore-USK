@extends('layouts.admin')

@section('title', 'List Pesanan User - BookStore')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">List Pesanan Buku User</h3>
        <p class="text-muted small mb-0">Monitoring seluruh transaksi pesanan buku dari berbagai pengguna</p>
    </div>
</div>

<!-- Filter & Pencarian Pesanan -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-7">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="q" class="form-control" placeholder="Cari no. order atau nama penerima..." value="{{ request('q') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status Pesanan</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu (Pending)</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Sedang Diproses</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Sedang Dikirim</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
                @if(request('q') || request('status'))
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary" title="Reset filter"><i class="bi bi-arrow-counterclockwise"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>No. Order</th>
                    <th>User & Pemesan</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Metode Pembayaran</th>
                    <th>Status Pesanan</th>
                    <th>Status Bayar</th>
                    <th class="text-center" style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $ord)
                    <tr>
                        <td>
                            <strong class="text-primary">{{ $ord->order_number }}</strong>
                            <div class="small text-muted">{{ $ord->items->count() }} item buku</div>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $ord->recipient_name }}</div>
                            <small class="text-muted d-block"><i class="bi bi-person me-1"></i> Akun: {{ $ord->user->name ?? 'Guest' }} ({{ $ord->user->email ?? '-' }})</small>
                            <small class="text-muted"><i class="bi bi-telephone me-1"></i> {{ $ord->recipient_phone }}</small>
                        </td>
                        <td>{{ $ord->created_at->format('d/m/Y H:i') }}</td>
                        <td class="fw-bold">Rp {{ number_format($ord->total_amount, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $ord->payment_method }}</span>
                        </td>
                        <td>
                            @if($ord->status === 'completed')
                                <span class="badge bg-success">Selesai</span>
                            @elseif($ord->status === 'shipped')
                                <span class="badge bg-info text-dark">Sedang Dikirim</span>
                            @elseif($ord->status === 'processing')
                                <span class="badge bg-primary">Diproses</span>
                            @elseif($ord->status === 'cancelled')
                                <span class="badge bg-danger">Dibatalkan</span>
                            @else
                                <span class="badge bg-warning text-dark">Menunggu (Pending)</span>
                            @endif
                        </td>
                        <td>
                            @if($ord->payment_status === 'paid')
                                <span class="badge bg-success">Lunas</span>
                            @else
                                <span class="badge bg-secondary">Belum Dibayar</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.orders.show', $ord->id) }}" class="btn btn-sm btn-outline-primary" title="Detail & Kelola Pesanan">
                                <i class="bi bi-eye me-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Belum ada data pesanan yang cocok dengan kriteria filter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
        <div class="card-footer bg-white d-flex justify-content-center py-3">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
