<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Exports\UsersExport;
// use App\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

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


    public function showEditAccount()
    {
        $user = auth()->guard('user')->user();
        return view('user.profile.editAccount', ['user' => $user]);

    }
    public function updateSelf(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . auth()->guard('user')->id(),
        ]);

        $user = auth()->guard('user')->user();
        $user->name = $request->name;
        $user->email = $request->email;

        $user->save();
        auth()->guard('user')->setUser($user);

        return response()->json(['message' => 'Akun berhasil diperbarui.']);
    }
    public function deactivateSelf(Request $request)
    {
        try {
            $user = auth()->guard('user')->user();
            $user->delete();
            return response()->json([
                'success' => true,
                'message' => 'User berhasil dinonaktifkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menonaktifkan akun: ' . $e->getMessage()
            ], 500);
        }
    }
    public function destroySelf(Request $request)
    {
        try {
            $user = auth()->guard('user')->user();
            // $user->forceDelete();
            $user->delete();
            return response()->json([
                'success' => true,
                'message' => 'Akun berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus akun: ' . $e->getMessage()
            ], 500);
        }
    }


    // Admin functions
    public function registerAccount(RegisterUserRequest $request)
    {
        $validated = $request->validated();
        // $user = $this->userRepo->create($request->all());

        $user = User::create($validated);
        auth()->guard('user')->login($user);

        return redirect()->route('user.dashboard')->with('success', 'Berhasil registrasi akun!');
    }

    public function users()
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
                'message' => 'Akun berhasil diaktifkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengaktifkan akun: ' . $e->getMessage()
            ], 500);
        }
    }
    public function deactivate(User $user)
    {
        try {
            $user->delete();
            return response()->json([
                'success' => true,
                'message' => 'User berhasil dinonaktifkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menonaktifkan akun: ' . $e->getMessage()
            ], 500);
        }
    }
    public function destroy(User $user)
    {
        try {
            $user->forceDelete();
            return response()->json([
                'success' => true,
                'message' => 'Akun berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus akun: ' . $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully!'
        ]);
    }

    /**
     * Export users to Excel/CSV
     */
    public function export()
    {
        try {
            // Try Excel export first
            if (class_exists('Maatwebsite\Excel\Facades\Excel')) {
                $fileName = 'users_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
                return Excel::download(new UsersExport, $fileName);
            } else {
                // Fallback to CSV export
                return $this->exportCSV();
            }
        } catch (\Exception) {
            // Fallback to CSV if Excel fails
            return $this->exportCSV();
        }
    }

    /**
     * Export users to CSV (fallback method)
     */
    private function exportCSV()
    {
        $users = User::with(['patients', 'profiles'])
                    ->withTrashed()
                    ->orderBy('created_at', 'desc')
                    ->get();

        $fileName = 'users_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'User ID',
                'Name',
                'Email',
                'Linked Patients',
                'Patient Names',
                'Total Linked Patients',
                'Account Status',
                'Registration Date',
                'Last Updated',
                'Deleted Date'
            ]);

            // Add user data
            foreach ($users as $user) {
                $linkedPatients = $user->patients;
                $patientNames = $linkedPatients->pluck('name')->implode(', ');
                $totalLinkedPatients = $linkedPatients->count();
                
                if ($totalLinkedPatients === 0) {
                    $patientNames = 'No patients linked';
                }
                
                $accountStatus = $user->deleted_at ? 'Deactivated' : 'Active';

                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $totalLinkedPatients > 0 ? 'Yes' : 'No',
                    $patientNames,
                    $totalLinkedPatients,
                    $accountStatus,
                    $user->created_at->format('Y-m-d H:i:s'),
                    $user->updated_at->format('Y-m-d H:i:s'),
                    $user->deleted_at ? $user->deleted_at->format('Y-m-d H:i:s') : 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}