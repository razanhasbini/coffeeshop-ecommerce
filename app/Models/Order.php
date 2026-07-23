<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone_number',
        'shipping_address',
        'delivery_city',
        'delivery_notes',
        'payment_method',
        'receipt_number',
        'status',
        'confirmation_email_sent_at',
    ];

    protected $casts = [
        'confirmation_email_sent_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function total(): float
    {
        return (float) $this->items->sum(
            fn (OrderItem $item) => (float) $item->price * $item->quantity
        );
    }
}
