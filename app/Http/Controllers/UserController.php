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

    public function index()
    {
        $users = $this->userRepo->withTrashed()->get()
            ->load('profiles.patient');
            // ->loadCount([
            //     'profiles as patients_count' => function ($query) {
            //         $query->whereNotNull('patient_id');
            //     }
            // ]);

        return view('admin.users', compact('users'));
    }
    public function activate($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            $user->restore(); // undo soft delete
            return response()->json([
                'success' => true,
                'message' => 'User account activated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to activate user: ' . $e->getMessage()
            ], 500);
        }
    }
    public function deactivate(User $user)
    {
        try {
            $user->delete();
            return response()->json([
                'success' => true,
                'message' => 'User deactivated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deactivating the user: ' . $e->getMessage()
            ], 500);
        }
    }
    public function destroy(User $user)
    {
        try {
            $user->forceDelete();
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the user: ' . $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'email' => 'email|unique:users,email,'
        ]);

        $user = User::findOrFail($id);

        $user->email = $request->email;
        $user->save();

        return response()->json([
                'success' => true,
                'message' => 'Email updated successfully!'
        ]);
    }
}