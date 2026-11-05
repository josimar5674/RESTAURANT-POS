<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CheckoutAccount extends Model
{
    protected $fillable = [
        'uuid',
        'table_id',
        'user_id',
        'name',
        'status',
        'subtotal',
        'discount',
        'tax',
        'total',
        'paid_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($account) {
            if (!$account->uuid) {
                $account->uuid = (string) Str::uuid();
            }
        });
    }

    public function table()
    {
        return $this->belongsTo(
            RestaurantObject::class,
            'table_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function orders()
{
    return $this->belongsToMany(
        Order::class,
        'checkout_account_orders',
        'checkout_account_id',
        'order_id'
    )->withTimestamps();
}
}