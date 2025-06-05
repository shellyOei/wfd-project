<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Invoice;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        // Get statistics
        $totalDoctors = Doctor::count();
        $totalPatients = Patient::count();
        $todayAppointments = Appointment::whereHas('schedule', function($query) {
            $query->whereDate('Datetime', Carbon::today());
        })->count();

        // Calculate monthly revenue
        $monthlyRevenue = Invoice::whereMonth('created_at', Carbon::now()->month)
                                ->whereYear('created_at', Carbon::now()->year)
                                ->sum('total_price');

        // Get recent appointments with relationships
        $recentAppointments = Appointment::with(['patient', 'schedule.doctor'])
                                        ->orderBy('created_at', 'desc')
                                        ->limit(5)
                                        ->get();

        // Get pending appointments count
        $pendingAppointments = Appointment::whereHas('schedule', function($query) {
            $query->where('Datetime', '>', Carbon::now());
        })->count();

        return view('admin.dashboard', compact(
            'totalDoctors',
            'totalPatients',
            'todayAppointments',
            'monthlyRevenue',
            'recentAppointments',
            'pendingAppointments'
        ));
    }

    public function patients()
    {
        $patients = Patient::with(['profiles.user', 'appointments'])
                          ->orderBy('created_at', 'desc')
                          ->get();

        return view('admin.patients', compact('patients'));
    }

    public function updatePatient(Request $request, Patient $patient)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'sex' => 'required|in:male,female',
            'date_of_birth' => 'required|date',
            'address' => 'required|string',
            'occupation' => 'required|string|max:255',
            'blood_type' => 'nullable|string|max:5',
            'rhesus_factor' => 'nullable|string|max:5',
            'id_card_number' => 'required|string|max:20',
            'BPJS_number' => 'nullable|string|max:20',
        ]);

        $patient->update($request->all());

        return redirect()->route('admin.patients')->with('success', 'Patient updated successfully!');
    }
}
