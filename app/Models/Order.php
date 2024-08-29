<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $primaryKey = 'id';

    protected $fillable = [
        'order_code',
        'user_id',
        'product_id',
        'total_amount',
        'payment_status'
    ];

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            $order->order_code = strtoupper(uniqid('ORD-'));
        });
    }
}
