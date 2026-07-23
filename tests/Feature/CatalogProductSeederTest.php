<?php

namespace Tests\Feature;

use Database\Seeders\CatalogProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogProductSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_seeding_is_complete_and_idempotent(): void
    {
        $this->seed(CatalogProductSeeder::class);
        $this->seed(CatalogProductSeeder::class);

        $this->assertDatabaseCount('products', 8);
        $this->assertDatabaseHas('products', [
            'name' => 'Strawberry Banana Cloud',
            'category' => 'Smoothies',
            'image_url' => '/images/strawberry-banana-smoothie.jpg',
        ]);
        $this->assertDatabaseHas('products', [
            'name' => 'Dark Chocolate Milkshake',
            'category' => 'Milkshakes',
        ]);
        $this->assertDatabaseCount('featured_sections', 1);
    }

    public function test_every_seeded_product_image_is_packaged_with_the_site(): void
    {
        $this->seed(CatalogProductSeeder::class);

        foreach (\App\Models\Product::all() as $product) {
            $this->assertFileExists(public_path(ltrim($product->image_url, '/')));
        }
    }
}
