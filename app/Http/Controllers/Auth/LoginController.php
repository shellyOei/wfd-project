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

    public function showAdmin()
    {
        return view('admin.login');
    }

    public function showUser()
    {
        return view('auth.login');
    }

    public function loginAsUser(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $user = \App\Models\User::withTrashed()->where('email', $credentials['email'])->first();

        // Activate user klo sebelumnya inactive
        if ($user->trashed()) {
            $user->restore();
        }
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
            session()->put('doctor_id', $admin->doctor_id);

            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Invalid credentials!');
    }

    public function logout(Request $request)
    {
        if (auth()->guard('admin')->check()) {
            $this->authService->logout($request, 'admin');
            return redirect()->route('admin.login')->with('success', 'Berhasil logout sebagai admin!');
        }

        if (auth()->guard('user')->check()) {
            $this->authService->logout($request, 'user');
            return redirect()->route('login')->with('success', 'Berhasil logout sebagai user!');
        }

        // Default fallback kalau tidak ada yang login
        return redirect('/')->with('warning', 'Anda belum login.');
    }

}
