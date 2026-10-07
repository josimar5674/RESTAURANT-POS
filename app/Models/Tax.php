<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tax extends Model
{
    protected $fillable = [
        'uuid',
        'name',
        'code',
        'rate',
        'description',
        'sort_order',
        'active',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'sort_order' => 'integer',
        'active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($tax) {
            if (!$tax->uuid) {
                $tax->uuid = (string) Str::uuid();
            }
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'tax_id');
    }
}