<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterPatientRequest;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    /**
     * Display a listing of the patients.
     */
    public function index()
    {
        $patients = Patient::with(['profiles.user', 'appointments'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.patients', compact('patients'));
    }

    /**
     * Store a newly created patient in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|min:10|max:20',
                'sex' => 'required|in:male,female',
                'date_of_birth' => 'required|date|before:today',
                'address' => 'required|string',
                'occupation' => 'required|string|max:255',
                'blood_type' => 'nullable|string|max:5',
                'rhesus_factor' => 'nullable|string|max:5',
                'id_card_number' => 'required|string|max:20|unique:patients,id_card_number',
                'BPJS_number' => 'nullable|string|max:20|unique:patients,BPJS_number',
            ]);

            // Generate patient number
            $lastPatient = Patient::orderBy('patient_number', 'desc')->first();
            if ($lastPatient && $lastPatient->patient_number) {
                $lastNumber = intval(substr($lastPatient->patient_number, 1));
            } else {
                $lastNumber = 0;
            }
            $newNumber = $lastNumber + 1;
            $patientNumber = 'P' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

            $patient = Patient::create([
                'patient_number' => $patientNumber,
                'name' => $request->name,
                'phone' => $request->phone,
                'sex' => $request->sex,
                'date_of_birth' => $request->date_of_birth,
                'address' => $request->address,
                'occupation' => $request->occupation,
                'blood_type' => $request->blood_type,
                'rhesus_factor' => $request->rhesus_factor,
                'id_card_number' => $request->id_card_number,
                'BPJS_number' => $request->BPJS_number,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Patient added successfully!',
                'patient' => $patient
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while adding the patient: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified patient.
     */
    public function show(Patient $patient)
    {
        $patient->load(['profiles.user', 'appointments.schedule.doctor']);

        return response()->json([
            'success' => true,
            'patient' => $patient
        ]);
    }

    /**
     * Update the specified patient in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'sex' => 'required|in:male,female',
            'date_of_birth' => 'required|date|before:today',
            'address' => 'required|string',
            'occupation' => 'required|string|max:255',
            'blood_type' => 'nullable|string|max:5',
            'rhesus_factor' => 'nullable|string|max:5',
            'id_card_number' => 'required|string|max:20|unique:patients,id_card_number,' . $patient->id,
            'BPJS_number' => 'nullable|string|max:20|unique:patients,BPJS_number,' . $patient->id,
        ]);

        $patient->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'sex' => $request->sex,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'occupation' => $request->occupation,
            'blood_type' => $request->blood_type,
            'rhesus_factor' => $request->rhesus_factor,
            'id_card_number' => $request->id_card_number,
            'BPJS_number' => $request->BPJS_number,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Patient updated successfully!',
            'patient' => $patient
        ]);
    }

    /**
     * Remove the specified patient from storage.
     */
    public function destroy(Patient $patient)
    {
        try {
            // Check if patient has any appointments
            if ($patient->appointments()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete patient with existing appointments. Please remove appointments first.'
                ], 400);
            }

            // Delete related profiles first
            $patient->profiles()->delete();

            // Delete the patient
            $patient->delete();

            return response()->json([
                'success' => true,
                'message' => 'Patient deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the patient: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get patient's medical history
     */
    public function getMedicalHistory(Patient $patient)
    {
        $patient->load([
            'appointments.schedule.doctor',
            'appointments.prescriptions.medicine',
            'labResults'
        ]);

        return response()->json([
            'success' => true,
            'patient' => $patient,
            'appointments' => $patient->appointments,
            'lab_results' => $patient->labResults ?? []
        ]);
    }

    // --- register patient ---
    public function showPatientRegistrationForm()
    {
        return view('user.registerPatient');
    }
    public function showExistingPatientRegistrationForm()
    {
        return view('user.profile.linkPatient');
    }
    public function showEditForm($id)
    {
        $patient = Patient::findOrFail($id); // Kalau tidak ketemu, langsung 404
        return view('user.registerPatient', compact('patient'));
    }


    public function registerPatient(RegisterPatientRequest $r)
    {
        $valid = $r->validated();

        try {
            Patient::create($valid);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to register patient: ' . $e->getMessage()]);
        }
        // $patient = $this->patientRepository->create($valid);

        return redirect()->route('user.dashboard')->with('success', 'Patient registration successful!');
    }

    public function getAppointments($patientId)
    {
        $patient = Patient::with(['appointments.schedule.doctor.specialization'])->findOrFail($patientId);

        $activeAppointments = collect();
        $historyAppointments = collect();
        $now = \Carbon\Carbon::now()->timezone('Asia/Jakarta');

        foreach ($patient->appointments as $appointment) {
            $schedule = $appointment->schedule;
            if (!$schedule || !$schedule->Datetime)
                continue;

            $datetime = \Carbon\Carbon::parse($schedule->Datetime)->timezone('Asia/Jakarta');
            $info = [
                'date' => $datetime->translatedFormat('d F Y'),
                'time' => $datetime->format('H:i'),
                'title' => $appointment->type,
                'doctor_name' => $schedule->doctor->name ?? '-',
                'specialization' => $schedule->doctor->specialization->name ?? '-',
            ];
            if ($datetime->gt($now)) {
                $activeAppointments->push($info);
            } else {
                $historyAppointments->push($info);
            }
        }
        return response()->json([
            'activeAppointments' => $activeAppointments->sortBy('date')->values(),
            'historyAppointments' => $historyAppointments->sortByDesc('date')->values(),
        ]);
    }
}
