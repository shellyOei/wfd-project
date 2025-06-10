<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Specialization;
use App\Models\Doctor;

class DokterController extends Controller
{
     /**
     * Display the page with a list of all specializations to filter by.
     */
    public function showSpecializations()
    {
        // Fetch all specializations from the database
        $specializations = Specialization::orderBy('name')->get();
        // dd($specializations);

        // Return the view and pass the specializations data to it
        return view('doctors.filter', ['specializations' => $specializations]);
        
    }

    /**
     * Display a list of doctors for a given specialization.
     *
     * @param  \App\Models\Specialization  $specialization
     * @return \Illuminate\Http\Response
     */
    public function showDoctorsBySpecialization(Specialization $specialization)
    {
        // Thanks to Route Model Binding, Laravel automatically finds the specialization.
        // Now, we load the doctors related to this specialization using the relationship we defined.
        $doctors = $specialization->doctors()->get();

        // Return the view and pass both the selected specialization and the list of doctors
        return view('doctors.index', [
            'specialization' => $specialization,
            'doctors' => $doctors
        ]);
    }
}
