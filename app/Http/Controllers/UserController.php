<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
// use App\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // protected $userRepo;

    // public function __construct(UserRepositoryInterface $userRepo)
    // {
    //     $this->userRepo = $userRepo;
    // }

    public function showRegist()
    {
        return view('auth.register'); 
    }

    public function showLogin()
    {
        return view('auth.login'); 
    }

    public function index()
    {
        return view('user.dashboard'); 
    }

    public function registerAccount(RegisterUserRequest $request)
    {
        $validated = $request->validated();
        // $user = $this->userRepo->create($request->all());

        $user = User::create($validated);
        auth()->login($user);

        return redirect()->route('user.dashboard')->with('success', 'Berhasil registrasi akun!');
    }
}
