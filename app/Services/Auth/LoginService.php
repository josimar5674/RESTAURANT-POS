<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Auth;

class LoginService
{
    /**
     * Autenticar un usuario.
     */
public function login(array $credentials): bool
{
    return Auth::attempt([
        'email'    => $credentials['email'],
        'password' => $credentials['password'],
        'active'   => true,
    ]);
}
}