<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Specialization;
use App\Models\Doctor;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{
    // FILTER.BLADE.PHP
    // show specializations (filter page)
    public function showSpecializations()
    {
        $specializations = Specialization::orderBy('name')->get();
        return view('user.doctors.filter', ['specializations' => $specializations]);
    }

    // listing of doctors filtered by a specific specialization.
    public function doctorsBySpecialization(Request $request, Specialization $specialization)
    {
        $query = $specialization->doctors();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('front_title', 'like', '%' . $search . '%')
                    ->orWhere('back_title', 'like', '%' . $search . '%');
            });
        }

        $doctors = $query->get();

        return view('user.doctors.list', compact('doctors', 'specialization'));
    }


    // ajax request for doctor search suggestions (filter.blade.php)
    public function getDoctorSuggestions(Request $request)
    {
        $query = $request->input('query');

        if (empty($query)) {
            return response()->json([]); 
        }

        $doctors = Doctor::query()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                    ->orWhere('front_title', 'like', '%' . $query . '%')
                    ->orWhere('back_title', 'like', '%' . $query . '%');
            })
            ->orWhereHas('specialization', function ($sQuery) use ($query) {
                $sQuery->where('name', 'like', '%' . $query . '%');
            })
            ->with('specialization') 
            ->limit(10) 
            ->get(['id', 'name', 'front_title', 'back_title', 'specialization_id']);

        return response()->json($doctors);
    }


    // LIST.BLADE.PHP
     // list all doctors
    public function listDoctor(Request $request)
    {
        $query = Doctor::query()->with('specialization');

        // Apply search filter if present
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('front_title', 'like', '%' . $search . '%')
                    ->orWhere('back_title', 'like', '%' . $search . '%');
            });
        }

        $doctors = $query->latest()->get();

        return view('user.doctors.list', compact('doctors'));
    }

      //  AJAX requests for dynamic doctor search (list.blade.php)
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
            $doctors->where(function ($q) use ($search) {
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

     
        $doctors = $doctors->with('specialization')->get();


        return response()->json($doctors);
    }

    

    // DETAIL.BLADE.PHP

    // show the detail page for a single doctor
    public function show(Doctor $doctor)
    {
        $doctor->load('specialization');
        return view('user.doctors.detail', compact('doctor'));
    }



  


    // ADMIN CONTROLLER METHODS
     public function index(Request $request)
    {
        try {
            // Start a query on the Doctor model and eager load the 'specialization' relationship.
            // Select 'doctors.*' to avoid potential column ambiguity if joining other tables later.
            $query = Doctor::query()->select('doctors.*')->with('specialization');

            // Handle search functionality
            if ($request->has('search') && $request->input('search') != '') {
                $search = $request->input('search');
                // Apply search filter: search in name, address, or related specialization's name.
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('address', 'like', '%' . $search . '%')
                      ->orWhereHas('specialization', function ($sq) use ($search) {
                          $sq->where('name', 'like', '%' . $search . '%');
                      });
                });
            }

            // Handle filter by specialization_id from the request query parameter
            if ($request->has('specialization_id') && $request->input('specialization_id') != '') {
                $specializationId = $request->input('specialization_id');
                $query->where('specialization_id', $specializationId);
            }

            // Fetch doctors, ordered by the latest creation date, with pagination.
            $doctors = $query->latest()->paginate(10); // Adjust pagination limit as needed

            // Fetch all specializations for the filter dropdown
            $specializations = Specialization::orderBy('name')->get();

            // If it's an AJAX request, return JSON data
            if ($request->ajax()) {
                return response()->json([
                    'doctors' => $doctors->items(), // Get the current page's doctor data
                    'pagination' => (string) $doctors->links()->toHtml(), // Render pagination links as string
                    'current_page' => $doctors->currentPage(),
                    'last_page' => $doctors->lastPage(),
                    'total' => $doctors->total(),
                    'per_page' => $doctors->perPage(),
                ]);
            }

        } catch (\Exception $e) {
            Log::error("Error fetching doctors: " . $e->getMessage(), ['exception' => $e]);

            // For AJAX requests, return a JSON error
            if ($request->ajax()) {
                return response()->json(['error' => 'An error occurred while fetching doctors.'], 500);
            }

            // For regular requests, return an empty collection and flash an error message
            $doctors = collect();
            $specializations = collect();
            session()->flash('error', 'An error occurred while fetching doctors. Please try again.');
        }

        // Return the full view for initial page load
        return view('admin.doctors.index', compact('doctors', 'specializations'));
    }

     public function create()
    {
        $specializations = Specialization::orderBy('name')->get();
        return view('admin.doctors.create', compact('specializations'));
    }


      public function store(Request $request)
    {
        // Validating fields based on your Doctor model (without 'email')
        $request->validate([
            'front_title' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'back_title' => 'nullable|string|max:50',
            'specialization_id' => 'required|exists:specializations,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'doctor_number' => 'nullable|string|max:255|unique:doctors,doctor_number', // Added doctor_number validation
            'photo' => 'nullable|string', // Assuming photo is stored as a URL/path string
            'description' => 'nullable|string', // Added description validation
        ]);

        Doctor::create($request->all());

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor added successfully!');
    }

    
    public function edit(Doctor $doctor)
    {
        $specializations = Specialization::orderBy('name')->get();
        return view('admin.doctors.edit', compact('doctor', 'specializations'));
    }

  
    public function update(Request $request, Doctor $doctor)
    {
        // Validating fields based on your Doctor model (without 'email')
        $request->validate([
            'front_title' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'back_title' => 'nullable|string|max:50',
            'specialization_id' => 'required|exists:specializations,id',
            // 'email' is removed from validation
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'doctor_number' => [ // Added doctor_number validation with unique ignore
                'nullable',
                'string',
                'max:255',
                Rule::unique('doctors')->ignore($doctor->id),
            ],
            'photo' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $doctor->update($request->all());

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor updated successfully!');
    }

        public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return redirect()->route('admin.doctors.index')->with('success', 'Doctor deleted successfully!');
    }



}


