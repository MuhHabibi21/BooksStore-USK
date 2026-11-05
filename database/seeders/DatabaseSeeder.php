<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Contact;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@bookstore.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '081234567890',
                'address' => 'Kantor Pusat BookStore, Jakarta',
            ]
        );

        // 2. Akun Regular User
        $user = User::firstOrCreate(
            ['email' => 'user@bookstore.com'],
            [
                'name' => 'Ahmad Rizki',
                'password' => Hash::make('user123'),
                'role' => 'user',
                'phone' => '089876543210',
                'address' => 'Jl. Merdeka No. 45, Bandung',
            ]
        );

        $user2 = User::firstOrCreate(
            ['email' => 'siti@bookstore.com'],
            [
                'name' => 'Siti Nurhaliza',
                'password' => Hash::make('user123'),
                'role' => 'user',
                'phone' => '085712345678',
                'address' => 'Jl. Sudirman No. 12, Surabaya',
            ]
        );

        // 3. Kategori Buku
        $catProg = Category::firstOrCreate(['name' => 'Pemrograman & Teknologi'], ['description' => 'Buku-buku panduan pemrograman, web development, dan teknologi informasi.']);
        $catFiksi = Category::firstOrCreate(['name' => 'Fiksi & Sastra'], ['description' => 'Novel, cerpen, dan karya sastra pilihan Indonesia dan mancanegara.']);
        $catBisnis = Category::firstOrCreate(['name' => 'Bisnis & Ekonomi'], ['description' => 'Buku seputar kewirausahaan, keuangan, manajemen, dan investasi.']);
        $catSelf = Category::firstOrCreate(['name' => 'Pengembangan Diri'], ['description' => 'Buku motivasi, psikologi populer, dan peningkatan produktivitas diri.']);

        // 4. Data Buku
        $b1 = Book::firstOrCreate(
            ['title' => 'Mastering Laravel 11 & 12'],
            [
                'category_id' => $catProg->id,
                'author' => 'Budi Raharjo',
                'publisher' => 'Informatika Bandung',
                'year' => 2024,
                'price' => 125000,
                'stock' => 15,
                'cover_image' => null,
                'description' => 'Panduan lengkap dan praktis membangun aplikasi web modern berskala besar menggunakan Framework Laravel versi terbaru.'
            ]
        );

        $b2 = Book::firstOrCreate(
            ['title' => 'Clean Code: Handbook of Agile Software Craftsmanship'],
            [
                'category_id' => $catProg->id,
                'author' => 'Robert C. Martin',
                'publisher' => 'Prentice Hall',
                'year' => 2023,
                'price' => 185000,
                'stock' => 10,
                'cover_image' => null,
                'description' => 'Pedoman esensial bagi setiap programmer yang ingin menulis kode yang bersih, mudah dibaca, dan mudah dikembangkan.'
            ]
        );

        $b3 = Book::firstOrCreate(
            ['title' => 'Laskar Pelangi'],
            [
                'category_id' => $catFiksi->id,
                'author' => 'Andrea Hirata',
                'publisher' => 'Bentang Pustaka',
                'year' => 2022,
                'price' => 89000,
                'stock' => 25,
                'cover_image' => null,
                'description' => 'Kisah inspiratif tentang perjuangan sepuluh anak di Belitong dalam mengejar mimpi dan menuntut ilmu di tengah keterbatasan.'
            ]
        );

        $b4 = Book::firstOrCreate(
            ['title' => 'Bumi Manusia'],
            [
                'category_id' => $catFiksi->id,
                'author' => 'Pramoedya Ananta Toer',
                'publisher' => 'Hasta Mitra / Lentera Dipantara',
                'year' => 2021,
                'price' => 99000,
                'stock' => 18,
                'cover_image' => null,
                'description' => 'Karya sastra legendaris Indonesia yang menceritakan dinamika sosial dan perjuangan pemuda pribumi di era kolonial Hindia Belanda.'
            ]
        );

        $b5 = Book::firstOrCreate(
            ['title' => 'Atomic Habits'],
            [
                'category_id' => $catSelf->id,
                'author' => 'James Clear',
                'publisher' => 'Gramedia Pustaka Utama',
                'year' => 2023,
                'price' => 108000,
                'stock' => 30,
                'cover_image' => null,
                'description' => 'Perubahan kecil yang memberikan hasil luar biasa. Cara mudah dan terbukti untuk membentuk kebiasaan baik dan menghilangkan kebiasaan buruk.'
            ]
        );

        $b6 = Book::firstOrCreate(
            ['title' => 'The Psychology of Money'],
            [
                'category_id' => $catBisnis->id,
                'author' => 'Morgan Housel',
                'publisher' => 'Baca Pustaka',
                'year' => 2023,
                'price' => 95000,
                'stock' => 12,
                'cover_image' => null,
                'description' => 'Pelajaran abadi mengenai kekayaan, ketamakan, dan kebahagiaan dalam mengelola uang secara bijaksana.'
            ]
        );

        // 5. Sample Order
        $order = Order::firstOrCreate(
            ['order_number' => 'ORD-20261105-0001'],
            [
                'user_id' => $user->id,
                'total_amount' => 214000,
                'status' => 'pending',
                'payment_method' => 'Payment at Delivery',
                'payment_status' => 'unpaid',
                'recipient_name' => $user->name,
                'recipient_phone' => $user->phone,
                'delivery_address' => $user->address,
                'notes' => 'Tolong kirim saat jam kerja ya.',
            ]
        );

        OrderItem::firstOrCreate(
            ['order_id' => $order->id, 'book_id' => $b1->id],
            [
                'quantity' => 1,
                'price' => $b1->price,
                'subtotal' => $b1->price,
            ]
        );

        OrderItem::firstOrCreate(
            ['order_id' => $order->id, 'book_id' => $b3->id],
            [
                'quantity' => 1,
                'price' => $b3->price,
                'subtotal' => $b3->price,
            ]
        );

        // 6. Sample Contact Message
        Contact::firstOrCreate(
            ['email' => 'ahmad@example.com', 'subject' => 'Pertanyaan Ketersediaan Stok Buku'],
            [
                'user_id' => $user->id,
                'name' => 'Ahmad Rizki',
                'message' => 'Halo Admin, apakah buku Mastering Laravel edisi cetakan kedua akan segera restock? Terima kasih!',
                'is_read' => false,
            ]
        );
    }
}
