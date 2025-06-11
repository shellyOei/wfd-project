<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterPatientRequest;
use App\Models\Patient;
use App\Repositories\Contracts\PatientRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    // --- register patient ---
    public function showPatientRegistrationForm()
    {
        return view('user.registerPatient');
    }

    public function registerPatient(RegisterPatientRequest $r)
    {
        $valid = $r->validated();
        
        Patient::create($valid);
        // $patient = $this->patientRepository->create($valid);

        return redirect()->route('user.dashboard')->with('success', 'Patient registration successful!');
    }
}
