<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Tampilkan List Buku
     */
    public function index(Request $request)
    {
        $query = Book::with('category')
            ->withExists(['orderItems as has_paid_orders' => function ($q) {
                $q->whereHas('order', function ($sub) {
                    $sub->where('payment_status', 'paid');
                });
            }]);

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('publisher', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        $books = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.books.index', compact('books', 'categories'));
    }

    /**
     * Form Tambah Buku
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.books.create', compact('categories'));
    }

    /**
     * Simpan Buku Baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
        ], [
            'category_id.required' => 'Kategori wajib dipilih.',
            'title.required' => 'Judul buku wajib diisi.',
            'author.required' => 'Nama penulis/pengarang wajib diisi.',
            'publisher.required' => 'Nama penerbit wajib diisi.',
            'year.required' => 'Tahun terbit wajib diisi.',
            'price.required' => 'Harga buku wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'stock.required' => 'Stok buku wajib diisi.',
            'stock.integer' => 'Stok harus berupa bilangan bulat.',
            'cover_image.image' => 'File cover harus berupa gambar (jpg, png, webp).',
            'cover_image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('books', 'public');
            $validated['cover_image'] = $path;
        }

        Book::create($validated);

        return redirect()->route('admin.books.index')->with('success', 'Buku baru berhasil ditambahkan!');
    }

    /**
     * Form Edit Buku
     */
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $categories = Category::all();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    /**
     * Update Data Buku
     */
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
        ], [
            'category_id.required' => 'Kategori wajib dipilih.',
            'title.required' => 'Judul buku wajib diisi.',
            'author.required' => 'Nama penulis/pengarang wajib diisi.',
            'publisher.required' => 'Nama penerbit wajib diisi.',
            'year.required' => 'Tahun terbit wajib diisi.',
            'price.required' => 'Harga buku wajib diisi.',
            'stock.required' => 'Stok buku wajib diisi.',
            'cover_image.image' => 'File cover harus berupa gambar (jpg, png, webp).',
        ]);

        if ($request->hasFile('cover_image')) {
            // Hapus file lama jika ada
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('books', 'public');
        }

        $book->update($validated);

        return redirect()->route('admin.books.index')->with('success', 'Data buku berhasil diperbarui!');
    }

    /**
     * Hapus Buku
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        // Validasi: Buku yang sudah dipesan & dibayar tidak boleh dihapus
        $hasPaidOrders = $book->orderItems()->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        })->exists();

        if ($hasPaidOrders) {
            return redirect()->route('admin.books.index')
                ->with('error', "Buku \"{$book->title}\" tidak dapat dihapus karena sudah ada transaksi pemesanan yang telah dibayar oleh pembeli.");
        }

        if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Data buku berhasil dihapus!');
    }
}
