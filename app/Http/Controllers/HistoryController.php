<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
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
        $appointments = collect();

        if (Auth::check()) {
            $user = Auth::user();

            $query = Appointment::with(['schedule.doctor'])
                ->whereHas('patient.profiles', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });

            // Filter status
            if ($request->filled('status') && in_array($request->status, ['completed', 'canceled'])) {
                $query->where('status', $request->status);
            }

            // Filter pencarian nama dokter
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->whereHas('schedule.doctor', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%');
                });
            }

            // **LOGIKA BARU UNTUK FILTER WAKTU**
            if ($request->filled('time_filter')) {
                $filter = $request->time_filter;
                $now = Carbon::now();

                $query->whereHas('schedule', function($q) use ($filter, $now) {
                    if ($filter == 'today') {
                        $q->whereDate('Datetime', $now->toDateString());
                    } elseif ($filter == 'month') {
                        $q->whereMonth('Datetime', $now->month)->whereYear('Datetime', $now->year);
                    } elseif ($filter == 'year') {
                        $q->whereYear('Datetime', $now->year);
                    }
                });
            }
            
            $appointments = $query->latest('updated_at')->get();
        }

        return view('history.index', compact('appointments'));
    }

    /**
     * Menampilkan halaman detail riwayat konsultasi.
     */
    public function show(Appointment $appointment)
    {
        // Pastikan user hanya bisa melihat detail appointment miliknya sendiri
        $this->authorize('view', $appointment);

        // Load relasi yang dibutuhkan untuk halaman detail
        $appointment->load(['schedule.doctor.specialization', 'documents']);

        return view('history.show', compact('appointment'));
    }
}