<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public const CATEGORIES = [
        'Coffee',
        'Iced Coffee',
        'Hot Drinks',
        'Tea',
        'Smoothies',
        'Milkshakes',
        'Desserts',
        'Cakes',
        'Pastries',
    ];

    protected $fillable = ['name', 'description', 'price', 'category', 'image_url'];
}
