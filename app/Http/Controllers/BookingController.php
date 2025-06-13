<?php

namespace App\Http\Controllers;

use App\Models\DayAvailable;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
   public function booking(Request $request)
{
        $dates = [
        ['day' => '8', 'label' => 'Mon'],
        ['day' => '9', 'label' => 'Tue'],
        ['day' => '10', 'label' => 'Wed'],
        ['day' => '11', 'label' => 'Thu'],
        ['day' => '12', 'label' => 'Fri'],
    ];

    $times = ['10:00 PM', '12:00 PM', '05:00 AM', '10:00 AM', '11:30 AM'];

    $selectedDate = $request->get('date', 0);
    $selectedTime = $request->get('time', '11:30 AM');

    return view('appointment.booking', compact('dates', 'times', 'selectedDate', 'selectedTime')); 
}

public function listDokter()
{
    return view('appointment.list-dokter'); 
}

public function detailDokter()
{
    return view('appointment.detail-dokter');
}

public function uploadFile()
{
    return view('appointment.upload-file');

}

 public function showBookingForm(Request $request, Doctor $doctor)
    {
        // 1. Generate dates for the next 7 days
        $bookingDates = [];
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::today()->addDays($i);
            $bookingDates[] = [
                'day_name' => $date->translatedFormat('D'),
                'date_num' => $date->format('j'),
                'full_date' => $date->format('Y-m-d'),
                'day_of_week_name' => $date->format('l'),
            ];
        }

        // 2. Determine the selected date
        // Default to today's date if no date is selected in the URL
        $selectedDate = $request->input('date', Carbon::today()->format('Y-m-d'));

        // 3. Get the full name of the selected day (e.g., 'Wednesday')
        $selectedDayName = Carbon::parse($selectedDate)->format('l'); // 'l' gives full day name

        // 4. Fetch available slots for the selected doctor and day
        $doctorAvailability = DayAvailable::where('doctor_id', $doctor->id)
                                          ->where('day', $selectedDayName)
                                          ->orderBy('start_time')
                                          ->get();

        // 5. Generate time slots based on doctor's availability for the selected day
        $times = [];
        $intervalMinutes = 30; // Interval for time slots

        foreach ($doctorAvailability as $availability) {
            $start = Carbon::parse($availability->start_time);
            $end = Carbon::parse($availability->end_time);

            // If the selected date is today, adjust start time to not show past slots
            if ($selectedDate === Carbon::today()->format('Y-m-d')) {
                $currentTime = Carbon::now();
                // Add a small buffer (e.g., 5 minutes) to avoid showing immediately passed times
                $bufferTime = $currentTime->copy()->addMinutes(5);

                if ($start->lessThan($bufferTime)) {
                    // Adjust start to the next valid slot after bufferTime
                    // Round up to the nearest interval if current time is not aligned
                    $newStart = $bufferTime->copy();
                    $minute = $newStart->minute;
                    $remainder = $minute % $intervalMinutes;
                    if ($remainder > 0) {
                        $newStart->addMinutes($intervalMinutes - $remainder);
                    }
                    $newStart->second(0); // Clear seconds

                    if ($newStart->greaterThanOrEqualTo($end)) {
                        continue; // Skip this availability block if no future slots
                    }
                    $start = $newStart;
                }
            }

            while ($start->lessThan($end)) {
                $times[] = $start->format('H:i');
                $start->addMinutes($intervalMinutes);
            }
        }

        $times = array_unique($times); // Remove duplicates
        sort($times); // Sort times numerically

        // 6. Determine the selected time (for initial load or if user clicks a time)
        $selectedTime = $request->input('time');

        // --- PENTING: Perubahan untuk permintaan AJAX ---
        if ($request->ajax()) {
            return response()->json([
                'times' => $times,
                'selected_date' => $selectedDate // Send back the selected date for JS
            ]);
        }

        // Jika bukan permintaan AJAX, tampilkan view seperti biasa
        return view('user.booking.form', compact('doctor', 'bookingDates', 'selectedDate', 'times', 'selectedTime'));
    }


}