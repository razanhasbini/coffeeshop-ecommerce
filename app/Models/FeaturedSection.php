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
