<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DayAvailable;
use App\Models\PracticeSchedule;
use App\Models\Patient;
use App\Models\Profile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // <-- Tambahkan ini

class BookingController extends Controller
{
   
   public function showBookingForm(Request $request, Doctor $doctor, Patient $patient)
    {
        $selectedDateStr = $request->input('date', Carbon::today()->toDateString());
        $selectedDate = Carbon::parse($selectedDateStr)->startOfDay();

        $bookingDates = [];
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::today()->addDays($i);
            $bookingDates[] = [
                'full_date' => $date->toDateString(),
                'day_name' => $date->translatedFormat('D'),
                'date_num' => $date->format('d'),
            ];
        }

        $availableTimeSlots = [];
        $dayName = $selectedDate->format('l');

        $dayAvailability = DayAvailable::where('doctor_id', $doctor->id)
            ->where('day', $dayName)
            ->first();

        if ($dayAvailability) {
            // Ambil SEMUA PracticeSchedule (booking) yang sudah ada untuk hari itu.
            $existingBookings = PracticeSchedule::where('day_available_id', $dayAvailability->id)
                ->whereDate('Datetime', $selectedDate)
                ->get()
                ->keyBy(function ($item) {
                    // Buat lookup map dengan key "HH:MM"
                    return $item->Datetime->format('H:i');
                });

            $startTime = Carbon::parse($dayAvailability->start_time);
            $endTime = Carbon::parse($dayAvailability->end_time);
            $interval = 30;

            while ($startTime < $endTime) {
                $slotDateTime = $selectedDate->copy()->setTimeFrom($startTime);
                $timeString = $slotDateTime->format('H:i');
                
                $isPastSlot = $slotDateTime->isPast();

                // [LOGIKA BENAR] Slot dianggap "booked" jika ada record PracticeSchedule untuk jam tersebut.
                $isAlreadyBooked = $existingBookings->has($timeString);

                $availableTimeSlots[] = [
                    'time' => $timeString,
                    'isBooked' => $isAlreadyBooked || $isPastSlot,
                ];

                $startTime->addMinutes($interval);
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'times' => $availableTimeSlots,
                'day_available_id' => $dayAvailability ? $dayAvailability->id : null,
            ]);
        }

        return view('user.booking.form', [
            'doctor' => $doctor,
            'patient' => $patient,
            'bookingDates' => $bookingDates,
            'selectedDate' => $selectedDateStr,
            'selectedTime' => $request->input('time'),
            'times' => $availableTimeSlots,
            'dayAvailable' => $dayAvailability,
        ]);
    }

    /**
     * Menyimpan booking baru.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'date' => 'required|date_format:Y-m-d',
                'time' => 'required|date_format:H:i',
                'patient_id' => 'required|exists:patients,id',
                'day_available_id' => 'required|exists:day_availables,id', 
            ]);

            $dateString = $request->input('date'); // e.g., "2025-06-20"
            $timeString = $request->input('time'); // e.g., "12:00"

            $dateTimeString = $dateString . ' ' . $timeString;
            $appointmentDateTime = Carbon::parse($dateTimeString);

            if ($appointmentDateTime->isPast()) {
                return response()->json(['success' => false, 'message' => 'Cannot book an appointment in the past.'], 400);
            }

            DB::beginTransaction();
            
            $existingSchedule = PracticeSchedule::where('day_available_id', $request->input('day_available_id'))
                ->where('Datetime', $appointmentDateTime)
                ->first();

            if ($existingSchedule) {
                DB::rollBack(); 
                return response()->json(['success' => false, 'message' => 'Slot waktu ini sudah dibooking.'], 400);
            }      

            // create practice schedule 
            $newSchedule = PracticeSchedule::create([
                'day_available_id' => $request->input('day_available_id'),
                'Datetime' => $appointmentDateTime,
            ]);    

            // Buat appointment baru
            Appointment::create([
                'schedule_id' => $newSchedule->id,
                'queue_number' => 1, // Atau logika queue_number yang sesuai
                'patient_id' => $request->input('patient_id'),
            ]);

            DB::commit(); 

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil disimpan.',
                'redirect_url' => route('user.appointments.index') 
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data yang dikirim tidak valid.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error("Error storing appointment: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server. Silakan coba lagi nanti.'
            ], 500);
        }
    }

    public function selectPatient(Request $request, Doctor $doctor)
    {
        $user = Auth::guard('user')->user();

        $patients = Profile::where('user_id', $user->id)->with('patient')
            ->get()
            ->map(function ($profile) {
                return $profile->patient;
            });

        return view('user.booking.select-patient', compact('doctor', 'patients'));
    }
}