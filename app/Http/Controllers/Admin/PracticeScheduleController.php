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
    public function index(Request $request)
    {
        $doctors = Doctor::orderBy('name')->get();
        $selectedDoctorId = $request->input('doctor_id');
        $viewType = $request->input('view_type', 'daily'); // 'daily' or 'weekly'
        $date = Carbon::parse($request->input('date', Carbon::today()->toDateString()));

        $practiceSchedulesQuery = PracticeSchedule::with('doctor', 'appointments'); // Load appointments

        if ($selectedDoctorId) {
            $practiceSchedulesQuery->where('doctor_id', $selectedDoctorId);
        }

        if ($viewType == 'daily') {
            $practiceSchedulesQuery->whereDate('Datetime', $date->toDateString());
        } elseif ($viewType == 'weekly') {
            $startOfWeek = $date->copy()->startOfWeek(Carbon::MONDAY)->toDateString();
            $endOfWeek = $date->copy()->endOfWeek(Carbon::SUNDAY)->toDateString();
            $practiceSchedulesQuery->whereBetween('Datetime', [$startOfWeek, $endOfWeek]);
        }

        $practiceSchedules = $practiceSchedulesQuery->orderBy('Datetime')->get();

        // Group by date for daily/weekly view
        $groupedSchedules = $practiceSchedules->groupBy(function ($schedule) {
            return Carbon::parse($schedule->Datetime)->format('Y-m-d');
        });

        $weeklySchedule = [];
        if ($viewType == 'weekly') {
            $currentDate = $date->copy()->startOfWeek(Carbon::MONDAY);
            for ($i = 0; $i < 7; $i++) {
                $day = $currentDate->format('Y-m-d');
                $weeklySchedule[$day] = $groupedSchedules[$day] ?? collect(); // Get schedules for the day or empty collection
                $currentDate->addDay();
            }
        }

        return view('admin.practice_schedules.index', compact('groupedSchedules', 'weeklySchedule', 'doctors', 'selectedDoctorId', 'viewType', 'date'));
    }

    /**
     * Show form to generate practice schedules based on DayAvailables.
     */
    public function createGenerate()
    {
        $doctors = Doctor::orderBy('name')->get();
        return view('admin.practice_schedules.generate', compact('doctors'));
    }

    /**
     * Generate practice schedules for a specific period.
     */
    public function storeGenerate(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $doctorId = $request->input('doctor_id');
        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));

        $doctor = Doctor::findOrFail($doctorId);
        $dayAvailables = $doctor->dayAvailables; // Get all day availables for this doctor

        $generatedCount = 0;
        $failedCount = 0;

        // Loop through each day from start_date to end_date
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $dayOfWeek = $currentDate->format('l'); // e.g., "Monday"

            // Find matching DayAvailable for the current day of the week
            $matchingDayAvailables = $dayAvailables->where('day', $dayOfWeek);

            foreach ($matchingDayAvailables as $dayAvailable) {
                // Combine date with start_time and end_time from DayAvailable
                $startDateTime = Carbon::parse($currentDate->format('Y-m-d') . ' ' . $dayAvailable->start_time);
                $endDateTime = Carbon::parse($currentDate->format('Y-m-d') . ' ' . $dayAvailable->end_time);

                // Assuming you want to create one practice schedule for each block,
                // or break it down into smaller slots if desired.
                // For simplicity, let's create one slot per DayAvailable block.

                try {
                    // Check if a practice schedule already exists for this exact datetime and doctor
                    PracticeSchedule::firstOrCreate(
                        [
                            'doctor_id' => $doctorId,
                            'Datetime' => $startDateTime,
                        ]
                        // No need for second array if using firstOrCreate, it only creates if not found.
                        // If you need more specific logic like splitting into 30 min slots,
                        // this section would be more complex.
                    );
                    $generatedCount++;
                } catch (\Exception $e) {
                    // Log error or handle gracefully if insertion fails (e.g., due to unique constraint)
                    $failedCount++;
                }
            }
            $currentDate->addDay();
        }

        return redirect()->route('admin.practice-schedules.index')->with('success', "Generated {$generatedCount} practice schedule slots. Failed to generate {$failedCount} slots (already exist or other errors).");
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