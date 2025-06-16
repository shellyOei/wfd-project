<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DayAvailable;
use App\Models\PracticeSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // <-- Tambahkan ini

class BookingController extends Controller
{
    /**
     * Menampilkan form booking dan menangani permintaan AJAX untuk jadwal.
     */
    public function showBookingForm(Request $request, Doctor $doctor)
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

        // === PERUBAHAN LOGIKA UTAMA DIMULAI DI SINI ===
        $availableTimeSlots = [];

        // dayOfWeek (0=Minggu, 1=Senin, ..., 6=Sabtu)
        $dayOfWeek = $selectedDate->dayOfWeek;

        // Ambil pola ketersediaan dokter untuk hari yang dipilih
        $dayAvailability = DayAvailable::where('doctor_id', $doctor->id)
            ->where('day', $dayOfWeek) // Pastikan 'day' di database Anda sesuai format Carbon (0-6)
            ->first();

        if ($dayAvailability) {
            $startTime = Carbon::parse($dayAvailability->start_time);
            $endTime = Carbon::parse($dayAvailability->end_time);
            $interval = 30; // durasi per slot dalam menit

            while ($startTime < $endTime) {
                // Gabungkan tanggal yang dipilih dengan waktu slot
                $slotDateTime = $selectedDate->copy()->setTimeFrom($startTime);

                // Lewati slot jika sudah terlewat dari waktu sekarang
                if ($slotDateTime->isPast()) {
                    $startTime->addMinutes($interval);
                    continue;
                }

                // Cari atau buat PracticeSchedule untuk slot ini (efisien!)
                // Ini adalah jantung dari perbaikan logika.
                $practiceSchedule = PracticeSchedule::firstOrCreate(
                    [
                        'doctor_id' => $doctor->id,
                        'Datetime' => $slotDateTime,
                    ],
                    [
                        // Data ini hanya akan diisi jika record baru dibuat
                        'day_available_id' => $dayAvailability->id,
                    ]
                );

                // Cek apakah jadwal ini sudah dibooking (punya appointment)
                // Kita gunakan ->doesntHave() untuk efisiensi query
                $isAvailable = !$practiceSchedule->appointment()->exists();

                if ($isAvailable) {
                    $availableTimeSlots[] = [
                        'time' => $slotDateTime->format('H:i'),
                        'schedule_id' => $practiceSchedule->id, // Kirim ID yang dibutuhkan frontend!
                    ];
                }

                $startTime->addMinutes($interval);
            }
        }

        // Jika ini adalah request AJAX, kembalikan JSON
        if ($request->ajax()) {
            return response()->json([
                'times' => $availableTimeSlots, // Nama 'times' sesuai yang diharapkan JS Anda
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
        ]);
    }

    /**
     * Menyimpan booking baru.
     */
    public function store(Request $request)
    {
       
        try {
            $validated = $request->validate([
                'schedule_id' => 'required|uuid|exists:practice_schedules,id',
            ]);

            $scheduleId = $validated['schedule_id'];
            $patientId = Auth::id();

            // Mulai transaksi database untuk mencegah race condition (rebutan slot)
            return DB::transaction(function () use ($scheduleId, $patientId) {
                // Kunci record PracticeSchedule untuk pembaruan (mencegah user lain booking di saat bersamaan)
                $practiceSchedule = PracticeSchedule::where('id', $scheduleId)->lockForUpdate()->first();

                // Cek sekali lagi jika slot sudah diambil orang lain
                if ($practiceSchedule->appointment) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Maaf, slot waktu ini baru saja dibooking. Silakan pilih waktu lain.'
                    ], 409); // 409 Conflict
                }

                // Jika masih tersedia, buat appointment
                $appointment = Appointment::create([
                    'patient_id' => $patientId,
                    'schedule_id' => $practiceSchedule->id,
                    // Isi default lainnya
                    'type' => 'Online Booking',
                    'is_bpjs' => false, // Default value
                ]);

                // Berhasil!
                return response()->json([
                    'success' => true,
                    'message' => 'Janji temu berhasil dikonfirmasi!',
                    'redirect_url' => route('user.my_appointments') // Ganti dengan rute halaman "Janji Temu Saya"
                ], 201);
            });

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
}