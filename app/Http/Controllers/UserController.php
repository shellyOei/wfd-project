<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Repositories\UserRepository;
use App\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function showRegist()
    {
        return view('auth.register'); 
    }

    public function showLogin()
    {
        return view('auth.login'); 
    }

    public function registerAccount(RegisterUserRequest $request)
    {
        $validated = $request->validated();
        $user = $this->userRepo->create($request->all());

        // auth()->login($user);

        return redirect()->route('user.dashboard')->with('success', 'Berhasil registrasi akun!');
    }
}
