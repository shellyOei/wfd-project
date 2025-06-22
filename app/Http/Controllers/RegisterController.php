<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    // --- register account for user ---
    public function show()
    {
        return view('auth.registerUser');
    }

    public function store(RegisterUserRequest $r)
    {
        $valid = $r->validated();

        try {
            $user = User::create($valid);
        
            Auth::guard('user')->login($user);

            return response()->json([
                'success' => true, 
                'message' => 'Registrasi akun berhasil', 
                'redirect' => route('home')
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Validasi gagal.', 
                'errors' => $e->errors()], 422); 

        } catch (\Exception $e) {
            Log::error('Registrasi akun gagal: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan registrasi akun: ' . $e->getMessage()
            ], 500); 

        }
    }
}
