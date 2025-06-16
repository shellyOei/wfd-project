<?php

namespace App\Services;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AuthService
{
    public function login(array $credentials, string $guard = 'user'): bool
    {
        if (Auth::guard($guard)->attempt($credentials)) {
            return true;
        }
        return false; 
    }

    public function logout(Request $r, $guard)
    {
        Auth::guard($guard)->logout();
        $r->session()->invalidate(); 
        $r->session()->regenerateToken(); 
    }

    public function user($guard)
    {
        return Auth::guard($guard)->user();
    }
}

?>