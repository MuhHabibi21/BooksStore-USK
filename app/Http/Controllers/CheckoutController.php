<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Tampilkan Halaman Checkout
     */
    public function index(Request $request)
    {
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.orders.index')->with('error', 'Akun Administrator tidak dapat melakukan pemesanan buku.');
        }

        $allCart = session()->get('cart', []);

        if (empty($allCart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        // Ambil item yang dicentang oleh user (fitur checkout ala marketplace)
        $selectedIds = $request->input('selected_items', session('selected_items', []));

        if (!empty($selectedIds)) {
            // Filter hanya item yang dicentang
            $cart = array_filter($allCart, function ($key) use ($selectedIds) {
                return in_array($key, $selectedIds);
            }, ARRAY_FILTER_USE_KEY);

            if (empty($cart)) {
                return redirect()->route('cart.index')->with('error', 'Silakan pilih minimal 1 buku yang ingin dicheckout.');
            }

            session(['selected_items' => array_keys($cart)]);
        } else {
            // Jika tidak ada spesifikasi, proses semua buku di keranjang
            $cart = $allCart;
            session(['selected_items' => array_keys($allCart)]);
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $user = Auth::user();

        return view('checkout.index', compact('cart', 'total', 'user'));
    }

    /**
     * Proses Pemesanan Buku (Payment at Delivery / Pembayaran sebelum buku dikirim)
     */
    public function process(Request $request)
    {
        $allCart = session()->get('cart', []);

        if (empty($allCart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        // Ambil item yang terpilih untuk dicheckout
        $selectedIds = session('selected_items', array_keys($allCart));
        $selectedCart = array_filter($allCart, function ($key) use ($selectedIds) {
            return in_array($key, $selectedIds);
        }, ARRAY_FILTER_USE_KEY);

        if (empty($selectedCart)) {
            return redirect()->route('cart.index')->with('error', 'Silakan pilih minimal 1 buku untuk checkout.');
        }

        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => ['required', 'regex:/^[0-9]{10,13}$/'],
            'delivery_address' => 'required|string',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ], [
            'recipient_name.required' => 'Nama penerima wajib diisi.',
            'recipient_phone.required' => 'Nomor HP penerima wajib diisi.',
            'recipient_phone.regex' => 'Nomor HP penerima hanya boleh berisi angka dan panjangnya antara 10 hingga 13 digit.',
            'delivery_address.required' => 'Alamat pengiriman wajib diisi.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
        ]);

        // Cek kembali ketersediaan stok buku yang dipilih sebelum memproses
        foreach ($selectedCart as $item) {
            $book = Book::find($item['id']);
            if (!$book || $book->stock < $item['quantity']) {
                return redirect()->route('cart.index')->with('error', "Stok untuk buku \"{$item['title']}\" tidak mencukupi.");
            }
        }

        // Gunakan Database Transaction agar aman
        $order = DB::transaction(function () use ($validated, $selectedCart) {
            $totalAmount = 0;
            foreach ($selectedCart as $item) {
                $totalAmount += $item['price'] * $item['quantity'];
            }

            // Generate nomor order unik
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_method' => $validated['payment_method'], // Payment at Delivery
                'payment_status' => 'unpaid', // belum lunas / menunggu pembayaran saat serah terima
                'recipient_name' => $validated['recipient_name'],
                'recipient_phone' => $validated['recipient_phone'],
                'delivery_address' => $validated['delivery_address'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Simpan detail item dan potong stok buku
            foreach ($selectedCart as $item) {
                $book = Book::find($item['id']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $book->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                // Kurangi stok buku
                $book->decrement('stock', $item['quantity']);
            }

            return $order;
        });

        // Hapus hanya item yang sudah berhasil dicheckout dari keranjang
        foreach ($selectedIds as $idToRemove) {
            unset($allCart[$idToRemove]);
        }
        session()->put('cart', $allCart);
        session()->forget('selected_items');

        // Jika memilih metode pembayaran digital (QRIS / Bank Transfer / Debit), arahkan langsung ke halaman pembayaran
        if ($validated['payment_method'] === 'Payment at Delivery' || $validated['payment_method'] === 'Payment at Delivery (COD)') {
            return redirect()->route('orders.show', $order->id)->with('success', 'Pesanan COD Anda berhasil dibuat! Pembayaran diserahkan kepada kurir saat buku sampai.');
        }

        return redirect()->route('orders.payment', $order->id)->with('info', 'Pesanan berhasil dibuat! Silakan selesaikan pembayaran untuk memproses pesanan.');
    }

    /**
     * Halaman Pembayaran Interaktif User (QRIS, Bank Transfer VA, Debit)
     */
    public function paymentPage($id)
    {
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.orders.index');
        }

        $order = Order::with(['items.book', 'user'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Jika pesanan sudah dibayar, langsung alihkan ke faktur
        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order->id)->with('info', 'Pesanan ini sudah dibayar (Lunas).');
        }

        return view('orders.payment', compact('order'));
    }

    /**
     * Proses Konfirmasi Pembayaran oleh User (Update status menjadi sudah dibayar / lunas)
     */
    public function processPayment(Request $request, $id)
    {
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.orders.index');
        }

        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'payment_method' => 'nullable|string|max:100',
        ]);

        $method = $validated['payment_method'] ?? $order->payment_method;

        $order->update([
            'payment_status' => 'paid',
            'status' => 'pending', // Menunggu konfirmasi admin
            'payment_method' => $method,
        ]);

        return redirect()->route('orders.show', $order->id)
            ->with('success', "Pembayaran sebesar Rp " . number_format($order->total_amount, 0, ',', '.') . " berhasil! Status pesanan kini sudah dibayar dan sedang menunggu konfirmasi admin.");
    }

    /**
     * Halaman Riwayat Pesanan User
     */
    public function myOrders()
    {
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.orders.index');
        }

        $orders = Order::where('user_id', Auth::id())
            ->with(['items.book'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Detail Pesanan / Faktur Struk Pembelian
     */
    public function orderDetail($id)
    {
        $order = Order::with(['items.book', 'user'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }
}
