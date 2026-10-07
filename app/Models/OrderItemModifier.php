<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrderItemModifier extends Model
{
    protected $fillable = [
    'uuid',
    'order_item_id',
    'modifier_option_id',
    'modifier_name',
    'price_adjustment',
    'tax_rate',
    'tax_amount',
    'quantity',
    'total',
];

    protected $casts = [
        'price_adjustment' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'quantity' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function ($modifier) {
            if (!$modifier->uuid) {
                $modifier->uuid = (string) Str::uuid();
            }
        });
    }

    public function orderItem()
    {
        return $this->belongsTo(
            OrderItem::class,
            'order_item_id'
        );
    }

    public function modifierOption()
    {
        return $this->belongsTo(
            ModifierOption::class,
            'modifier_option_id'
        );
    }
}