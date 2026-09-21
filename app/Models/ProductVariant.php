<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductVariant extends Model
{
    protected $fillable = [
        'uuid',
        'product_id',
        'name',
        'price',
        'sku',
        'sort_order',
        'active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($variant) {
            if (!$variant->uuid) {
                $variant->uuid = (string) Str::uuid();
            }
        });
    }

    public function product()
    {
        return $this->belongsTo(
            Product::class,
            'product_id'
        );
    }
}