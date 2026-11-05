<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Halaman Utama & Katalog Toko Buku Online
     * Menampilkan semua buku dengan fitur filter kategori, harga, stok, dan pencarian.
     */
    public function index(Request $request)
    {
        $query = Book::with('category');

        // 1. Pencarian Kata Kunci (Judul, Penulis, Penerbit)
        if ($request->filled('q')) {
            $search = trim($request->input('q'));
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('publisher', 'like', "%{$search}%");
            });
        }

        // 2. Filter Kategori Buku
        $selectedCategory = null;
        if ($request->filled('category')) {
            $catId = $request->input('category');
            $query->where('category_id', $catId);
            $selectedCategory = Category::find($catId);
        }

        // 3. Filter Rentang Harga
        if ($request->filled('price_range')) {
            switch ($request->input('price_range')) {
                case 'under_50':
                    $query->where('price', '<', 50000);
                    break;
                case '50_100':
                    $query->whereBetween('price', [50000, 100000]);
                    break;
                case 'above_100':
                    $query->where('price', '>', 100000);
                    break;
            }
        }

        // 4. Filter Ketersediaan Stok
        if ($request->input('in_stock') === '1') {
            $query->where('stock', '>', 0);
        }

        // 5. Urutan (Sorting)
        switch ($request->input('sort')) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'title_az':
                $query->orderBy('title', 'asc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
                break;
        }

        $books = $query->paginate(16)->withQueryString();
        $categories = Category::withCount('books')->get();
        $totalAllBooks = Book::count();

        // Buku Best Seller untuk banner sorotan atas
        $featuredBooks = Book::with('category')->inRandomOrder()->take(3)->get();

        return view('home', compact('books', 'categories', 'selectedCategory', 'totalAllBooks', 'featuredBooks'));
    }

    /**
     * Detail Buku Lengkap
     */
    public function show($id)
    {
        $book = Book::with('category')->findOrFail($id);
        $relatedBooks = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->take(4)
            ->get();

        return view('books.show', compact('book', 'relatedBooks'));
    }

    /**
     * Halaman About Us (Tentang Kami)
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Halaman Contact Us (Hubungi Admin)
     */
    public function contact()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.contacts.index');
        }

        return view('contact');
    }

    /**
     * Simpan Pesan Kontak ke Admin
     */
    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'subject.required' => 'Subjek pesan wajib diisi.',
            'message.required' => 'Isi pesan wajib diisi.',
        ]);

        Contact::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        return back()->with('success', 'Pesan Anda telah berhasil dikirim ke Admin. Terima kasih!');
    }
}
