<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrderItem extends Model
{
    protected $fillable = [
        'uuid',
        'order_id',
        'product_id',
        'variant_id',
        'product_name',
        'variant_name',
        'quantity',
        'unit_price',
        'tax_rate',
        'tax_amount',
        'discount',
        'subtotal',
        'total',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function ($item) {
            if (!$item->uuid) {
                $item->uuid = (string) Str::uuid();
            }
        });
    }

    public function order()
    {
        return $this->belongsTo(
            Order::class,
            'order_id'
        );
    }

    public function product()
    {
        return $this->belongsTo(
            Product::class,
            'product_id'
        );
    }

    public function variant()
    {
        return $this->belongsTo(
            ProductVariant::class,
            'variant_id'
        );
    }

    public function modifiers()
    {
        return $this->hasMany(
            OrderItemModifier::class,
            'order_item_id'
        );
    }
}