<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showAdminLogin()
    {
        return view('admin.login');
    }

    public function showUserLogin()
    {
        return view('auth.login');
    }

    public function loginAsUser(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($this->authService->login($credentials, 'user')) {
            return redirect()->route('user.dashboard');
        }

        return back()->with('error', 'Invalid credentials!');
    }

    public function loginAsAdmin(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if ($this->authService->login($credentials, 'admin')) {
            // Get the authenticated admin user
            $admin = auth()->guard('admin')->user();

            // Store admin information in session
            session()->put('name', $admin->name);
            session()->put('email', $admin->email);

            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Invalid credentials!');
    }

    public function logout()
    {
        $this->authService->logout();
        return redirect()->route('admin.login')->with('success', 'Logged out successfully!');
    }
}
