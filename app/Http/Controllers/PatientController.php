<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterPatientRequest;
use App\Models\Doctor;
use App\Models\Patient;
use App\Services\AuthService;
use App\Services\PatientService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PatientController extends Controller
{
    protected $patientService, $authService;

    public function __construct(PatientService $patientService, AuthService $authService)
    {
        $this->patientService = $patientService;
        $this->authService = $authService;
    }

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
    public function store(RegisterPatientRequest $request)
    {
        try {
            $valid = $request->validated();

            // Generate patient number
            $lastPatient = Patient::orderBy('patient_number', 'desc')->first();
            if ($lastPatient && $lastPatient->patient_number) {
                $lastNumber = intval(substr($lastPatient->patient_number, 1));
            } else {
                $lastNumber = 0;
            }
            $newNumber = $lastNumber + 1;
            $patientNumber = 'P' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

            $valid['patient_number'] = $patientNumber;

            $patient = Patient::create($valid);

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
    public function update(RegisterPatientRequest $request, Patient $patient)
    {
        $valid = $request->validated();

        $patient->update($valid);

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
        $patient = Patient::findOrFail($id);
        return view('user.registerPatient', compact('patient'));
    }


    public function registerPatient(RegisterPatientRequest $r)
    {
        $user = $this->authService->user('user');

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Please log in to register a user.'
            ], 401);
        }

        $valid = $r->validated();

        try {  
            $patient = $this->patientService->registerPatient($valid, $user);

            return response()->json([
                'message' => 'Pendaftaran pasien berhasil!',
                'patient' => $patient
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server saat mendaftar pasien: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // choosing patient for appointment
    public function selectExistingPatient(Request $request, Doctor $doctor)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to select a patient.');
        }


        $patients = $user->profiles->with('patient')->get()->map(function($profile) {
            return $profile->patient;
        })->filter()->unique('id');

        return view('appointment.select-existing-patient', compact('patients', 'doctor'));
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
                'doctor_name' => $schedule->dayAvailable->doctor->name ?? '-',
                'specialization' => $schedule->dayAvailable->doctor->specialization->name ?? '-',
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
