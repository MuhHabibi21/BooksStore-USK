<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Tampilkan Halaman Keranjang Belanja
     */
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Anda belum login, yuk login dulu untuk melihat keranjang belanja.');
        }

        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Akun Administrator tidak memiliki keranjang belanja.');
        }

        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.index', compact('cart', 'total'));
    }

    /**
     * Tambahkan Buku ke Keranjang Belanja
     */
    public function add(Request $request, $id)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Anda belum login, yuk login dulu');
        }

        if (auth()->user()->isAdmin()) {
            return back()->with('error', 'Akun Administrator tidak dapat melakukan pemesanan buku.');
        }

        $book = Book::findOrFail($id);

        if ($book->stock <= 0) {
            return back()->with('error', 'Maaf, stok buku ini sedang habis.');
        }

        $qty = max(1, (int) $request->input('quantity', 1));
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $newQty = $cart[$id]['quantity'] + $qty;
            if ($newQty > $book->stock) {
                return back()->with('error', "Jumlah melebihi stok yang tersedia (Sisa stok: {$book->stock}).");
            }
            $cart[$id]['quantity'] = $newQty;
        } else {
            if ($qty > $book->stock) {
                return back()->with('error', "Jumlah melebihi stok yang tersedia (Sisa stok: {$book->stock}).");
            }
            $cart[$id] = [
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
                'price' => (float) $book->price,
                'quantity' => $qty,
                'cover_image' => $book->cover_image,
                'stock' => $book->stock,
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', "Buku \"{$book->title}\" berhasil ditambahkan ke keranjang!");
    }

    /**
     * Update Jumlah Buku di Keranjang
     */
    public function update(Request $request, $id)
    {
        $qty = (int) $request->input('quantity');
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $book = Book::find($id);
            if ($qty <= 0) {
                unset($cart[$id]);
                session()->put('cart', $cart);

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => true, 'action' => 'deleted']);
                }

                return back()->with('success', 'Item berhasil dihapus dari keranjang.');
            }

            if ($book && $qty > $book->stock) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => "Stok tidak mencukupi. Sisa stok: {$book->stock}"], 422);
                }
                return back()->with('error', "Stok tidak mencukupi. Sisa stok: {$book->stock}");
            }

            $cart[$id]['quantity'] = $qty;
            session()->put('cart', $cart);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'quantity' => $qty,
                    'subtotal' => $cart[$id]['price'] * $qty,
                    'formatted_subtotal' => 'Rp ' . number_format($cart[$id]['price'] * $qty, 0, ',', '.')
                ]);
            }

            return back()->with('success', 'Jumlah pesanan berhasil diperbarui.');
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan di keranjang.'], 404);
        }

        return back()->with('error', 'Item tidak ditemukan di keranjang.');
    }

    /**
     * Hapus Item dari Keranjang
     */
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            return back()->with('success', 'Item berhasil dihapus dari keranjang.');
        }

        return back()->with('error', 'Item tidak ditemukan di keranjang.');
    }

    /**
     * Kosongkan Keranjang Belanja
     */
    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Keranjang belanja berhasil dikosongkan.');
    }
}
