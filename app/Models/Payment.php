<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    protected $fillable = [
        'uuid',
        'order_id',
        'checkout_account_id',
        'user_id',
        'method',
        'amount',
        'reference',
        'notes',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($payment) {
            if (!$payment->uuid) {
                $payment->uuid = (string) Str::uuid();
            }
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function checkoutAccount()
    {
        return $this->belongsTo(CheckoutAccount::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}