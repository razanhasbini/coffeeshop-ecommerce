<?php

namespace Tests\Feature;

use App\Mail\OrderReceiptMail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CheckoutReceiptTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_confirms_order_emails_receipt_and_clears_cart(): void
    {
        Mail::fake();
        $user = User::factory()->create();
        $product = Product::create([
            'name' => 'Iced Latte',
            'description' => 'Cold and smooth',
            'price' => 4.50,
            'category' => 'Iced Coffee',
            'image_url' => 'latte.jpg',
        ]);
        Cart::create(['user_id' => $user->id, 'product_id' => $product->id, 'quantity' => 2]);

        $response = $this->actingAs($user)->post(route('checkout.confirm'), [
            'phone_number' => '+961 70 123 456',
            'shipping_address' => '12 Coffee Street, Cedar Building, floor 3',
            'delivery_city' => 'Beirut',
            'delivery_notes' => 'Ring the blue doorbell',
            'payment_method' => 'cash',
        ]);

        $order = Order::with('items')->firstOrFail();
        $response->assertRedirect(route('orders.receipt.show', $order));
        $this->assertSame('confirmed', $order->status);
        $this->assertSame('+961 70 123 456', $order->phone_number);
        $this->assertSame('Beirut', $order->delivery_city);
        $this->assertStringStartsWith('CS-', $order->receipt_number);
        $this->assertSame(2, $order->items->first()->quantity);
        $this->assertDatabaseMissing('carts', ['user_id' => $user->id]);

        Mail::assertSent(OrderReceiptMail::class, fn ($mail) => $mail->hasTo($user->email));
    }

    public function test_owner_can_view_and_download_receipt(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'shipping_address' => 'Beirut',
            'payment_method' => 'cash',
            'receipt_number' => 'CS-20260723-000001',
            'status' => 'confirmed',
        ]);

        $this->actingAs($user)
            ->get(route('orders.receipt.show', $order))
            ->assertOk()
            ->assertSee($order->receipt_number);

        $this->actingAs($user)
            ->get(route('orders.receipt.download', $order))
            ->assertOk()
            ->assertHeader('Content-Disposition', 'attachment; filename="receipt-cs-20260723-000001.html"');
    }

    public function test_another_user_cannot_access_a_receipt(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $order = Order::create([
            'user_id' => $owner->id,
            'shipping_address' => 'Beirut',
            'payment_method' => 'cash',
            'receipt_number' => 'CS-20260723-000002',
            'status' => 'confirmed',
        ]);

        $this->actingAs($otherUser)
            ->get(route('orders.receipt.show', $order))
            ->assertForbidden();
    }

    public function test_checkout_requires_clear_contact_and_delivery_details(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('checkout.index'))
            ->post(route('checkout.confirm'), [
                'phone_number' => 'not a phone',
                'shipping_address' => '',
                'delivery_city' => '',
                'payment_method' => 'cash',
            ])
            ->assertRedirect(route('checkout.index'))
            ->assertSessionHasErrors(['phone_number', 'shipping_address', 'delivery_city']);
    }
}
