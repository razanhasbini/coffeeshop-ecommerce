<?php

namespace Database\Seeders;

use App\Models\FeaturedSection;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CatalogProductSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = [
            [
                'name' => 'Strawberry Banana Cloud',
                'description' => 'Ripe strawberries and banana blended until silky, creamy, and naturally sweet.',
                'price' => 6.50,
                'category' => 'Smoothies',
                'image_url' => '/images/strawberry-banana-smoothie.jpg',
            ],
            [
                'name' => 'Mango Passion Glow',
                'description' => 'Mango and passionfruit blended into a bright tropical smoothie with a smooth finish.',
                'price' => 6.75,
                'category' => 'Smoothies',
                'image_url' => '/images/mango-passion-smoothie.jpg',
            ],
            [
                'name' => 'Vanilla Bean Milkshake',
                'description' => 'A thick, velvety vanilla shake finished with real vanilla bean and soft whipped cream.',
                'price' => 7.00,
                'category' => 'Milkshakes',
                'image_url' => '/images/vanilla-bean-milkshake.jpg',
            ],
            [
                'name' => 'Dark Chocolate Milkshake',
                'description' => 'Deep cocoa, cold milk, and a restrained dark-chocolate drizzle in one rich shake.',
                'price' => 7.50,
                'category' => 'Milkshakes',
                'image_url' => '/images/dark-chocolate-milkshake.jpg',
            ],
            [
                'name' => 'Pistachio Matcha Latte',
                'description' => 'Ceremonial matcha layered over chilled pistachio milk for a balanced, nutty finish.',
                'price' => 6.95,
                'category' => 'Tea',
                'image_url' => '/images/pistachio-matcha-latte.jpg',
            ],
            [
                'name' => 'Salted Caramel Iced Latte',
                'description' => 'Espresso, cold milk, and a subtle salted-caramel ribbon poured over ice.',
                'price' => 5.75,
                'category' => 'Iced Coffee',
                'image_url' => '/images/salted-caramel-iced-latte.jpg',
            ],
            [
                'name' => 'Basque Burnt Cheesecake',
                'description' => 'Caramelized on top with a softly set, custardy center and delicate vanilla finish.',
                'price' => 6.25,
                'category' => 'Cakes',
                'image_url' => '/images/basque-cheesecake.jpg',
            ],
            [
                'name' => 'Artisan Almond Croissant',
                'description' => 'A crisp, buttery laminated pastry filled with almond cream and toasted almond flakes.',
                'price' => 4.95,
                'category' => 'Pastries',
                'image_url' => '/images/almond-croissant.jpg',
            ],
        ];

        $products = collect($catalog)->mapWithKeys(function (array $item) {
            $product = Product::updateOrCreate(
                ['name' => $item['name']],
                $item,
            );

            return [$product->name => $product];
        });

        FeaturedSection::firstOrCreate([], [
            ...FeaturedSection::defaults(),
            'product_one_id' => $products['Strawberry Banana Cloud']->id,
            'product_two_id' => $products['Salted Caramel Iced Latte']->id,
            'product_three_id' => $products['Basque Burnt Cheesecake']->id,
        ]);
    }
}
