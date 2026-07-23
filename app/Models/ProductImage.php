<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'mime_type', 'image_data'];

    protected $hidden = ['image_data'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
