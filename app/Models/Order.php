<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'uuid',
        'table_id',
        'user_id',
        'status',
        'subtotal',
        'discount',
        'tax',
        'total',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function ($order) {
            if (!$order->uuid) {
                $order->uuid = (string) Str::uuid();
            }
        });
    }

    public function table()
    {
        return $this->belongsTo(
            RestaurantObject::class,
            'table_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public function items()
    {
        return $this->hasMany(
            OrderItem::class,
            'order_id'
        );
    }
}