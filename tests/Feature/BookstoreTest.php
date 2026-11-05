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
        $this->get('/cart')->assertStatus(200);
        $this->get('/login')->assertStatus(200);
        $this->get('/register')->assertStatus(200);
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
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHas('success');
    }
}
