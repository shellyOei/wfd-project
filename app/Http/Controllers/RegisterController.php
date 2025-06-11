<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.registerUser');
    }

    public function registerUser(RegisterUserRequest $r)
    {
        $valid = $r->validated();

         $user = User::create([
            'name' => $valid['name'], 
            'email' => $valid['email'], 
            'phone' => $valid['phone'], 
            'password' => Hash::make($valid['password']), 
        ]);

        // Log the user in
        auth()->guard('user')->login($user);

        return redirect()->route('login')->with('success', 'Registration successful!');
    }
}
