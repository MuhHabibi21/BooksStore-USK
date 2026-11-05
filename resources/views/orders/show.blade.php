@extends('layouts.app')

@section('title', 'Faktur Pesanan ' . $order->order_number . ' - BookStore')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb & Print Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-decoration-none">Pesanan Saya</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $order->order_number }}</li>
            </ol>
        </nav>
        <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Cetak Faktur / Struk
        </button>
    </div>

    <div class="card border-0 shadow-sm p-4 p-md-5">
        <!-- Header Faktur -->
        <div class="row align-items-center pb-4 border-bottom mb-4">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-book-half fs-2 text-primary"></i>
                    <h3 class="fw-bold mb-0">BookStore</h3>
                </div>
                <p class="text-muted small mb-0">
                    Platform Pembelian Buku Terpercaya<br>
                    Jl. MT. Haryono Kavling 52, Jakarta Selatan
                </p>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <h4 class="fw-bold text-primary mb-1">FAKTUR PESANAN</h4>
                <div class="fw-bold">{{ $order->order_number }}</div>
                <div class="text-muted small">Tanggal: {{ $order->created_at->format('d F Y, H:i') }} WIB</div>
            </div>
        </div>

        <!-- Info Penerima & Status -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <h6 class="fw-bold text-uppercase text-muted small">Tujuan Pengiriman:</h6>
                <div class="fw-bold fs-6">{{ $order->recipient_name }}</div>
                <div class="text-muted small"><i class="bi bi-telephone me-1"></i> {{ $order->recipient_phone }}</div>
                <div class="text-secondary small mt-1">{!! nl2br(e($order->delivery_address)) !!}</div>
                @if($order->notes)
                    <div class="mt-2 p-2 bg-light rounded small">
                        <strong>Catatan:</strong> {{ $order->notes }}
                    </div>
                @endif
            </div>
            <div class="col-md-6 text-md-end">
                <h6 class="fw-bold text-uppercase text-muted small">Status Transaksi:</h6>
                <div class="mb-2">
                    <span class="text-muted small me-2">Status Pesanan:</span>
                    @if($order->status === 'completed')
                        <span class="badge bg-success">Selesai</span>
                    @elseif($order->status === 'shipped')
                        <span class="badge bg-info text-dark">Sedang Dikirim</span>
                    @elseif($order->status === 'processing')
                        <span class="badge bg-primary">Diproses</span>
                    @elseif($order->status === 'cancelled')
                        <span class="badge bg-danger">Dibatalkan</span>
                    @else
                        <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                    @endif
                </div>

                <div class="mb-2">
                    <span class="text-muted small me-2">Metode Pembayaran:</span>
                    <strong class="text-dark">{{ $order->payment_method }}</strong>
                </div>

                <div>
                    <span class="text-muted small me-2">Status Pembayaran:</span>
                    @if($order->payment_status === 'paid')
                        <span class="badge bg-success">Lunas</span>
                    @else
                        <span class="badge bg-secondary">Belum Dibayar (Saat Antar)</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tabel Item Pesanan -->
        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th>Judul Buku</th>
                        <th class="text-end" style="width: 140px;">Harga Satuan</th>
                        <th class="text-center" style="width: 90px;">Jumlah</th>
                        <th class="text-end" style="width: 160px;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $idx => $item)
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td>
                                <strong>{{ $item->book->title ?? 'Buku Dihapus' }}</strong>
                                @if($item->book)
                                    <div class="text-muted small">Penulis: {{ $item->book->author }} | Penerbit: {{ $item->book->publisher }}</div>
                                @endif
                            </td>
                            <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end fw-bold">Total Pembayaran:</td>
                        <td class="text-end fw-bold fs-5 text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="alert alert-secondary small mb-0">
            <strong>Keterangan Pembayaran:</strong> Pesanan ini menggunakan skema <em>Payment at Delivery</em> di mana pembayaran dapat diserahkan langsung kepada kurir sebelum serah terima paket buku atau transfer saat pengiriman.
        </div>
    </div>
</div>
@endsection
