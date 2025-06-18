<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Specialization;
use App\Exports\AdminsExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function index()
    {
        // Get statistics
        $totalDoctors = Doctor::count();
        $totalPatients = Patient::count();
        $todayAppointments = Appointment::whereHas('schedule', function ($query) {
            $query->whereDate('Datetime', Carbon::today());
        })->count();

        // Calculate monthly revenue
        $monthlyRevenue = Invoice::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_price');

        // Get recent appointments with relationships
        $recentAppointments = Appointment::with(['patient', 'schedule.dayAvailable.doctor'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get pending appointments count
        $pendingAppointments = Appointment::whereHas('schedule', function ($query) {
            $query->where('Datetime', '>', Carbon::now());
        })->count();

        // Chart Data: Appointments over last 7 days
        $appointmentsChartData = $this->getAppointmentsChartData();

        // Chart Data: Patients by Department (Specialization)
        $patientsByDepartmentData = $this->getPatientsByDepartmentData();

        // Chart Data: Monthly Revenue for last 6 months
        $revenueChartData = $this->getRevenueChartData();

        // Chart Data: Patient Registration Trends (last 6 months)
        $patientRegistrationData = $this->getPatientRegistrationData();

        return view('admin.dashboard', compact(
            'totalDoctors',
            'totalPatients',
            'todayAppointments',
            'monthlyRevenue',
            'recentAppointments',
            'pendingAppointments',
            'appointmentsChartData',
            'patientsByDepartmentData',
            'revenueChartData',
            'patientRegistrationData'
        ));
    }

    /**
     * Get appointments chart data for the last 7 days
     */
    private function getAppointmentsChartData()
    {
        $labels = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('M d');

            $count = Appointment::whereHas('schedule', function ($query) use ($date) {
                $query->whereDate('Datetime', $date);
            })->count();

            $data[] = $count;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * Get patients by department (specialization) data
     */
    private function getPatientsByDepartmentData()
    {
        $specializations = Specialization::with('doctors')->get();

        $labels = [];
        $data = [];
        $colors = ['#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899', '#06B6D4', '#84CC16'];

        foreach ($specializations as $specialization) {
            if ($specialization->doctors->count() > 0) {
                // Count unique patients who have appointments with doctors from this specialization
                $patientCount = Patient::whereHas('appointments.schedule.dayAvailable.doctor', function ($query) use ($specialization) {
                    $query->where('specialization_id', $specialization->id);
                })->distinct()->count();

                if ($patientCount > 0) {
                    $labels[] = $specialization->name;
                    $data[] = $patientCount;
                }
            }
        }

        // If no data found, provide some default data to prevent empty chart
        if (empty($labels)) {
            $labels = ['General Medicine', 'Cardiology', 'Pediatrics'];
            $data = [0, 0, 0];
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => array_slice($colors, 0, count($labels))
        ];
    }

    /**
     * Get revenue chart data for the last 6 months
     */
    private function getRevenueChartData()
    {
        $labels = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M Y');

            $revenue = Invoice::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('total_price');

            $data[] = $revenue;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * Get patient registration data for the last 6 months
     */
    private function getPatientRegistrationData()
    {
        $labels = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M Y');

            $count = Patient::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();

            $data[] = $count;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    public function manageAdmins()
    {
        $admins = Admin::with('doctor')->withTrashed()->get();

        return view('admin.admins', compact('admins'));
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:admins,email',
                'password' => 'required|string|min:8',
                'doctor_id' => 'nullable|exists:doctors,id|unique:admins,doctor_id',
            ]);

            Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'doctor_id' => $request->doctor_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Admin account created successfully.',
            ]);
        } catch (ValidationException $e) {
            if ($e->errors()['doctor_id'] ?? false) {
                return response()->json([
                    'success' => false,
                    'message' => 'This doctor is already linked to another admin account.',
                ], 422);
            }

            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create admin account: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, Admin $admin)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:admins,email,' . $admin->id,
                'doctor_id' => 'nullable|exists:doctors,id|unique:admins,doctor_id',
            ]);

            $admin->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'doctor_id' => $validated['doctor_id'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Admin account updated successfully.',
            ]);
        } catch (ValidationException $e) {
            if ($e->errors()['doctor_id'] ?? false) {
                return response()->json([
                    'success' => false,
                    'message' => 'This doctor is already linked to another admin account.',
                ], 422);
            }

            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the admin account.',
            ], 500);
        }
    }

    public function activate($id)
    {
        try {
            $admin = Admin::withTrashed()->findOrFail($id);
            $admin->restore(); // undo soft delete
            return response()->json([
                'success' => true,
                'message' => 'Admin account activated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to activate admin: ' . $e->getMessage()
            ], 500);
        }
    }
    public function deactivate(Admin $admin)
    {
        try {
            $admin->delete();
            return response()->json([
                'success' => true,
                'message' => 'Admin deactivated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deactivating the admin: ' . $e->getMessage()
            ], 500);
        }
    }
    public function destroy(Admin $admin)
    {
        try {
            $admin->forceDelete();
            return response()->json([
                'success' => true,
                'message' => 'Admin deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the admin: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export admins to Excel/CSV
     */
    public function export()
    {
        try {
            // Try Excel export first
            if (class_exists('Maatwebsite\Excel\Facades\Excel')) {
                $fileName = 'admins_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
                return Excel::download(new AdminsExport, $fileName);
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
     * Export admins to CSV (fallback method)
     */
    private function exportCSV()
    {
        $admins = Admin::with(['doctor.specialization'])
                      ->withTrashed()
                      ->orderBy('created_at', 'desc')
                      ->get();

        $fileName = 'admins_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($admins) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'Admin ID',
                'Name',
                'Email',
                'Role Type',
                'Connected Doctor',
                'Doctor Specialization',
                'Doctor Phone',
                'Account Status',
                'Created Date',
                'Last Updated',
                'Deleted Date'
            ]);

            // Add admin data
            foreach ($admins as $admin) {
                $roleType = $admin->isSuperAdmin() ? 'Super Admin' : 'Doctor Admin';
                $doctorName = $admin->doctor ? $admin->doctor->name : 'Not Connected';
                $doctorSpecialization = $admin->doctor && $admin->doctor->specialization 
                    ? $admin->doctor->specialization->name 
                    : 'N/A';
                $doctorPhone = $admin->doctor ? $admin->doctor->phone : 'N/A';
                $accountStatus = $admin->deleted_at ? 'Deactivated' : 'Active';

                fputcsv($file, [
                    $admin->id,
                    $admin->name,
                    $admin->email,
                    $roleType,
                    $doctorName,
                    $doctorSpecialization,
                    $doctorPhone,
                    $accountStatus,
                    $admin->created_at->format('Y-m-d H:i:s'),
                    $admin->updated_at->format('Y-m-d H:i:s'),
                    $admin->deleted_at ? $admin->deleted_at->format('Y-m-d H:i:s') : 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
