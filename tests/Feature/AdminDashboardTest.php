<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_is_redirected_to_dashboard_after_login(): void
    {
        $admin = User::factory()->create(['email' => 'admin@example.com']);
        $admin->forceFill(['is_admin' => true, 'role' => 'manager', 'is_active' => true])->save();

        $this->post('/login', ['email' => $admin->email, 'password' => 'password'])
            ->assertRedirect(route('dashboard'));

        $this->get('/dashboard')->assertOk()->assertSee('Users & Roles', false)->assertSee('Homepage Featured Section');
    }

    public function test_regular_user_cannot_open_admin_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/dashboard')->assertForbidden();
    }

    public function test_admin_can_create_a_product_with_an_image(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true, 'role' => 'manager', 'is_active' => true])->save();

        $this->actingAs($admin)->from('/dashboard')->post(route('admin.products.store'), [
            'name' => 'Velvet Espresso',
            'description' => 'A rich double espresso.',
            'price' => '4.50',
            'category' => 'Other',
            'custom_category' => 'Seasonal Specials',
            'image' => UploadedFile::fake()->image('espresso.jpg'),
        ])->assertRedirect('/dashboard')->assertSessionHasNoErrors();

        $product = Product::where('name', 'Velvet Espresso')->firstOrFail();
        $this->assertSame('/products/'.$product->id.'/image', $product->image_url);
        $this->assertTrue(ProductImage::where('product_id', $product->id)->exists());
        $this->assertSame('Seasonal Specials', $product->category);
    }
}
