<?php

namespace App\Services;

use App\Models\Patient;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class PatientService
{
    /**
     * Creates a new patient and associates it with the user's profile.
     *
     * @param array $patientData Data for creating the patient.
     * @param User $user The authenticated user (current account).
     * @throws Throwable
     */
    public function registerPatient(array $patientData, User $user)
    {
        DB::beginTransaction();

        try {
            $patient = Patient::create($patientData);

            $profile = Profile::create([
                'patient_id' => $patient->id,
                'user_id' => $user->id,
            ]);

            DB::commit();

            return $patient;

        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Additional methods could go here:
     * - updatePatientProfile($patient, $data)
     * - getPatientProfileForUser(User $user)
     * - deletePatientAndProfile(Patient $patient)
     */
}

?>