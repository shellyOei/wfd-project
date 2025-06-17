<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterPatientRequest;
use App\Models\Patient;
use App\Services\AuthService;
use App\Services\PatientService;
use App\Exports\PatientsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

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

    /**
     * Export patients to Excel/CSV
     */
    public function export()
    {
        try {
            // Try Excel export first
            if (class_exists('Maatwebsite\Excel\Facades\Excel')) {
                $fileName = 'patients_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
                return Excel::download(new PatientsExport, $fileName);
            } else {
                // Fallback to CSV export
                return $this->exportCSV();
            }
        } catch (\Exception) {
            // Fallback to CSV if Excel fails
            return $this->exportCSV();
        }
    }

    /**
     * Export patients to CSV (fallback method)
     */
    private function exportCSV()
    {
        $patients = Patient::with(['profiles.user', 'appointments'])
                          ->orderBy('created_at', 'desc')
                          ->get();

        $fileName = 'patients_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($patients) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'Patient Number',
                'Full Name',
                'Phone Number',
                'Gender',
                'Date of Birth',
                'Age',
                'Blood Type',
                'Rhesus Factor',
                'Occupation',
                'Address',
                'ID Card Number',
                'BPJS Number',
                'Linked Users',
                'Total Appointments',
                'Status',
                'Registration Date'
            ]);

            // Add patient data
            foreach ($patients as $patient) {
                $age = \Carbon\Carbon::parse($patient->date_of_birth)->age;
                $linkedUsers = $patient->profiles->pluck('user.email')->filter()->implode(', ');
                if (empty($linkedUsers)) {
                    $linkedUsers = 'No linked users';
                }
                $totalAppointments = $patient->appointments->count();
                $hasRecentAppointment = $patient->appointments->where('created_at', '>=', now()->subMonths(6))->count() > 0;
                $status = $hasRecentAppointment ? 'Active' : 'Inactive';

                fputcsv($file, [
                    $patient->patient_number,
                    $patient->name,
                    $patient->phone,
                    ucfirst($patient->sex),
                    \Carbon\Carbon::parse($patient->date_of_birth)->format('Y-m-d'),
                    $age . ' years',
                    $patient->blood_type ?? 'N/A',
                    $patient->rhesus_factor ?? 'N/A',
                    $patient->occupation,
                    $patient->address,
                    $patient->id_card_number,
                    $patient->BPJS_number ?? 'Not registered',
                    $linkedUsers,
                    $totalAppointments,
                    $status,
                    $patient->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
