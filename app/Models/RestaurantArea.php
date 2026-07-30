<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class RestaurantArea extends Model
{


    protected $fillable = [
        'uuid',
        'restaurant_id',
        'name',
        'description',
        'sort_order',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function objects()
    {
        return $this->hasMany(RestaurantObject::class, 'area_id');
    }
}