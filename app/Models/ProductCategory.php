<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductCategory extends Model
{
    protected $fillable = [
        'uuid',
        'name',
        'description',
        'sort_order',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($category) {
            if (!$category->uuid) {
                $category->uuid = (string) Str::uuid();
            }
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}