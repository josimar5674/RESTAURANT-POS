<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'uuid',
        'category_id',
        'name',
        'description',
        'image',
        'sort_order',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($product) {
            if (!$product->uuid) {
                $product->uuid = (string) Str::uuid();
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(
            ProductCategory::class,
            'category_id'
        );
    }

    public function variants()
    {
        return $this->hasMany(
            ProductVariant::class,
            'product_id'
        );
    }

public function modifierGroups()
{
    return $this->belongsToMany(
        ModifierGroup::class,
        'product_modifier_groups'
    )
    ->using(ProductModifierGroup::class)
    ->withPivot('uuid', 'sort_order')
    ->orderByPivot('sort_order');
}

}