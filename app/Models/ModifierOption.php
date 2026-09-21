<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ModifierOption extends Model
{
    protected $fillable = [
        'uuid',
        'modifier_group_id',
        'name',
        'price_adjustment',
        'sort_order',
        'active',
    ];

    protected $casts = [
        'price_adjustment' => 'decimal:2',
        'sort_order' => 'integer',
        'active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($option) {
            if (!$option->uuid) {
                $option->uuid = (string) Str::uuid();
            }
        });
    }

    public function modifierGroup()
    {
        return $this->belongsTo(
            ModifierGroup::class,
            'modifier_group_id'
        );
    }
}