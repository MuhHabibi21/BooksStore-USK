<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use App\Models\Contact;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookstoreTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_public_pages_load_successfully(): void
    {
        $this->get('/')->assertStatus(200);
        $this->get('/about')->assertStatus(200);
        $this->get('/contact')->assertStatus(200);
        
        $loginRes = $this->get('/login');
        $loginRes->assertStatus(200);
        // Pada halaman login, tombol Masuk aktif dengan styling btn-warning
        $loginRes->assertSee('class="btn btn-warning text-dark fw-bold px-3 shadow-sm" href="' . route('login') . '">Masuk</a>', false);

        $registerRes = $this->get('/register');
        $registerRes->assertStatus(200);
        // Pada halaman register, tombol Daftar aktif dengan styling btn-warning
        $registerRes->assertSee('class="btn btn-warning text-dark fw-bold px-3 shadow-sm" href="' . route('register') . '">Daftar</a>', false);
    }

    public function test_guest_accessing_cart_redirects_to_login(): void
    {
        $response = $this->get('/cart');
        $response->assertRedirect('/login');
        $response->assertSessionHas('error', 'Anda belum login, yuk login dulu untuk melihat keranjang belanja.');
    }

    public function test_authenticated_user_can_access_cart(): void
    {
        $user = User::where('role', 'user')->first();
        $this->actingAs($user);
        $this->get('/cart')->assertStatus(200);
    }

    public function test_registration_phone_and_password_validation(): void
    {
        // 1. Password lemah (kurang dari 8 karakter, tanpa huruf besar/simbol)
        $response = $this->post('/register', [
            'name' => 'User Weak Pass',
            'email' => 'weakpass@example.com',
            'password' => 'secret',
            'password_confirmation' => 'secret',
            'phone' => '081234567890',
        ]);
        $response->assertSessionHasErrors(['password']);

        // 2. Password 8 karakter tapi tanpa simbol & huruf besar
        $response = $this->post('/register', [
            'name' => 'User No Symbol',
            'email' => 'nosymbol@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'phone' => '081234567890',
        ]);
        $response->assertSessionHasErrors(['password']);

        // 3. Phone dengan huruf / karakter invalid
        $response = $this->post('/register', [
            'name' => 'User Test',
            'email' => 'testphone@example.com',
            'password' => 'Secret123!',
            'password_confirmation' => 'Secret123!',
            'phone' => '0812abc345',
        ]);
        $response->assertSessionHasErrors(['phone']);

        // 4. Phone lebih dari 15 digit
        $response = $this->post('/register', [
            'name' => 'User Test',
            'email' => 'testphone2@example.com',
            'password' => 'Secret123!',
            'password_confirmation' => 'Secret123!',
            'phone' => '081234567890123456',
        ]);
        $response->assertSessionHasErrors(['phone']);

        // 5. Phone valid dengan format internasional +62 & password kuat
        $response = $this->post('/register', [
            'name' => 'User Plus 62',
            'email' => 'testphone62@example.com',
            'password' => 'Secret123!',
            'password_confirmation' => 'Secret123!',
            'phone' => '+6281234567890',
        ]);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', ['email' => 'testphone62@example.com', 'phone' => '+6281234567890']);

        // 6. Phone valid dengan format standar 08 & password kuat
        $response = $this->post('/register', [
            'name' => 'User Test Valid 08',
            'email' => 'testphonevalid08@example.com',
            'password' => 'Secret123!',
            'password_confirmation' => 'Secret123!',
            'phone' => '081234567890',
        ]);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', ['email' => 'testphonevalid08@example.com', 'phone' => '081234567890']);
    }

    public function test_book_detail_page(): void
    {
        $book = Book::first();
        $this->assertNotNull($book);

        $response = $this->get('/books/' . $book->id);
        $response->assertStatus(200);
        $response->assertSee($book->title);
    }

    public function test_guest_cannot_add_to_cart_and_redirects_to_login(): void
    {
        $book = Book::first();
        $response = $this->post('/cart/add/' . $book->id, ['quantity' => 1]);
        $response->assertRedirect('/login');
        $response->assertSessionHas('error', 'Anda belum login, yuk login dulu');
    }

    public function test_cart_functionality(): void
    {
        $user = User::where('role', 'user')->first();
        $book = Book::first();
        $this->assertNotNull($book);

        $this->actingAs($user);
        $response = $this->post('/cart/add/' . $book->id, ['quantity' => 1]);
        $response->assertSessionHas('cart');

        $cart = session('cart');
        $this->assertArrayHasKey($book->id, $cart);
    }

    public function test_admin_pages_accessible_by_admin(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->assertNotNull($admin);

        $this->actingAs($admin);

        $this->get('/admin')->assertStatus(200);
        $this->get('/admin/categories')->assertStatus(200);
        $this->get('/admin/books')->assertStatus(200);
        $this->get('/admin/books/create')->assertStatus(200);
        $this->get('/admin/orders')->assertStatus(200);
        $this->get('/admin/users')->assertStatus(200);
        $this->get('/admin/contacts')->assertStatus(200);
    }

    public function test_guest_cannot_access_admin_and_receives_403_forbidden(): void
    {
        $response = $this->get('/admin');
        $response->assertStatus(403);
        $response->assertSee('403');
        $response->assertSee('Akses Ditolak');
    }

    public function test_regular_user_cannot_access_admin(): void
    {
        $user = User::where('role', 'user')->first();
        $this->assertNotNull($user);

        $this->actingAs($user);
        $this->get('/admin')->assertStatus(403);
    }

    public function test_admin_restricted_from_customer_features(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $this->get('/cart')->assertRedirect('/admin');
        $this->get('/my-orders')->assertRedirect('/admin/orders');
        $this->get('/contact')->assertRedirect('/admin/contacts');

        $book = Book::first();
        $response = $this->post('/cart/add/' . $book->id, ['quantity' => 1]);
        $response->assertSessionHas('error');
    }

    public function test_contact_form_submission(): void
    {
        $response = $this->post('/contact', [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'subject' => 'Tanya Stok',
            'message' => 'Apakah buku tersedia?',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('contacts', [
            'email' => 'budi@example.com',
            'subject' => 'Tanya Stok',
        ]);
    }

    public function test_user_can_place_order_with_payment_at_delivery(): void
    {
        $user = User::where('role', 'user')->first();
        $book = Book::first();

        $this->actingAs($user);

        // Tambah ke keranjang
        $this->post('/cart/add/' . $book->id, ['quantity' => 2]);

        // Proses checkout
        $response = $this->post('/checkout', [
            'recipient_name' => 'Ahmad Rizki',
            'recipient_phone' => '08123456789',
            'delivery_address' => 'Jl. Mawar No. 10, Jakarta Selatan',
            'payment_method' => 'Payment at Delivery',
            'notes' => 'Tolong telepon sebelum kirim',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'payment_method' => 'Payment at Delivery',
            'status' => 'pending',
            'recipient_name' => 'Ahmad Rizki',
        ]);
    }

    public function test_user_can_update_profile(): void
    {
        $user = User::where('role', 'user')->first();
        $this->actingAs($user);

        $response = $this->get('/profile');
        $response->assertStatus(200);

        $response = $this->put('/profile', [
            'name' => 'Ahmad Updated',
            'email' => $user->email,
            'phone' => '08999999999',
            'address' => 'Jl. Baru No. 1',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Ahmad Updated',
            'phone' => '08999999999',
        ]);
    }

    public function test_user_can_change_password(): void
    {
        $user = User::where('role', 'user')->first();
        $this->actingAs($user);

        $response = $this->put('/profile/password', [
            'current_password' => 'user123',
            'password' => 'NewPass123!',
            'password_confirmation' => 'NewPass123!',
        ]);

        $response->assertSessionHas('success');
    }

    public function test_admin_cannot_delete_category_with_books(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $category = Category::whereHas('books')->first();
        $this->assertNotNull($category);

        $response = $this->delete('/admin/categories/' . $category->id);
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_admin_can_delete_empty_category(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $emptyCategory = Category::create([
            'name' => 'Kategori Kosong Baru',
            'description' => 'Tidak memiliki buku',
        ]);

        $response = $this->delete('/admin/categories/' . $emptyCategory->id);
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('categories', ['id' => $emptyCategory->id]);
    }

    public function test_admin_cannot_delete_book_with_paid_orders(): void
    {
        $admin = User::where('role', 'admin')->first();
        $user = User::where('role', 'user')->first();
        $this->actingAs($admin);

        $book = Book::first();
        $this->assertNotNull($book);

        // Buat order dengan payment_status 'paid'
        $order = Order::create([
            'order_number' => 'ORD-TEST-PAID',
            'user_id' => $user->id,
            'total_amount' => $book->price,
            'status' => 'completed',
            'payment_method' => 'cod',
            'payment_status' => 'paid',
            'recipient_name' => 'Budi Santoso',
            'recipient_phone' => '081234567890',
            'delivery_address' => 'Jl. Merdeka No. 10',
        ]);

        $order->items()->create([
            'book_id' => $book->id,
            'quantity' => 1,
            'price' => $book->price,
            'subtotal' => $book->price,
        ]);

        $response = $this->delete('/admin/books/' . $book->id);
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('books', ['id' => $book->id]);
    }

    public function test_admin_can_delete_book_without_paid_orders(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $category = Category::first();
        $newBook = Book::create([
            'category_id' => $category->id,
            'title' => 'Buku Baru Belum Ada Order',
            'author' => 'Penulis Percobaan',
            'publisher' => 'Penerbit Percobaan',
            'year' => 2024,
            'price' => 50000,
            'stock' => 10,
        ]);

        $response = $this->delete('/admin/books/' . $newBook->id);
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('books', ['id' => $newBook->id]);
    }

    public function test_login_page_has_no_cache_headers_and_redirects_authenticated_users(): void
    {
        // 1. Guest mengakses /login mendapatkan cache-control no-cache & no-store
        $response = $this->get('/login');
        $response->assertStatus(200);
        $cacheControl = $response->headers->get('Cache-Control');
        $this->assertStringContainsString('no-store', $cacheControl);
        $this->assertStringContainsString('no-cache', $cacheControl);

        // 2. User yang sudah login mengakses /login langsung diarahkan ke home
        $user = User::where('role', 'user')->first();
        $this->actingAs($user);
        $authResponse = $this->get('/login');
        $authResponse->assertRedirect('/');
    }

    public function test_user_can_place_order_with_qris_and_redirects_to_payment_page(): void
    {
        $user = User::where('role', 'user')->first();
        $book = Book::first();

        $this->actingAs($user);
        $this->post('/cart/add/' . $book->id, ['quantity' => 1]);

        $response = $this->post('/checkout', [
            'recipient_name' => 'Wandi Test',
            'recipient_phone' => '08123456789',
            'delivery_address' => 'Jl. Surabaya 10',
            'payment_method' => 'QRIS',
        ]);

        $order = Order::where('recipient_name', 'Wandi Test')->latest()->first();
        $this->assertNotNull($order);
        $response->assertRedirect('/my-orders/' . $order->id . '/payment');

        // Buka halaman pembayaran
        $paymentPageRes = $this->get('/my-orders/' . $order->id . '/payment');
        $paymentPageRes->assertStatus(200);
        $paymentPageRes->assertSee('QRIS');
        $paymentPageRes->assertSee('Virtual Account');
        $paymentPageRes->assertSee('Kartu Debit');

        // Selesaikan pembayaran
        $payRes = $this->post('/my-orders/' . $order->id . '/pay', [
            'payment_method' => 'QRIS',
        ]);

        $payRes->assertRedirect('/my-orders/' . $order->id);

        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals('pending', $order->status); // Menunggu konfirmasi admin

        // Buka faktur pesanan
        $invoiceRes = $this->get('/my-orders/' . $order->id);
        $invoiceRes->assertStatus(200);
        $invoiceRes->assertSee('Menunggu Konfirmasi Admin');
        $invoiceRes->assertSee('Sudah Dibayar (Lunas)');
    }

    public function test_user_can_pay_existing_unpaid_order(): void
    {
        $user = User::where('role', 'user')->first();
        $book = Book::first();

        // Buat pesanan belum bayar (seperti pesanan #6 atau #7)
        $order = Order::create([
            'order_number' => 'ORD-TEST-UNPAID',
            'user_id' => $user->id,
            'total_amount' => 150000,
            'status' => 'pending',
            'payment_method' => 'Bank Transfer',
            'payment_status' => 'unpaid',
            'recipient_name' => 'Wandi Existing',
            'recipient_phone' => '08123456789',
            'delivery_address' => 'Jalan Surabaya 10',
        ]);

        $this->actingAs($user);

        // Faktur menampilkan tombol Bayar Sekarang
        $detailRes = $this->get('/my-orders/' . $order->id);
        $detailRes->assertStatus(200);
        $detailRes->assertSee('Bayar Sekarang');

        // User melakukan pembayaran Virtual Account
        $payRes = $this->post('/my-orders/' . $order->id . '/pay', [
            'payment_method' => 'Transfer Bank (BCA Virtual Account)',
        ]);

        $payRes->assertRedirect('/my-orders/' . $order->id);

        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals('pending', $order->status); // Menunggu konfirmasi admin
        $this->assertEquals('Transfer Bank (BCA Virtual Account)', $order->payment_method);

        // Faktur sekarang menampilkan Sudah Dibayar (Lunas)
        $updatedRes = $this->get('/my-orders/' . $order->id);
        $updatedRes->assertSee('Sudah Dibayar (Lunas)');
        $updatedRes->assertSee('Menunggu Konfirmasi Admin');
    }

    public function test_admin_can_reply_to_contact_and_user_can_view_reply(): void
    {
        $user = User::where('role', 'user')->first();
        $admin = User::where('role', 'admin')->first();

        // 1. User kirim pesan kontak
        $contact = Contact::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'subject' => 'Tanya Stok Buku Pemrograman',
            'message' => 'Apakah buku Clean Code masih ready stok banyak?',
            'is_read' => false,
        ]);

        // 2. Admin membuka detail pesan dan membalas
        $this->actingAs($admin);
        $showRes = $this->get('/admin/contacts/' . $contact->id);
        $showRes->assertStatus(200);
        $showRes->assertSee('Kirim Pesan Balasan ke Pengguna');

        $replyRes = $this->post('/admin/contacts/' . $contact->id . '/reply', [
            'admin_reply' => 'Halo Kak, stok buku Clean Code masih tersedia banyak dan siap dikirim!',
        ]);
        $replyRes->assertSessionHas('success');

        $contact->refresh();
        $this->assertTrue($contact->is_read);
        $this->assertEquals('Halo Kak, stok buku Clean Code masih tersedia banyak dan siap dikirim!', $contact->admin_reply);
        $this->assertNotNull($contact->replied_at);

        // 3. User login dan melihat balasan di halaman Kontak (/contact)
        $this->actingAs($user);
        $userContactRes = $this->get('/contact');
        $userContactRes->assertStatus(200);
        $userContactRes->assertSee('Riwayat Pesan Anda');
        $userContactRes->assertSee('Dibalas oleh Admin');
        $userContactRes->assertSee('Halo Kak, stok buku Clean Code masih tersedia banyak dan siap dikirim!');
    }
}
