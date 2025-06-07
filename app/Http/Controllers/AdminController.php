<?php

namespace App\Http\Controllers;

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


}
