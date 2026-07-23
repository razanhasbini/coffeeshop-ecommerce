<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedSection extends Model
{
    protected $fillable = [
        'title', 'description', 'background_color', 'title_color',
        'ticker_text', 'ticker_text_color', 'ticker_background_color', 'ticker_speed',
        'product_one_id', 'product_two_id', 'product_three_id',
    ];

    public static function defaults(): array
    {
        return [
            'title' => 'Featured Favorites',
            'description' => 'A small selection of drinks we think you’ll love.',
            'background_color' => '#f5eee5',
            'title_color' => '#1c0f07',
            'ticker_text' => 'Freshly roasted • Thoughtfully sourced • Made with love',
            'ticker_text_color' => '#f2cb83',
            'ticker_background_color' => '#1c0f07',
            'ticker_speed' => 22,
        ];
    }

    public function products()
    {
        return Product::whereIn('id', array_filter([
            $this->product_one_id,
            $this->product_two_id,
            $this->product_three_id,
        ]))->get()->sortBy(fn ($product) => array_search($product->id, [
            $this->product_one_id,
            $this->product_two_id,
            $this->product_three_id,
        ]))->values();
    }
}
