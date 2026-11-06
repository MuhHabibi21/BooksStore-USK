@extends('layouts.app')

@section('title', 'Pembayaran Pesanan ' . $order->order_number . ' - BookStore')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-decoration-none">Pesanan Saya</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.show', $order->id) }}" class="text-decoration-none">{{ $order->order_number }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pembayaran</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header Tagihan & Countdown -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4 text-center bg-primary text-white rounded-3">
                    <span class="badge bg-warning text-dark px-3 py-2 fw-semibold mb-2">
                        <i class="bi bi-clock-history me-1"></i> Menunggu Pembayaran
                    </span>
                    <h5 class="fw-normal mb-1 opacity-75">Total Tagihan Pembayaran:</h5>
                    <h2 class="fw-bold mb-3">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h2>
                    <div class="d-inline-flex align-items-center gap-2 bg-white bg-opacity-10 px-3 py-2 rounded-pill small">
                        <i class="bi bi-receipt"></i>
                        <span>No. Pesanan: <strong>{{ $order->order_number }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- Card Metode Pembayaran Interaktif -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-wallet2 text-primary me-2"></i> Pilih Cara Pembayaran
                    </h5>
                    <small class="text-muted">Pilih metode di bawah ini lalu klik tombol konfirmasi bayar untuk menyelesaikan pesanan.</small>
                </div>
                <div class="card-body p-4">
                    <!-- Nav Tabs Pilihan Pembayaran -->
                    <ul class="nav nav-pills nav-fill mb-4 p-1 bg-light rounded-3" id="paymentTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold py-2" id="qris-tab" data-bs-toggle="pill" data-bs-target="#qris-pane" type="button" role="tab">
                                <i class="bi bi-qr-code me-1"></i> QRIS
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold py-2" id="va-tab" data-bs-toggle="pill" data-bs-target="#va-pane" type="button" role="tab">
                                <i class="bi bi-bank me-1"></i> Virtual Account
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold py-2" id="debit-tab" data-bs-toggle="pill" data-bs-target="#debit-pane" type="button" role="tab">
                                <i class="bi bi-credit-card me-1"></i> Kartu Debit
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Contents -->
                    <div class="tab-content" id="paymentTabsContent">
                        <!-- 1. TAB QRIS -->
                        <div class="tab-pane fade show active" id="qris-pane" role="tabpanel">
                            <div class="text-center py-2">
                                <div class="p-3 border rounded-3 d-inline-block bg-white shadow-sm mb-3 text-center" style="max-width: 320px;">
                                    <div class="d-flex justify-content-between align-items-center mb-2 px-1 border-bottom pb-2">
                                        <span class="fw-bold text-danger fs-5">QRIS</span>
                                        <span class="text-muted small">STANDAR NASIONAL</span>
                                    </div>
                                    <div class="small fw-semibold text-dark mb-2">BookStore Official Store</div>
                                    
                                    <!-- QR Code SVG Representation -->
                                    <div class="p-2 bg-light rounded d-flex justify-content-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" width="180" height="180">
                                            <!-- Background -->
                                            <rect width="200" height="200" fill="#ffffff"/>
                                            <!-- Corner Squares (Position Detection Patterns) -->
                                            <rect x="15" y="15" width="50" height="50" fill="#000000"/>
                                            <rect x="25" y="25" width="30" height="30" fill="#ffffff"/>
                                            <rect x="33" y="33" width="14" height="14" fill="#000000"/>
                                            
                                            <rect x="135" y="15" width="50" height="50" fill="#000000"/>
                                            <rect x="145" y="25" width="30" height="30" fill="#ffffff"/>
                                            <rect x="153" y="33" width="14" height="14" fill="#000000"/>
                                            
                                            <rect x="15" y="135" width="50" height="50" fill="#000000"/>
                                            <rect x="25" y="145" width="30" height="30" fill="#ffffff"/>
                                            <rect x="33" y="153" width="14" height="14" fill="#000000"/>
                                            
                                            <!-- Data Pixels Simulation -->
                                            <rect x="75" y="20" width="10" height="10" fill="#000000"/>
                                            <rect x="95" y="20" width="10" height="10" fill="#000000"/>
                                            <rect x="115" y="20" width="10" height="10" fill="#000000"/>
                                            <rect x="85" y="40" width="10" height="10" fill="#000000"/>
                                            <rect x="105" y="40" width="10" height="10" fill="#000000"/>
                                            <rect x="20" y="75" width="10" height="10" fill="#000000"/>
                                            <rect x="40" y="75" width="10" height="10" fill="#000000"/>
                                            <rect x="60" y="75" width="10" height="10" fill="#000000"/>
                                            <rect x="80" y="75" width="10" height="10" fill="#000000"/>
                                            <rect x="100" y="75" width="10" height="10" fill="#000000"/>
                                            <rect x="120" y="75" width="10" height="10" fill="#000000"/>
                                            <rect x="140" y="75" width="10" height="10" fill="#000000"/>
                                            <rect x="160" y="75" width="10" height="10" fill="#000000"/>
                                            <rect x="180" y="75" width="10" height="10" fill="#000000"/>
                                            <rect x="30" y="95" width="10" height="10" fill="#000000"/>
                                            <rect x="50" y="95" width="10" height="10" fill="#000000"/>
                                            <rect x="70" y="95" width="10" height="10" fill="#000000"/>
                                            <rect x="90" y="95" width="20" height="20" fill="#0d6efd" rx="4"/>
                                            <rect x="120" y="95" width="10" height="10" fill="#000000"/>
                                            <rect x="150" y="95" width="10" height="10" fill="#000000"/>
                                            <rect x="170" y="95" width="10" height="10" fill="#000000"/>
                                            <rect x="75" y="125" width="10" height="10" fill="#000000"/>
                                            <rect x="95" y="125" width="10" height="10" fill="#000000"/>
                                            <rect x="115" y="125" width="10" height="10" fill="#000000"/>
                                            <rect x="135" y="125" width="10" height="10" fill="#000000"/>
                                            <rect x="155" y="125" width="10" height="10" fill="#000000"/>
                                            <rect x="75" y="145" width="10" height="10" fill="#000000"/>
                                            <rect x="105" y="145" width="10" height="10" fill="#000000"/>
                                            <rect x="125" y="145" width="10" height="10" fill="#000000"/>
                                            <rect x="145" y="145" width="10" height="10" fill="#000000"/>
                                            <rect x="165" y="145" width="10" height="10" fill="#000000"/>
                                            <rect x="75" y="165" width="10" height="10" fill="#000000"/>
                                            <rect x="95" y="165" width="10" height="10" fill="#000000"/>
                                            <rect x="115" y="165" width="10" height="10" fill="#000000"/>
                                            <rect x="135" y="165" width="10" height="10" fill="#000000"/>
                                            <rect x="175" y="165" width="10" height="10" fill="#000000"/>
                                        </svg>
                                    </div>
                                    <div class="mt-2 text-muted small">NMID: ID1020261106889</div>
                                </div>

                                <div class="alert alert-info text-start small mb-4">
                                    <h6 class="fw-bold mb-1"><i class="bi bi-info-circle me-1"></i> Cara Pembayaran QRIS:</h6>
                                    <ol class="ps-3 mb-0">
                                        <li>Buka aplikasi <strong>GoPay, OVO, Dana, ShopeePay, BCA Mobile, Livin Mandiri</strong> atau bank apa pun.</li>
                                        <li>Pilih fitur <strong>Scan / Bayar QR</strong> lalu scan kode di atas.</li>
                                        <li>Pastikan nominal pembayaran adalah <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>.</li>
                                        <li>Setelah transfer berhasil, klik tombol <strong>Bayar Sekarang via QRIS</strong> di bawah ini.</li>
                                    </ol>
                                </div>

                                <form action="{{ route('orders.pay', $order->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="payment_method" value="QRIS">
                                    <button type="submit" class="btn btn-success btn-lg w-100 py-3 fw-bold shadow-sm">
                                        <i class="bi bi-check-circle-fill me-2"></i> Bayar Sekarang via QRIS (Konfirmasi Lunas)
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- 2. TAB VIRTUAL ACCOUNT -->
                        <div class="tab-pane fade" id="va-pane" role="tabpanel">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-muted small">Pilih Bank Virtual Account:</label>
                                <div class="row g-2 mb-3">
                                    <div class="col-6 col-md-3">
                                        <button type="button" class="btn btn-outline-primary w-100 fw-bold py-2 btn-bank active" data-bank="BCA" data-code="8801">
                                            BCA
                                        </button>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <button type="button" class="btn btn-outline-primary w-100 fw-bold py-2 btn-bank" data-bank="Mandiri" data-code="8902">
                                            Mandiri
                                        </button>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <button type="button" class="btn btn-outline-primary w-100 fw-bold py-2 btn-bank" data-bank="BRI" data-code="8003">
                                            BRI
                                        </button>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <button type="button" class="btn btn-outline-primary w-100 fw-bold py-2 btn-bank" data-bank="BNI" data-code="8804">
                                            BNI
                                        </button>
                                    </div>
                                </div>

                                <div class="p-4 border rounded-3 bg-light text-center mb-3">
                                    <span class="text-muted small d-block mb-1">Nomor Rekening Virtual Account (<span id="bankNameDisplay">BCA</span>):</span>
                                    <h3 class="fw-bold text-primary font-monospace tracking-wider mb-2" id="vaNumberDisplay">
                                        8801 0812 {{ sprintf('%04d', $order->user_id) }} {{ sprintf('%04d', $order->id) }}
                                    </h3>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="copyVA()">
                                        <i class="bi bi-clipboard me-1"></i> <span id="copyBtnText">Salin Nomor VA</span>
                                    </button>
                                </div>

                                <div class="alert alert-secondary small mb-4">
                                    <h6 class="fw-bold mb-1"><i class="bi bi-wallet me-1"></i> Panduan Transfer VA:</h6>
                                    <p class="mb-0">Transfer tepat <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong> melalui menu Virtual Account / Transfer Antar Rekening di ATM atau Mobile Banking Anda. Setelah selesai transfer, klik tombol konfirmasi di bawah ini.</p>
                                </div>

                                <form action="{{ route('orders.pay', $order->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="payment_method" id="vaMethodInput" value="Transfer Bank (BCA Virtual Account)">
                                    <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold shadow-sm">
                                        <i class="bi bi-check-circle-fill me-2"></i> Saya Sudah Transfer / Bayar Virtual Account
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- 3. TAB KARTU DEBIT ONLINE -->
                        <div class="tab-pane fade" id="debit-pane" role="tabpanel">
                            <!-- Visual Kartu Debit -->
                            <div class="card bg-dark text-white rounded-3 p-4 mb-4 shadow" style="background: linear-gradient(135deg, #1e3a8a, #0284c7);">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <span class="fs-5 fw-bold">DEBIT CARD</span>
                                    <i class="bi bi-credit-card-2-front fs-2"></i>
                                </div>
                                <div class="fs-4 font-monospace tracking-wide mb-3" id="cardDisplayNumber">
                                    •••• •••• •••• ••••
                                </div>
                                <div class="d-flex justify-content-between small opacity-75">
                                    <div>
                                        <span class="d-block" style="font-size: 0.7rem;">PEMEGANG KARTU</span>
                                        <span class="fw-bold text-uppercase" id="cardDisplayName">{{ $order->recipient_name }}</span>
                                    </div>
                                    <div>
                                        <span class="d-block" style="font-size: 0.7rem;">BERLAKU S/D</span>
                                        <span class="fw-bold" id="cardDisplayExpiry">12/28</span>
                                    </div>
                                </div>
                            </div>

                            <form action="{{ route('orders.pay', $order->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="payment_method" value="Kartu Debit Online">

                                <div class="row g-3 mb-4">
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Nomor Kartu Debit</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-credit-card"></i></span>
                                            <input type="text" class="form-control" placeholder="4111 2222 3333 4444" maxlength="19" required oninput="formatCardNumber(this)">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Masa Berlaku (MM/YY)</label>
                                        <input type="text" class="form-control" placeholder="MM/YY" maxlength="5" required oninput="formatExpiry(this)">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">CVV (3 Digit)</label>
                                        <input type="password" class="form-control" placeholder="123" maxlength="3" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold shadow-sm">
                                    <i class="bi bi-shield-check me-2"></i> Bayar Rp {{ number_format($order->total_amount, 0, ',', '.') }} Sekarang
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white text-center py-3 border-top">
                    <a href="{{ route('orders.show', $order->id) }}" class="text-muted text-decoration-none small">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail Faktur Pesanan (Bayar Nanti)
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Penanganan Switch Bank Virtual Account
    const bankButtons = document.querySelectorAll('.btn-bank');
    const bankNameDisplay = document.getElementById('bankNameDisplay');
    const vaNumberDisplay = document.getElementById('vaNumberDisplay');
    const vaMethodInput = document.getElementById('vaMethodInput');
    const userId = "{{ sprintf('%04d', $order->user_id) }}";
    const orderId = "{{ sprintf('%04d', $order->id) }}";

    bankButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            bankButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const bank = this.getAttribute('data-bank');
            const code = this.getAttribute('data-code');

            bankNameDisplay.textContent = bank;
            vaNumberDisplay.textContent = `${code} 0812 ${userId} ${orderId}`;
            vaMethodInput.value = `Transfer Bank (${bank} Virtual Account)`;
        });
    });

    // Fitur Salin VA
    function copyVA() {
        const textToCopy = vaNumberDisplay.textContent.replace(/\s+/g, '');
        navigator.clipboard.writeText(textToCopy).then(() => {
            const btnText = document.getElementById('copyBtnText');
            btnText.textContent = 'Tersalin!';
            setTimeout(() => {
                btnText.textContent = 'Salin Nomor VA';
            }, 2000);
        });
    }

    // Format Input Kartu Debit
    function formatCardNumber(input) {
        let value = input.value.replace(/\D/g, '');
        let formatted = value.match(/.{1,4}/g)?.join(' ') || value;
        input.value = formatted.substring(0, 19);
        document.getElementById('cardDisplayNumber').textContent = input.value || '•••• •••• •••• ••••';
    }

    function formatExpiry(input) {
        let value = input.value.replace(/\D/g, '');
        if (value.length >= 2) {
            input.value = value.substring(0, 2) + '/' + value.substring(2, 4);
        } else {
            input.value = value;
        }
        document.getElementById('cardDisplayExpiry').textContent = input.value || 'MM/YY';
    }

    // Set tab aktif sesuai pilihan saat checkout
    document.addEventListener('DOMContentLoaded', function () {
        const chosenMethod = "{{ $order->payment_method }}";
        if (chosenMethod.includes('Transfer') || chosenMethod.includes('Virtual Account')) {
            const vaTab = new bootstrap.Tab(document.getElementById('va-tab'));
            vaTab.show();
        } else if (chosenMethod.includes('Debit') || chosenMethod.includes('Kartu')) {
            const debitTab = new bootstrap.Tab(document.getElementById('debit-tab'));
            debitTab.show();
        } else {
            const qrisTab = new bootstrap.Tab(document.getElementById('qris-tab'));
            qrisTab.show();
        }
    });
</script>
@endpush
