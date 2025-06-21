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
        // Ambil tanggal yang dipilih dari request, atau default ke hari ini
        $selectedDateStr = $request->input('date', Carbon::today()->toDateString());
        $selectedDate = Carbon::parse($selectedDateStr)->startOfDay();

        // Generate tanggal untuk 7 hari ke depan untuk date picker
        $bookingDates = [];
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::today()->addDays($i);
            $bookingDates[] = [
                'full_date' => $date->toDateString(),
                'day_name' => $date->translatedFormat('D'), // Misal: Sen, Sel
                'date_num' => $date->format('d'),
            ];
        }
        
        $availableTimeSlots = [];

        $dayName = strtolower($selectedDate->format('l')); //convert to day names

        $dayAvailability = DayAvailable::where('doctor_id', $doctor->id)
            ->where('day', $dayName) 
            ->first();

        if ($dayAvailability) {
            $startTime = Carbon::parse($dayAvailability->start_time);
            $endTime = Carbon::parse($dayAvailability->end_time);
            $interval = 30; 

            while ($startTime < $endTime) {
                $slotDateTime = $selectedDate->copy()->setTimeFrom($startTime);
                if ($slotDateTime->isPast()) {
                    $startTime->addMinutes($interval);
                    continue;
                }

                $existingPracticeSchedule = PracticeSchedule::where('day_available_id',  $dayAvailability->id)
                                                ->where('Datetime', $slotDateTime)
                                                ->first();

                // check if the slot is already booked
                $isBooked = $existingPracticeSchedule && $existingPracticeSchedule->appointment;

                $availableTimeSlots[] = [
                    'time' => $slotDateTime->format('H:i'),
                    'isBooked' => $isBooked,
                ];

                $startTime->addMinutes($interval);
            }
        }

      
        if ($request->ajax()) {
            return response()->json([
                'times' => $availableTimeSlots, 
            ]);
        }

        // Jika ini adalah load halaman awal, tampilkan view
        // Ganti nama variabel 'availableSlots' menjadi 'times' agar konsisten dengan Blade Anda
        return view('user.booking.form', [
            'doctor' => $doctor,
            'bookingDates' => $bookingDates,
            'selectedDate' => $selectedDateStr,
            'selectedTime' => $request->input('time'), // Ambil dari request jika ada
            'times' => $availableTimeSlots, // Kirim slot yang tersedia
            'patient' => $patient, // Tambahkan pasien yang dipilih
            'dayAvailable' => $dayAvailability, // Tambahkan pola ketersediaan hari
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