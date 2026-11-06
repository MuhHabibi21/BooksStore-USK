@extends('layouts.app')

@section('title', 'Keranjang Belanja - BookStore')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">Keranjang Belanja</li>
        </ol>
    </nav>

    <h3 class="fw-bold mb-4"><i class="bi bi-cart3 text-primary me-2"></i> Keranjang Belanja Anda</h3>

    @if(count($cart) > 0)
        <!-- Form Checkout Utama yang membawa item terpilih (Checkbox) -->
        <form action="{{ route('checkout.index') }}" method="GET" id="checkoutForm">
            <div class="row g-4">
                <!-- Tabel Item Keranjang -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-3">
                        <!-- Bar Pilih Semua (Select All) -->
                        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                            <div class="form-check d-flex align-items-center mb-0">
                                <input class="form-check-input me-2 fs-5 mt-0" type="checkbox" id="selectAllCheckbox" checked>
                                <label class="form-check-label fw-bold text-dark cursor-pointer user-select-none" for="selectAllCheckbox">
                                    Pilih Semua (<span id="totalItemsCount">{{ count($cart) }}</span> Buku)
                                </label>
                            </div>
                            <button type="button" class="btn btn-outline-danger btn-sm" id="btnDeleteSelected" title="Hapus item yang dipilih">
                                <i class="bi bi-trash me-1"></i> Hapus Terpilih
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light small text-muted">
                                    <tr>
                                        <th scope="col" style="width: 45px;">Pilih</th>
                                        <th scope="col" style="min-width: 240px;">Buku</th>
                                        <th scope="col" style="width: 130px;">Harga Satuan</th>
                                        <th scope="col" style="width: 140px;">Jumlah</th>
                                        <th scope="col" style="width: 140px;">Subtotal</th>
                                        <th scope="col" class="text-center" style="width: 60px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart as $id => $item)
                                        <tr id="cart-row-{{ $id }}" class="cart-item-row" data-id="{{ $id }}" data-price="{{ $item['price'] }}" data-stock="{{ $item['stock'] ?? 99 }}">
                                            <!-- Checkbox Item -->
                                            <td class="text-center">
                                                <input class="form-check-input item-checkbox fs-5" type="checkbox" name="selected_items[]" value="{{ $id }}" id="check-{{ $id }}" checked>
                                            </td>

                                            <!-- Info Buku -->
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if(!empty($item['cover_image']))
                                                        <img src="{{ asset('storage/' . $item['cover_image']) }}" alt="{{ $item['title'] }}" class="rounded me-3 border" style="width: 50px; height: 70px; object-fit: cover;">
                                                    @else
                                                        <div class="rounded bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 70px; font-size: 1.2rem;">
                                                            <i class="bi bi-book"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <a href="{{ route('books.show', $id) }}" class="fw-bold text-dark text-decoration-none d-block">
                                                            {{ $item['title'] }}
                                                        </a>
                                                        <small class="text-muted d-block">{{ $item['author'] ?? '' }}</small>
                                                        <small class="text-success">Sisa stok: {{ $item['stock'] ?? 99 }}</small>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Harga Satuan -->
                                            <td class="fw-semibold text-secondary">
                                                Rp {{ number_format($item['price'], 0, ',', '.') }}
                                            </td>

                                            <!-- Jumlah / Kuantitas Stepper [-] Qty [+] -->
                                            <td>
                                                <div class="input-group input-group-sm" style="width: 110px;">
                                                    <button type="button" class="btn btn-outline-secondary btn-decrease px-2" data-id="{{ $id }}">
                                                        <i class="bi bi-dash"></i>
                                                    </button>
                                                    <input type="number" 
                                                           class="form-control text-center quantity-input fw-bold px-1" 
                                                           id="qty-input-{{ $id }}" 
                                                           data-id="{{ $id }}"
                                                           value="{{ $item['quantity'] }}" 
                                                           min="0" 
                                                           max="{{ $item['stock'] ?? 99 }}">
                                                    <button type="button" class="btn btn-outline-secondary btn-increase px-2" data-id="{{ $id }}">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </div>
                                            </td>

                                            <!-- Subtotal -->
                                            <td class="fw-bold text-primary item-subtotal" id="subtotal-{{ $id }}">
                                                Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                            </td>

                                            <!-- Tombol Hapus -->
                                            <td class="text-center">
                                                <button type="button" class="btn btn-outline-danger btn-sm btn-delete-single" data-id="{{ $id }}" data-title="{{ $item['title'] }}" title="Hapus dari keranjang">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer bg-white d-flex justify-content-between align-items-center p-3 border-top">
                            <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-arrow-left me-1"></i> Tambah Buku Lainnya
                            </a>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="btnClearAllCart">
                                <i class="bi bi-x-circle me-1"></i> Kosongkan Keranjang
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Belanja & Checkout -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm sticky-top" style="top: 80px;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3 border-bottom pb-2">Ringkasan Belanja</h5>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Item Dipilih</span>
                                <span class="fw-semibold" id="selectedItemsText">
                                    {{ array_sum(array_column($cart, 'quantity')) }} buku
                                </span>
                            </div>

                            <div class="d-flex justify-content-between mb-3 border-top pt-3">
                                <span class="fs-5 fw-bold">Total Pembayaran</span>
                                <span class="fs-5 fw-bold text-primary" id="totalCheckoutPrice">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="alert alert-info py-2 small mb-3">
                                <i class="bi bi-truck me-1"></i> Metode Pembayaran: <strong>Payment at Delivery</strong> (COD / Bayar saat barang sampai).
                            </div>

                            @auth
                                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" id="btnCheckoutSubmit">
                                    Lanjut ke Pembayaran (<span id="checkoutBtnCount">{{ array_sum(array_column($cart, 'quantity')) }}</span>) <i class="bi bi-arrow-right ms-1"></i>
                                </button>
                            @else
                                <button type="button" class="btn btn-primary w-100 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#loginPromptModal">
                                    Lanjut ke Pembayaran <i class="bi bi-arrow-right ms-1"></i>
                                </button>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @else
        <div class="card border-0 shadow-sm py-5 text-center">
            <div class="card-body">
                <i class="bi bi-cart-x text-muted" style="font-size: 4rem;"></i>
                <h4 class="fw-bold mt-3">Keranjang Belanja Anda Masih Kosong</h4>
                <p class="text-muted small">Anda belum menambahkan koleksi buku apa pun ke dalam keranjang.</p>
                <a href="{{ route('home') }}" class="btn btn-primary px-4 mt-2">
                    <i class="bi bi-grid me-1"></i> Jelajahi Katalog Buku
                </a>
            </div>
        </div>
    @endif
</div>

<!-- MODAL KONFIRMASI HAPUS (Sesuai Gambar 2: "Apakah Anda yakin untuk menghapus?") -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-body text-center p-4">
                <div class="mb-3 text-warning">
                    <i class="bi bi-trash3-fill" style="font-size: 2.8rem;"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Apakah Anda yakin untuk menghapus?</h5>
                <p class="text-muted small mb-0" id="deleteModalSubtitle">Item buku ini akan dikeluarkan dari keranjang belanja Anda.</p>
            </div>
            <div class="modal-footer border-top p-0 d-flex">
                <button type="button" class="btn btn-light py-2 flex-fill border-end rounded-0 fw-semibold text-secondary" id="btnCancelDelete" data-bs-dismiss="modal">
                    Tidak
                </button>
                <button type="button" class="btn btn-light py-2 flex-fill rounded-0 fw-bold text-danger" id="btnConfirmDelete">
                    Ya
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Form tersembunyi untuk proses hapus standar / fallback -->
<form id="hiddenRemoveForm" method="POST" style="display: none;">
    @csrf
</form>
<form id="hiddenClearForm" action="{{ route('cart.clear') }}" method="POST" style="display: none;">
    @csrf
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = '{{ csrf_token() }}';
    const deleteModalEl = document.getElementById('deleteConfirmModal');
    const deleteModal = new bootstrap.Modal(deleteModalEl);
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const checkoutBtn = document.getElementById('btnCheckoutSubmit');
    const checkoutBtnCount = document.getElementById('checkoutBtnCount');
    const selectedItemsText = document.getElementById('selectedItemsText');
    const totalCheckoutPrice = document.getElementById('totalCheckoutPrice');

    let pendingDeleteId = null;
    let pendingDeleteType = 'single'; // 'single' atau 'selected' atau 'all'

    // Format Rupiah
    function formatRupiah(number) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
    }

    // Kalkulasi Total Checkbox Terpilih
    function updateCartCalculation() {
        let totalCheckedPrice = 0;
        let totalCheckedQty = 0;
        let checkedCount = 0;
        const totalRows = document.querySelectorAll('.cart-item-row').length;

        document.querySelectorAll('.cart-item-row').forEach(row => {
            const id = row.getAttribute('data-id');
            const checkbox = document.getElementById('check-' + id);
            const price = parseFloat(row.getAttribute('data-price')) || 0;
            const input = document.getElementById('qty-input-' + id);
            const qty = parseInt(input.value) || 0;

            if (checkbox && checkbox.checked) {
                checkedCount++;
                totalCheckedQty += qty;
                totalCheckedPrice += (price * qty);
            }
        });

        // Update Text Tampilan
        if (selectedItemsText) selectedItemsText.textContent = totalCheckedQty + ' buku';
        if (totalCheckoutPrice) totalCheckoutPrice.textContent = formatRupiah(totalCheckedPrice);
        if (checkoutBtnCount) checkoutBtnCount.textContent = totalCheckedQty + ' buku';

        // Update Status Button Checkout
        if (checkoutBtn) {
            if (checkedCount === 0 || totalCheckedQty === 0) {
                checkoutBtn.disabled = true;
                checkoutBtn.classList.remove('btn-primary');
                checkoutBtn.classList.add('btn-secondary');
            } else {
                checkoutBtn.disabled = false;
                checkoutBtn.classList.remove('btn-secondary');
                checkoutBtn.classList.add('btn-primary');
            }
        }

        // Update Select All Checkbox state
        if (selectAllCheckbox) {
            if (totalRows > 0 && checkedCount === totalRows) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            } else if (checkedCount > 0 && checkedCount < totalRows) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = true;
            } else {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }
        }
    }

    // Handler Pilih Semua
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            const isChecked = this.checked;
            document.querySelectorAll('.item-checkbox').forEach(cb => {
                cb.checked = isChecked;
            });
            updateCartCalculation();
        });
    }

    // Handler Checkbox Tiap Item
    document.querySelectorAll('.item-checkbox').forEach(cb => {
        cb.addEventListener('change', function () {
            updateCartCalculation();
        });
    });

    // Kirim AJAX update kuantitas ke server
    function sendUpdateQty(bookId, newQty, onSuccess) {
        fetch(`/cart/update/${bookId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ quantity: newQty })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (onSuccess) onSuccess(data);
            } else {
                alert(data.message || 'Gagal memperbarui jumlah.');
            }
        })
        .catch(err => {
            console.error(err);
        });
    }

    // 1. Tombol Decrease (-)
    document.querySelectorAll('.btn-decrease').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const input = document.getElementById('qty-input-' + id);
            let currentQty = parseInt(input.value) || 1;

            if (currentQty <= 1) {
                // BUG FIX SESUAI GAMBAR 2: Ketika stok 1 dan dipencet minus (jadi 0), munculkan modal konfirmasi!
                pendingDeleteId = id;
                pendingDeleteType = 'single';
                document.getElementById('deleteModalSubtitle').textContent = 'Kuantitas menjadi 0. Apakah Anda ingin menghapus buku ini dari keranjang?';
                deleteModal.show();
            } else {
                const newQty = currentQty - 1;
                input.value = newQty;
                const row = document.getElementById('cart-row-' + id);
                const price = parseFloat(row.getAttribute('data-price')) || 0;
                document.getElementById('subtotal-' + id).textContent = formatRupiah(price * newQty);
                updateCartCalculation();
                sendUpdateQty(id, newQty);
            }
        });
    });

    // 2. Tombol Increase (+)
    document.querySelectorAll('.btn-increase').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const input = document.getElementById('qty-input-' + id);
            const row = document.getElementById('cart-row-' + id);
            const stock = parseInt(row.getAttribute('data-stock')) || 99;
            let currentQty = parseInt(input.value) || 0;

            if (currentQty >= stock) {
                alert(`Maksimal stok tercapai. Sisa stok yang tersedia hanya ${stock} buku.`);
                return;
            }

            const newQty = currentQty + 1;
            input.value = newQty;
            const price = parseFloat(row.getAttribute('data-price')) || 0;
            document.getElementById('subtotal-' + id).textContent = formatRupiah(price * newQty);
            updateCartCalculation();
            sendUpdateQty(id, newQty);
        });
    });

    // 3. Input Manual Kuantitas
    document.querySelectorAll('.quantity-input').forEach(input => {
        let previousVal = input.value;

        input.addEventListener('focus', function () {
            previousVal = this.value;
        });

        input.addEventListener('change', function () {
            const id = this.getAttribute('data-id');
            const row = document.getElementById('cart-row-' + id);
            const stock = parseInt(row.getAttribute('data-stock')) || 99;
            let val = parseInt(this.value);

            if (isNaN(val) || val <= 0) {
                // Munculkan modal konfirmasi
                pendingDeleteId = id;
                pendingDeleteType = 'single';
                document.getElementById('deleteModalSubtitle').textContent = 'Kuantitas 0. Apakah Anda ingin menghapus buku ini dari keranjang?';
                deleteModal.show();
            } else if (val > stock) {
                alert(`Jumlah melebihi stok yang tersedia (${stock} buku).`);
                this.value = stock;
                const price = parseFloat(row.getAttribute('data-price')) || 0;
                document.getElementById('subtotal-' + id).textContent = formatRupiah(price * stock);
                updateCartCalculation();
                sendUpdateQty(id, stock);
            } else {
                const price = parseFloat(row.getAttribute('data-price')) || 0;
                document.getElementById('subtotal-' + id).textContent = formatRupiah(price * val);
                updateCartCalculation();
                sendUpdateQty(id, val);
            }
        });
    });

    // 4. Tombol Hapus Single Item
    document.querySelectorAll('.btn-delete-single').forEach(btn => {
        btn.addEventListener('click', function () {
            pendingDeleteId = this.getAttribute('data-id');
            pendingDeleteType = 'single';
            const title = this.getAttribute('data-title');
            document.getElementById('deleteModalSubtitle').textContent = `Buku "${title}" akan dihapus dari keranjang belanja.`;
            deleteModal.show();
        });
    });

    // 5. Tombol Hapus Terpilih
    const btnDeleteSelected = document.getElementById('btnDeleteSelected');
    if (btnDeleteSelected) {
        btnDeleteSelected.addEventListener('click', function () {
            const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
            if (checkedBoxes.length === 0) {
                alert('Pilih minimal 1 item untuk dihapus.');
                return;
            }
            pendingDeleteType = 'selected';
            document.getElementById('deleteModalSubtitle').textContent = `Anda akan menghapus ${checkedBoxes.length} buku yang dicentang dari keranjang.`;
            deleteModal.show();
        });
    }

    // 6. Tombol Kosongkan Semua Keranjang
    const btnClearAllCart = document.getElementById('btnClearAllCart');
    if (btnClearAllCart) {
        btnClearAllCart.addEventListener('click', function () {
            pendingDeleteType = 'all';
            document.getElementById('deleteModalSubtitle').textContent = 'Semua isi keranjang belanja akan dikosongkan.';
            deleteModal.show();
        });
    }

    // 7. Aksi Tombol "TIDAK" di Modal (Reset ke 1 jika dari minus/0)
    document.getElementById('btnCancelDelete').addEventListener('click', function () {
        if (pendingDeleteId) {
            const input = document.getElementById('qty-input-' + pendingDeleteId);
            if (input && (parseInt(input.value) <= 0 || isNaN(parseInt(input.value)))) {
                input.value = 1;
                const row = document.getElementById('cart-row-' + pendingDeleteId);
                const price = parseFloat(row.getAttribute('data-price')) || 0;
                document.getElementById('subtotal-' + pendingDeleteId).textContent = formatRupiah(price);
                updateCartCalculation();
                sendUpdateQty(pendingDeleteId, 1);
            }
        }
        pendingDeleteId = null;
    });

    // 8. Aksi Tombol "YA" di Modal
    document.getElementById('btnConfirmDelete').addEventListener('click', function () {
        deleteModal.hide();

        if (pendingDeleteType === 'single' && pendingDeleteId) {
            const form = document.getElementById('hiddenRemoveForm');
            form.action = `/cart/remove/${pendingDeleteId}`;
            form.submit();
        } else if (pendingDeleteType === 'selected') {
            // Hapus yang dicentang satu per satu lalu refresh
            const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
            const promises = Array.from(checkedBoxes).map(cb => {
                return fetch(`/cart/remove/${cb.value}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
            });

            Promise.all(promises).then(() => {
                window.location.reload();
            });
        } else if (pendingDeleteType === 'all') {
            document.getElementById('hiddenClearForm').submit();
        }
    });

    // Inisialisasi awal
    updateCartCalculation();
});
</script>
@endpush
@endsection
