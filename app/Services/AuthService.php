<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function login(array $credentials, string $guard = 'web'): bool
    {
        if (Auth::guard($guard)->attempt($credentials)) {
            return true;
        }
        return false; 
    }

    public function logout(): void
    {
        Auth::logout();
    }

    public function user()
    {
        return Auth::user();
    }
}

?>