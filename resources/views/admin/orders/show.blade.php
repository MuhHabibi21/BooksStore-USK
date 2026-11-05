@extends('layouts.admin')

@section('title', 'Detail Pesanan ' . $order->order_number . ' - BookStore')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke List Pesanan
    </a>
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-0">Pesanan: {{ $order->order_number }}</h3>
            <p class="text-muted small mb-0">Dibuat pada: {{ $order->created_at->format('d F Y, H:i') }} WIB</p>
        </div>
        <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Cetak Pesanan
        </button>
    </div>
</div>

<div class="row g-4">
    <!-- Info Penerima & Item Pesanan -->
    <div class="col-lg-8">
        <!-- Informasi Pembeli & Pengiriman -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-person-lines-fill text-primary me-2"></i> Informasi Penerima & Pengiriman</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <span class="text-muted small d-block">Nama Penerima:</span>
                        <strong class="fs-6">{{ $order->recipient_name }}</strong>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted small d-block">Nomor Telepon:</span>
                        <strong>{{ $order->recipient_phone }}</strong>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted small d-block">Akun Pengguna Terdaftar:</span>
                        <span>{{ $order->user->name ?? '-' }} ({{ $order->user->email ?? '-' }})</span>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted small d-block">Metode Pembayaran:</span>
                        <strong class="text-primary">{{ $order->payment_method }}</strong>
                    </div>
                    <div class="col-12">
                        <span class="text-muted small d-block">Alamat Lengkap Pengiriman:</span>
                        <div class="p-2 bg-light rounded small mt-1">{!! nl2br(e($order->delivery_address)) !!}</div>
                    </div>
                    @if($order->notes)
                        <div class="col-12">
                            <span class="text-muted small d-block">Catatan Pemesan:</span>
                            <div class="p-2 bg-warning-subtle text-dark rounded small mt-1">{{ $order->notes }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Daftar Buku yang Dipesan -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-journal-check text-primary me-2"></i> Item Buku yang Dipesan</h6>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Judul Buku</th>
                            <th class="text-end">Harga</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $idx => $item)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>
                                    <strong>{{ $item->book->title ?? 'Buku Dihapus' }}</strong>
                                    @if($item->book)
                                        <div class="small text-muted">{{ $item->book->author }} ({{ $item->book->category->name ?? '-' }})</div>
                                    @endif
                                </td>
                                <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="text-center fw-bold">{{ $item->quantity }}</td>
                                <td class="text-end fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Total Nilai Pesanan:</td>
                            <td class="text-end fw-bold text-primary fs-5">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Panel Update Status Pesanan & Pembayaran -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-sliders text-primary me-2"></i> Kelola Status Pesanan</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="status" class="form-label fw-semibold">Status Pengiriman/Pesanan:</label>
                        <select name="status" id="status" class="form-select">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Menunggu (Pending)</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Sedang Diproses</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Sedang Dikirim</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Selesai (Diterima)</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="payment_status" class="form-label fw-semibold">Status Pembayaran:</label>
                        <select name="payment_status" id="payment_status" class="form-select">
                            <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Belum Dibayar (Unpaid)</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Sudah Lunas (Paid)</option>
                        </select>
                        <small class="text-muted">Untuk Payment at Delivery, ubah ke <strong>Sudah Lunas</strong> setelah kurir menerima uang dari pembeli.</small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                        <i class="bi bi-check2-circle me-1"></i> Simpan Perubahan Status
                    </button>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body small">
                <strong class="text-muted d-block mb-2">Riwayat & Log Sistem:</strong>
                <div>Pesanan ID: #{{ $order->id }}</div>
                <div>Waktu Pembuatan: {{ $order->created_at->format('Y-m-d H:i:s') }}</div>
                <div>Pembaruan Terakhir: {{ $order->updated_at->format('Y-m-d H:i:s') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
