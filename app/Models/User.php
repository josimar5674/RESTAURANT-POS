<?php

namespace App\Models;

use Illuminate\Support\Str;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;

    protected $fillable = [
        'uuid',
        'first_name',
        'last_name',
        'email',
        'phone',
        'photo',
        'password',
        'pin',
        'active',
        'locale',
        'theme',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'pin',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login' => 'datetime',
            'active' => 'boolean',
            'password' => 'hashed',
            'pin' => 'hashed',
        ];
    }

protected static function booted(): void
{
    static::creating(function ($user) {
        $user->uuid = (string) Str::uuid();
    });
}
}