<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Tampilkan List Pesanan Buku dari Berbagai User
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.book']);

        // Filter status jika ada
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Pencarian nomor order atau nama penerima
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('recipient_name', 'like', "%{$search}%");
            });
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Detail Pesanan
     */
    public function show($id)
    {
        $order = Order::with(['user', 'items.book.category'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update Status Pesanan & Pembayaran
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
            'payment_status' => 'required|in:unpaid,paid',
        ]);

        $order->update($validated);

        return back()->with('success', "Status pesanan {$order->order_number} berhasil diperbarui!");
    }
}
