<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestCartTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_add_products_and_must_login_at_checkout(): void
    {
        $product = Product::create(['name' => 'Iced Mocha', 'price' => 5.75, 'category' => 'Iced Coffee']);

        $this->post(route('cart.add'), ['product_id' => $product->id, 'quantity' => 1])
            ->assertOk()->assertJson(['success' => true, 'cartCount' => 1]);

        $this->get(route('cart.view'))->assertOk()->assertSee('Iced Mocha')->assertSee('Sign In to Checkout');
        $this->get('/products')->assertOk()->assertSee('id="navCartCount">1', false)->assertSee('Quantity of Iced Mocha in cart')->assertSee('product-qty-value', false);
        $this->get(route('checkout.index'))->assertRedirect(route('login'));
    }

    public function test_guest_cart_is_merged_into_account_after_login(): void
    {
        $product = Product::create(['name' => 'Berry Shake', 'price' => 6.25, 'category' => 'Milkshakes']);
        $user = User::factory()->create();

        $this->post(route('cart.add'), ['product_id' => $product->id, 'quantity' => 2]);
        $this->post('/login', ['email' => $user->email, 'password' => 'password']);

        $this->assertDatabaseHas('carts', ['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 2]);
        $this->assertNull(session('guest_cart'));
    }

    public function test_menu_uses_quick_add_instead_of_login_to_buy(): void
    {
        Product::create(['name' => 'Hot Chocolate', 'price' => 4.25, 'category' => 'Hot Drinks']);

        $this->get('/products')->assertOk()->assertSee('Quantity of Hot Chocolate in cart')->assertSee('product-minus')->assertSee('product-plus')->assertDontSee('Login to buy');
    }
}
