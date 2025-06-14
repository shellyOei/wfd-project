<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // protected $userRepository;

    // public function __construct(UserRepositoryInterface $userRepository)
    // {
    //     $this->userRepository = $userRepository;
    // }

    // --- register account for user ---
    public function showRegistrationForm()
    {
        return view('auth.registerUser');
    }

    public function registerUser(RegisterUserRequest $r)
    {
        $valid = $r->validated();

        $user = User::create($valid);

        // Log the user in
        auth()->guard('user')->login($user);

        return redirect()->route('login')->with('success', 'Registration successful!');
    }
}
