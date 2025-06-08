<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Specialization;
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

            $count = Appointment::whereHas('schedule', function($query) use ($date) {
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
                $patientCount = Patient::whereHas('appointments.schedule.doctor', function($query) use ($specialization) {
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
}
