<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_menu_exposes_the_expanded_product_categories(): void
    {
        $this->get('/products')
            ->assertOk()
            ->assertSee('Iced Coffee')
            ->assertSee('Smoothies')
            ->assertSee('Milkshakes')
            ->assertSee('Desserts')
            ->assertSee('Cakes');
    }

    public function test_category_search_and_price_filters_work_together(): void
    {
        Product::create(['name' => 'Berry Blast', 'description' => 'Fresh berry smoothie', 'price' => 6.50, 'category' => 'Smoothies']);
        Product::create(['name' => 'Mango Glow', 'description' => 'Mango smoothie', 'price' => 5.50, 'category' => 'Smoothies']);
        Product::create(['name' => 'Iced Latte', 'description' => 'Cold espresso', 'price' => 4.50, 'category' => 'Iced Coffee']);

        $response = $this->get('/products?category=smoothies&search=mango&price=low');

        $response->assertOk()->assertSee('Mango Glow')->assertDontSee('Berry Blast')->assertDontSee('Iced Latte');
    }
}
