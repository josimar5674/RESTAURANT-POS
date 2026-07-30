<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\LoginService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct(
        private LoginService $loginService
    ) {}

    /**
     * Mostrar el formulario de login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Procesar el inicio de sesión.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $authenticated = $this->loginService->login(
            $request->only('email', 'password')
        );

        if (! $authenticated) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Las credenciales son incorrectas.',
                ]);
        }

        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }
}