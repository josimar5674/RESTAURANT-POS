<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Str;

class ProductModifierGroup extends Pivot
{
    protected $table = 'product_modifier_groups';

    protected $fillable = [
        'uuid',
        'product_id',
        'modifier_group_id',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function ($pivot) {
            if (!$pivot->uuid) {
                $pivot->uuid = (string) Str::uuid();
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

    public function modifierGroup()
    {
        return $this->belongsTo(
            ModifierGroup::class,
            'modifier_group_id'
        );
    }
}