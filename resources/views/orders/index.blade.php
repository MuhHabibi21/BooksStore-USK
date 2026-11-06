@extends('layouts.app')

@section('title', 'Riwayat Pesanan Saya - BookStore')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pesanan Saya</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="bi bi-bag-check text-primary me-2"></i> Riwayat Pesanan Saya</h3>
        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">Belanja Buku Lagi</a>
    </div>

    @if($orders->count() > 0)
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Order</th>
                            <th>Tanggal</th>
                            <th>Total Tagihan</th>
                            <th>Metode Bayar</th>
                            <th>Status Pesanan</th>
                            <th>Status Bayar</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>
                                    <strong class="text-primary">{{ $order->order_number }}</strong>
                                    <div class="small text-muted">{{ $order->items->count() }} item buku</div>
                                </td>
                                <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                <td class="fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $order->payment_method }}</span>
                                </td>
                                <td>
                                    @if($order->status === 'completed')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($order->status === 'shipped')
                                        <span class="badge bg-info text-dark">Sedang Dikirim</span>
                                    @elseif($order->status === 'processing')
                                        <span class="badge bg-primary">Diproses</span>
                                    @elseif($order->status === 'cancelled')
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Menunggu Konfirmasi Admin</span>
                                    @endif
                                </td>
                                <td>
                                    @if($order->payment_status === 'paid')
                                        <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i> Sudah Dibayar</span>
                                    @else
                                        <span class="badge bg-secondary">Belum Dibayar</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                        @if($order->payment_status !== 'paid')
                                            <a href="{{ route('orders.payment', $order->id) }}" class="btn btn-sm btn-primary" title="Bayar Sekarang">
                                                <i class="bi bi-credit-card"></i> Bayar
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
                <div class="card-footer bg-white d-flex justify-content-center py-3">
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    @else
        <div class="card border-0 shadow-sm py-5 text-center">
            <div class="card-body">
                <i class="bi bi-bag-x text-muted" style="font-size: 4rem;"></i>
                <h4 class="fw-bold mt-3">Belum Ada Riwayat Pesanan</h4>
                <p class="text-muted small">Anda belum pernah melakukan pemesanan buku di BookStore.</p>
                <a href="{{ route('home') }}" class="btn btn-primary px-4 mt-2">Mulai Belanja</a>
            </div>
        </div>
    @endif
</div>
@endsection
