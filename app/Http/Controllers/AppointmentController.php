<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    // fetch incoming appointments 
    // if appointment already passed 30 minutes from their scheduled time, it will not be shown
    public function index(Request $request)
    {
        $user = auth()->guard('user')->user();

        // fetch the patients linked to the user (account)
        $linkedPatientIds = $user->profiles->pluck('patient_id')->toArray();

        if (empty($linkedPatientIds)) {
            return view('user.appointments.index', [
                'linkedPatients' => collect(),
                'groupedAppointments' => collect(),
                'selectedPatientId' => null,
            ]);
        }

        $linkedPatients = Patient::whereIn('id', $linkedPatientIds)->get();

        $selectedPatientId = $request->input('patient_id');

        if (empty($selectedPatientId) || !in_array($selectedPatientId, $linkedPatientIds)) {
            $selectedPatientId = $linkedPatientIds[0] ?? null;
        }

        $appointments = [];
        if ($selectedPatientId) {
            $appointments = Appointment::with(['patient', 'schedule', 'schedule.dayAvailable.doctor']) 
                                   ->join('practice_schedules', 'appointments.schedule_id', '=', 'practice_schedules.id')
                                   ->where('appointments.patient_id', $selectedPatientId) 
                                   ->where('appointments.status', 1) 
                                   ->orderBy('practice_schedules.Datetime', 'asc') 
                                   ->select('appointments.*')
                                   ->get();
        }

        $groupedAppointments = $appointments->groupBy(function($appointment) {
            return Carbon::parse($appointment->schedule->Datetime)->format('Y-m-d');
        });

        return view('user.appointment', [
            'linkedPatients' => $linkedPatients,
            'groupedAppointments' => $groupedAppointments,
            'selectedPatientId' => $selectedPatientId,
        ]);
    }
}
