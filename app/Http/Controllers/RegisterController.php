<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

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
        try {
            $valid = $r->validated();
            $user = User::create($valid);

            auth()->login($user);
            return response()->json(['success' => true, 'message' => 'Registrasi akun berhasil', 'redirect' => route('user.dashboard')]);

        } catch (ValidationException $e) {

            return response()->json(['success' => false, 'message' => 'Validasi gagal.', 'errors' => $e->errors()], 422); 
        } catch (\Exception $e) {
            
            Log::error('Registrasi akun gagal: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan registrasi akun: ' . $e->getMessage()
            ], 500); 
        }
    }
}
