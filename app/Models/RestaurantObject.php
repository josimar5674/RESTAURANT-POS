<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RestaurantObject extends Model
{
    protected $fillable = [

        'uuid',

        'type',

        'name',

        'x',

        'y',

        'rotation',

        'width',

        'height',

        'shape',

        'style',

        'properties',
        'area_id',

    ];

    protected $casts = [

        'properties' => 'array',

    ];

    protected static function booted()
    {
        static::creating(function ($object) {

            $object->uuid = (string) Str::uuid();

        });
    }

    public function area()
{
    return $this->belongsTo(RestaurantArea::class, 'area_id');
}
}