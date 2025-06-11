<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Specialization;
use App\Models\Doctor;

class DokterController extends Controller
{
    /**
     * Display a page for users to choose a specialization.
     * This is the entry point for filtering.
     */
    public function showSpecializations()
    {
        $specializations = Specialization::orderBy('name')->get();
        return view('doctors.filter', ['specializations' => $specializations]);
    }

    /**
     * Display a listing of ALL doctors.
     * Corresponds to route('doctors.index')
     */
    public function index(Request $request)
    {
        $query = Doctor::query()->with('specialization');

        // Apply search filter if present
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('front_title', 'like', '%' . $search . '%')
                  ->orWhere('back_title', 'like', '%' . $search . '%');
            });
        }

        $doctors = $query->latest()->get();

        // Pass the '$doctors' collection. The view's @isset($specialization) will handle
        // the $specialization variable not being set.
        return view('doctors.list', compact('doctors'));
    }

    /**
     * Display a listing of doctors filtered by a specific specialization.
     * Corresponds to route('doctors.by_specialization', $specialization->id)
     */
    public function doctorsBySpecialization(Request $request, Specialization $specialization)
    {
        // Start query from the specialization's doctors relationship
        $query = $specialization->doctors();

        // Apply search filter if present
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('front_title', 'like', '%' . $search . '%')
                  ->orWhere('back_title', 'like', '%' . $search . '%');
            });
        }

        // We don't need with('specialization') here since all doctors
        // belong to the $specialization object we already have, but it doesn't hurt.
        $doctors = $query->get();

        // Pass both the filtered doctors and the specialization to the view
        return view('doctors.list', compact('doctors', 'specialization'));
    }

    /**
     * Display the detail page for a single doctor.
     * Corresponds to route('doctors.show', $doctor->id)
     */
    public function show(Doctor $doctor)
    {
        // Eager load the relationship for efficiency
        $doctor->load('specialization');
        // dd($doctor);

        // IMPORTANT: Return a DETAIL view, not the list view.
        return view('doctors.detail', compact('doctor'));
    }
}