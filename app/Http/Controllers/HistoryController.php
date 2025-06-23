<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * Menampilkan halaman daftar riwayat konsultasi.
     */
    // public function index(Request $request)
    // {
    //     // Dapatkan user yang sedang login
    //     $user = Auth::user();

    //     // Ambil janji temu (appointments) milik pasien yang terhubung dengan user ini
    //     // Eager load relasi agar query lebih efisien
    //     $query = Appointment::with(['schedule.doctor'])
    //         ->whereHas('patient.profiles', function ($q) use ($user) {
    //             $q->where('user_id', $user->id);
    //         });

    //     // Terapkan filter status (Selesai/Dibatalkan)
    //     if ($request->has('status') && in_array($request->status, ['completed', 'canceled'])) {
    //         $query->where('status', $request->status);
    //     }

    //     // Terapkan filter pencarian nama dokter
    //     if ($request->has('search')) {
    //         $searchTerm = $request->search;
    //         $query->whereHas('schedule.doctor', function ($q) use ($searchTerm) {
    //             $q->where('name', 'like', '%' . $searchTerm . '%');
    //         });
    //     }
        
    //     // Urutkan berdasarkan yang terbaru
    //     $appointments = $query->latest('updated_at')->get();

    //     return view('history.index', compact('appointments'));
    // }
        // di dalam app/Http/Controllers/HistoryController.php

    // Function ini hanya untuk debugging, yang diatas harusnya butuh auth login dulu - Anto
    public function index(Request $request)
    {
        // Dapatkan user yang sedang login
        $user = Auth::guard('user')->user();

        // Mengambil daftar pasien yang terhubung dengan user untuk dropdown filter
        $userPatients = Patient::whereHas('profiles', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->get();

        // Query dasar untuk mengambil appointment milik user
        $query = Appointment::with(['schedule.doctor', 'schedule.doctor.specialization', 'patient'])
            ->whereHas('patient.profiles', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });

        // Terapkan filter status (Selesai/Dibatalkan/Semua)
        if ($request->filled('status') && in_array($request->status, [3, 2])) {
            $query->where('status', $request->status);
        } else {
            // Jika "Semua", tampilkan yang sudah selesai atau dibatalkan
            $query->whereIn('status', [3, 2]);
        }
        
        // Terapkan filter pasien jika dipilih dari dropdown
        if ($request->filled('patient_id')) {
            // Validasi untuk memastikan user hanya bisa memfilter pasien miliknya
            if ($userPatients->contains('id', $request->patient_id)) {
                $query->where('patient_id', $request->patient_id);
            }
        }
        
        // Urutkan berdasarkan tanggal janji temu terbaru
        $appointments = $query->join('practice_schedules', 'appointments.schedule_id', '=', 'practice_schedules.id')
                               ->orderBy('practice_schedules.Datetime', 'desc')
                               ->select('appointments.*')
                               ->get();
        
        // Kirim data appointments dan daftar pasien ke view
        return view('history.index', compact('appointments', 'userPatients'));
    }

    public function show(Appointment $appointment)
{
    $appointment->load([
        'schedule.dayAvailable.doctor', 
        'schedule.dayAvailable.doctor.specialization',
        'patient',
    ]);
    return view('history.show', compact('appointment'));
}
}