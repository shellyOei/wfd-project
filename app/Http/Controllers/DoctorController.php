<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Specialization;
use App\Models\Doctor;

class DoctorController extends Controller
{
    /**
     * Display a page for users to choose a specialization.
     * This is the entry point for filtering.
     */
    public function showSpecializations()
    {
        $specializations = Specialization::orderBy('name')->get();
        return view('user.doctors.filter', ['specializations' => $specializations]);
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

        return view('user.doctors.list', compact('doctors'));
    }

    
    /**
     * Display a listing of doctors filtered by a specific specialization.
     * Corresponds to route('doctors.by_specialization', $specialization->id)
     */
    public function doctorsBySpecialization(Request $request, Specialization $specialization)
    {
        $query = $specialization->doctors();

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('front_title', 'like', '%' . $search . '%')
                  ->orWhere('back_title', 'like', '%' . $search . '%');
            });
        }

        $doctors = $query->get();

        return view('user.doctors.list', compact('doctors', 'specialization'));
    }


     /**
     * Handle AJAX request for doctor search suggestions.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDoctorSuggestions(Request $request)
    {
        $query = $request->input('query');

        if (empty($query)) {
            return response()->json([]); // Return empty array if query is empty
        }

        $doctors = Doctor::query()
            ->where(function($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('front_title', 'like', '%' . $query . '%')
                  ->orWhere('back_title', 'like', '%' . $query . '%');
            })
            // Optionally, also search by specialization name
            ->orWhereHas('specialization', function ($sQuery) use ($query) {
                $sQuery->where('name', 'like', '%' . $query . '%');
            })
            ->with('specialization') // Eager load specialization for display
            ->limit(10) // Limit the number of suggestions
            ->get(['id', 'name', 'front_title', 'back_title', 'specialization_id']); // Select only necessary columns

        return response()->json($doctors);
    }

    
    /**
     * Display the detail page for a single doctor.
     * Corresponds to route('doctors.show', $doctor->id)
     */
    public function show(Doctor $doctor)
    {
        $doctor->load('specialization');
        return view('user.doctors.detail', compact('doctor'));
    }

    /**
     * Handle AJAX requests for dynamic doctor search.
     * Returns a JSON array of doctors matching the search query and optional specialization.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchDoctorsAjax(Request $request)
    {
        // Ambil nilai pencarian dari input JavaScript.
        // Kita akan menggunakan 'query' sebagai nama parameter di JS.
        $search = $request->input('query');

        // Ambil ID spesialisasi jika ada dari hidden input di form.
        $specializationId = $request->input('specialization_id');

        // Mulai query untuk model Doctor.
        $doctors = Doctor::query();

        // Jika ada specialization_id, filter berdasarkan itu terlebih dahulu.
        if ($specializationId) {
            $doctors->where('specialization_id', $specializationId);
        }

        // Terapkan filter pencarian jika ada query.
        if ($search) {
            $doctors->where(function($q) use ($search) {
                // Cari di kolom 'name', 'front_title', 'back_title', dan 'description' dokter.
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('front_title', 'like', '%' . $search . '%')
                  ->orWhere('back_title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            })
            // Juga cari di nama spesialisasi dokter terkait.
            ->orWhereHas('specialization', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        }

        // Eager load relasi 'specialization' agar data spesialisasi ikut diambil.
        // Ini penting karena JavaScript akan menampilkan nama spesialisasi.
        $doctors = $doctors->with('specialization')->get();

        // Kembalikan hasil sebagai respons JSON.
        // Laravel akan secara otomatis mengonversi koleksi Eloquent menjadi array JSON.
        return response()->json($doctors);
    }
}