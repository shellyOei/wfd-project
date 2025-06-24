<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\PracticeSchedule;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    // fetch incoming appointments 
    // if appointment already passed 30 minutes from their scheduled time, it will not be shown
    public function index(Request $request)
    {
        $user = auth()->guard('user')->user();

        // fetch the patients linked to the user (account)
        $linkedPatientIds = $user->profiles->pluck('patient_id')->toArray();

        if (empty($linkedPatientIds)) {
            return view('user.appointments.index', [
                'linkedPatients' => collect(),
                'groupedAppointments' => collect(),
                'selectedPatientId' => null,
            ]);
        }

        $linkedPatients = Patient::whereIn('id', $linkedPatientIds)->get();

        $selectedPatientId = $request->input('patient_id');

        if (empty($selectedPatientId) || !in_array($selectedPatientId, $linkedPatientIds)) {
            $selectedPatientId = $linkedPatientIds[0] ?? null;
        }

        $appointments = [];
        if ($selectedPatientId) {
            $appointments = Appointment::with(['patient', 'schedule', 'schedule.dayAvailable.doctor']) 
                                   ->join('practice_schedules', 'appointments.schedule_id', '=', 'practice_schedules.id')
                                   ->where('appointments.patient_id', $selectedPatientId) 
                                   ->where('appointments.status', 1) 
                                   ->orderBy('practice_schedules.Datetime', 'asc') 
                                   ->select('appointments.*')
                                   ->get();
        }

        $groupedAppointments = $appointments->groupBy(function($appointment) {
            return Carbon::parse($appointment->schedule->Datetime)->format('Y-m-d');
        });

        return view('user.appointment', [
            'linkedPatients' => $linkedPatients,
            'groupedAppointments' => $groupedAppointments,
            'selectedPatientId' => $selectedPatientId,
        ]);
    }

    public function show()
    {
        $admin = auth()->guard('admin')->user();
        $appointments = collect();

        $superAdmin = $admin->email == "superadmin@gmail.com";

        if ($admin->isDoctor()) {
            $appointments = Appointment::with(['patient', 'schedule', 'schedule.dayAvailable.doctor'])
                ->whereHas('schedule.dayAvailable.doctor', function ($query) use ($admin) {
                    $query->where('id', $admin->id);
                })
                ->where('status', 1)
                ->orderBy('created_at', 'desc')
                ->get();

            // Counts specific to this doctor's appointments
            $statusCounts = [
                'ongoing' => Appointment::whereHas('schedule.dayAvailable.doctor', function ($query) use ($admin) {
                        $query->where('id', $admin->id);
                    })->where('status', 1)->count(),
                'cancelled' => Appointment::whereHas('schedule.dayAvailable.doctor', function ($query) use ($admin) {
                        $query->where('id', $admin->id);
                    })->where('status', 2)->count(),
                'completed' => Appointment::whereHas('schedule.dayAvailable.doctor', function ($query) use ($admin) {
                        $query->where('id', $admin->id);
                    })->where('status', 3)->count(),
            ];
        } else if ($superAdmin) {
            $appointments = Appointment::with(['patient', 'schedule', 'schedule.dayAvailable.doctor'])->get();

            // Counts for all appointments
            $statusCounts = [
                'ongoing' => Appointment::where('status', 1)->count(),
                'cancelled' => Appointment::where('status', 2)->count(),
                'completed' => Appointment::where('status', 3)->count(),
            ];
        } else {
            // Optional: if other admins exist
            $statusCounts = [
                'ongoing' => 0,
                'cancelled' => 0,
                'completed' => 0,
            ];
        }

        $doctors = Doctor::all();
        $patients = Patient::all();

        return view('admin.appointments', compact('appointments', 'statusCounts', 'doctors', 'patients'));
    }

    public function getSchedulesForDoctor(Doctor $doctor)
    {
        // Get all days the doctor is available (e.g., ['Monday', 'Wednesday'])
        $availableDays = $doctor->dayAvailables()->get()->keyBy('day');

        if ($availableDays->isEmpty()) {
            return response()->json([]);
        }

        // Get all existing schedules for this doctor to check for conflicts
        $existingSchedules = PracticeSchedule::whereHas('dayAvailable', function ($query) use ($doctor) {
            $query->where('doctor_id', $doctor->id);
        })->where('Datetime', '>=', now())
          ->pluck('Datetime')
          ->map(fn($datetime) => $datetime->format('Y-m-d H:i:s'))
          ->flip(); // Flip for fast O(1) lookups using isset()

        $availableSlots = [];
        // Generate slots for the next 14 days
        $period = CarbonPeriod::create(Carbon::today(), Carbon::today()->addDays(5));

        foreach ($period as $date) {
            $dayName = $date->format('l'); // e.g., 'Monday'

            if ($availableDays->has($dayName)) {
                $dayInfo = $availableDays[$dayName];
                $startTime = Carbon::parse($date->format('Y-m-d') . ' ' . $dayInfo->start_time);
                $endTime = Carbon::parse($date->format('Y-m-d') . ' ' . $dayInfo->end_time);

                while ($startTime < $endTime) {
                    $slot = $startTime->format('Y-m-d H:i:s');
                    if (!isset($existingSchedules[$slot])) {
                        $availableSlots[] = [
                            'value' => $slot,
                            'text' => Carbon::parse($slot)->format('l, j M Y - h:i A')
                        ];
                    }
                    $startTime->addMinutes(30); 
                }
            }
        }

        return response()->json($availableSlots);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'date' => 'required|date_format:Y-m-d',
                'time' => 'required|date_format:H:i',
                'patient_id' => 'required|exists:patients,id',
                'day_available_id' => 'required|exists:day_availables,id', 
            ]);

            $dateString = $request->input('date'); 
            $timeString = $request->input('time'); 

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

    public function storeAdmin(Request $request)
    {
        try {
            $request->validate([
                'date' => 'required|date_format:Y-m-d',
                'time' => 'required|date_format:H:i',
                'patient_id' => 'required|exists:patients,id',
                'day_available_id' => 'required|exists:day_availables,id', 
                'bpjs' => 'boolean',
                'type' => 'required|string|max:255',
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
                'is_bpjs' => $request->input('bpjs', false),
                'type' => $request->input('type'),
            ]);

            DB::commit(); 

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil disimpan.',
                'redirect_url' => route('admin.appointments.index') 
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

    public function update(Request $request, Appointment $appointment)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|integer|in:1,2,3',
            'type' => 'required|string|max:255',
            'is_bpjs' => 'nullable|boolean',
            'notes' => 'nullable|string',
            'subjective' => 'nullable|string',
            'objective' => 'nullable|string',
            'assessment' => 'nullable|string',
            'plan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $appointment->update([
                'status'     => $request->input('status'),
                'type'       => $request->input('type'),
                'is_bpjs'    => $request->input('is_bpjs', false),
                'subjective' => $request->input('subjective'),
                'objective'  => $request->input('objective'),
                'assessment' => $request->input('assessment'),
                'plan'       => $request->input('plan'),
            ]);


            DB::commit();
            return redirect()->back()->with('success', 'Appointment updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update the appointment.');
        }
    }

    public function destroy(Appointment $appointment)
    {
        if ($appointment->status == 2 || $appointment->status == 3) {
            return back()->with('error', 'This appointment cannot be cancelled.');
        }

        try {
            $appointment->status = 2; 
            $appointment->save();

            return response()->json([
                'success' => true,
                'message' => 'Status janji temu berhasil diperbarui menjadi Dibatalkan.'
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat memperbarui status janji temu.'], 500);
        }
    }

    public function saveNotes(Request $request, Appointment $appointment)
    {
        $request->validate([
            'notes' => 'required|string|max:500',
        ]);
        try {
            $appointment->notes = $request->input('notes');
            $appointment->save();

            return response()->json([
                'success' => true,
                'message' => 'Catatan berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to save notes for appointment ID {$appointment->id}: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan catatan.'], 500);
        }
    }

    public function getDoctorAvailability(Doctor $doctor)
    {
        $dayOfWeekMap = ['Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3, 'Thursday' => 4, 'Friday' => 5, 'Saturday' => 6, 'Sunday' => 0];
        
        $availableDays = $doctor->dayAvailables()
            ->pluck('day')
            ->map(function ($dayName) use ($dayOfWeekMap) {
                return $dayOfWeekMap[$dayName] ?? null;
            })
            ->filter()
            ->unique()
            ->values();

        return response()->json($availableDays);
    }

    public function getAvailableTimes(Request $request, Doctor $doctor)
    {
        $request->validate(['date' => 'required|date_format:Y-m-d']);
        $date = Carbon::parse($request->input('date'));
        $dayName = $date->format('l');

        $dayAvailable = $doctor->dayAvailables()->where('day', $dayName)->first();

        if (!$dayAvailable) {
            return response()->json(['available_slots' => [], 'day_available_id' => null]);
        }

        $startTime = Carbon::parse($date->format('Y-m-d') . ' ' . $dayAvailable->start_time);
        $endTime = Carbon::parse($date->format('Y-m-d') . ' ' . $dayAvailable->end_time);
        
        $bookedSlots = PracticeSchedule::where('day_available_id', $dayAvailable->id)
            ->whereDate('Datetime', $date->toDateString())
            ->get()
            ->map(fn($schedule) => Carbon::parse($schedule->Datetime)->format('H:i'))
            ->flip();

        $availableSlots = [];
        while ($startTime < $endTime) {
            $slot = $startTime->format('H:i');
            if (!isset($bookedSlots[$slot])) {
                $availableSlots[] = $slot;
            }
            $startTime->addMinutes(30);
        }

        return response()->json([
            'available_slots' => $availableSlots,
            'day_available_id' => $dayAvailable->id,
        ]);
    }
}
