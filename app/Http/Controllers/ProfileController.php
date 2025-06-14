<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    public function index()
    {
        $user = auth()->guard('user')->user();
        $user->load(['profiles.patient']);
        $user->profiles = $user->profiles->sortBy('created_at');
        return view('user.profile.index', ['user' => $user]);
    }

    public function linkPatient(Request $r)
    {
        $validated = $r->validate([
            'name' => 'required|string',
            'date_of_birth' => 'required|date',
            'patient_number' => 'required|string'
        ]);

        $patient = Patient::where('name', $validated['name'])
            ->where('date_of_birth', $validated['date_of_birth'])
            ->where('patient_number', $validated['patient_number'])
            ->first();

        if (!$patient) {
            return response()->json(['message' => 'Pasien tidak ditemukan.'], 404);
        }

        $user = auth()->guard('user')->user();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan.'], 403);
        }

        Profile::create([
            'user_id' => $user->id,
            'patient_id' => $patient->id,
        ]);

        return response()->json(['message' => 'Pasien berhasil dikaitkan.']);
    }

    public function miniHistory()
    {
        $user = auth()->guard('user')->user();

        $patients = $user->patients()->with(['appointments.schedule.doctor.specialization'])->get();

        $activeAppointments = collect();
        $historyAppointments = collect();
        $patientNames = collect();

        // Ambil pasien pertama sebagai default load
        $firstPatient = $patients->first();

        foreach ($patients as $patient) {
            $patientNames->push([
                'patient_id' => $patient->id,
                'patient_name' => $patient->name,
            ]);
        }

        // Jika ada pasien, load data janji temu dari pasien pertama
        if ($firstPatient) {
            foreach ($firstPatient->appointments as $appointment) {
                $schedule = $appointment->schedule;
                if (!$schedule || !$schedule->Datetime)
                    continue;

                $datetime = \Carbon\Carbon::parse($schedule->Datetime);
                $doctor = $schedule->doctor;

                $info = [
                    'date' => $datetime->translatedFormat('d F Y'),
                    'time' => $datetime->format('H:i'),
                    'title' => $appointment->type,
                    'doctor_name' => $doctor->name ?? '-',
                    'specialization' => $doctor->specialization->name ?? '-',
                ];

                if ($datetime->isToday() || $datetime->isFuture()) {
                    $activeAppointments->push($info);
                } else {
                    $historyAppointments->push($info);
                }
            }
        }

        return view('user.miniHistory', [
            'activeAppointments' => $activeAppointments->sortBy('date')->values(),
            'historyAppointments' => $historyAppointments->sortByDesc('date')->values(),
            'patients' => $patientNames,
        ]);
    }
    public function disconnect($id)
    {
        $user = auth()->guard('user')->user();
        $patient = Patient::findOrFail($id);
        $user->profiles()->where('patient_id', $patient->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pasien telah diputus.'
        ]);
    }
    public function showEditPatient()
    {
        $user = auth()->guard('user')->user();
        $patients = $user->patients()->get();

        return view('user.profile.patientList', compact('patients'));
    }



}


