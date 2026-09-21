<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ModifierGroup extends Model
{
    protected $fillable = [
        'uuid',
        'name',
        'description',
        'min_selections',
        'max_selections',
        'sort_order',
        'active',
    ];

    protected $casts = [
        'min_selections' => 'integer',
        'max_selections' => 'integer',
        'active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($group) {
            if (!$group->uuid) {
                $group->uuid = (string) Str::uuid();
            }
        });
    }

    public function options()
    {
        return $this->hasMany(
            ModifierOption::class,
            'modifier_group_id'
        );
    }

public function products()
{
    return $this->belongsToMany(
        Product::class,
        'product_modifier_groups'
    )->withPivot('sort_order')
     ->orderByPivot('sort_order');
}
}