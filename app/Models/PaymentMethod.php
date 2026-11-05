<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PaymentMethod extends Model
{
    protected $fillable = [
        'uuid',
        'name',
        'code',
        'requires_reference',
        'active',
        'sort_order',
    ];

    protected $casts = [
        'requires_reference' => 'boolean',
        'active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function ($paymentMethod) {
            if (!$paymentMethod->uuid) {
                $paymentMethod->uuid = (string) Str::uuid();
            }

            if (!$paymentMethod->code) {
                $paymentMethod->code = Str::slug(
                    $paymentMethod->name,
                    '_'
                );
            }
        });
    }
}