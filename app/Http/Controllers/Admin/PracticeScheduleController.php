<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DayAvailable; // Jika diperlukan untuk generate
use App\Models\PracticeSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PracticeScheduleController extends Controller
{
    /**
     * Display a listing of practice schedules (reservations).
     * This will act as the "Melihat jadwal reservasi dokter harian/mingguan."
     */
    // app/Http/Controllers/Admin/PracticeScheduleController.php

public function index(Request $request)
{
    // [OPTIMIZED] Eager load specialization untuk dropdown filter
    $doctors = Doctor::with('specialization')->orderBy('name')->get();

    $selectedDoctorId = $request->input('doctor_id');
    $viewType = $request->input('view_type', 'daily');
    $date = Carbon::parse($request->input('date', Carbon::today()->toDateString()));

    // [OPTIMIZED] Eager load relasi bersarang 'doctor.specialization' dan 'appointments'
    $practiceSchedulesQuery = PracticeSchedule::with(['doctor.specialization', 'appointments']);

    // [FIXED] Menggunakan nama tabel 'doctors.id' untuk menghindari ambiguitas
    if ($selectedDoctorId) {
        $practiceSchedulesQuery->whereHas('doctor', function ($query) use ($selectedDoctorId) {
            $query->where('doctors.id', $selectedDoctorId);
        });
    }

    if ($viewType == 'daily') {
        $practiceSchedulesQuery->whereDate('Datetime', $date->toDateString());
    } elseif ($viewType == 'weekly') {
        $startOfWeek = $date->copy()->startOfWeek(Carbon::MONDAY)->toDateString();
        $endOfWeek = $date->copy()->endOfWeek(Carbon::SUNDAY)->toDateString();
        $practiceSchedulesQuery->whereBetween('Datetime', [$startOfWeek, $endOfWeek]);
    }

    $practiceSchedules = $practiceSchedulesQuery->orderBy('Datetime')->get();

    $groupedSchedules = $practiceSchedules->groupBy(function ($schedule) {
        return $schedule->Datetime->format('Y-m-d');
    });

    $weeklySchedule = [];
    if ($viewType == 'weekly') {
        $currentDate = $date->copy()->startOfWeek(Carbon::MONDAY);
        for ($i = 0; $i < 7; $i++) {
            $day = $currentDate->format('Y-m-d');
            $weeklySchedule[$day] = $groupedSchedules->get($day, collect());
            $currentDate->addDay();
        }
    }

    // dd( $groupedSchedules, $weeklySchedule);

    return view('admin.practice_schedules.index', compact('groupedSchedules', 'weeklySchedule', 'doctors', 'selectedDoctorId', 'viewType', 'date'));
}
 
    /**
     * Option to delete a specific practice schedule slot.
     * Use with caution as these might be linked to appointments.
     */
    public function destroy(PracticeSchedule $practiceSchedule)
    {
        // Add logic here to check if there are appointments linked before deleting
        if ($practiceSchedule->appointments()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete practice schedule: There are existing appointments linked to this slot.');
        }

        $practiceSchedule->delete();
        return redirect()->route('admin.practice-schedules.index')->with('success', 'Practice schedule slot deleted successfully.');
    }

    // You might also want an 'edit' for individual practice schedules,
    // but typically these are managed by generation or appointment booking.
    // For now, I'll omit edit/update for individual practice schedules to keep it simpler.
}