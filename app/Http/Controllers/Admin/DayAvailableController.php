<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DayAvailable;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class DayAvailableController extends Controller
{
    /**
     * Display a listing of the day availables.
     */
    public function index(Request $request)
    {
        $doctors = Doctor::orderBy('name')->get();
        $selectedDoctorId = $request->input('doctor_id');
        $selectedDay = $request->input('day');

        $dayAvailablesQuery = DayAvailable::with('doctor');

        if ($selectedDoctorId) {
            $dayAvailablesQuery->where('doctor_id', $selectedDoctorId);
        }
        if ($selectedDay) {
            $dayAvailablesQuery->where('day', $selectedDay);
        }

        $dayAvailables = $dayAvailablesQuery->orderBy('day')->orderBy('start_time')->paginate(10);

        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return view('admin.day-availables.index', compact('dayAvailables', 'doctors', 'selectedDoctorId', 'selectedDay', 'daysOfWeek'));
    }

    /**
     * Show the form for creating a new day available.
     */
    public function create()
    {
        $doctors = Doctor::orderBy('name')->get();
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        return view('admin.day-availables.create', compact('doctors', 'daysOfWeek'));
    }

    /**
     * Store a newly created day available in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'day' => ['required', 'string', 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'],
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        // Cek tumpang tindih jadwal untuk dokter yang sama pada hari yang sama
        $overlap = DayAvailable::where('doctor_id', $validatedData['doctor_id'])
                               ->where('day', $validatedData['day'])
                               ->where(function ($query) use ($validatedData) {
                                   $query->whereBetween('start_time', [$validatedData['start_time'], Carbon::parse($validatedData['end_time'])->subMinute()->format('H:i')])
                                         ->orWhereBetween('end_time', [Carbon::parse($validatedData['start_time'])->addMinute()->format('H:i'), $validatedData['end_time']])
                                         ->orWhere(function ($query) use ($validatedData) {
                                             $query->where('start_time', '<=', $validatedData['start_time'])
                                                   ->where('end_time', '>=', $validatedData['end_time']);
                                         });
                               })->count();

        if ($overlap > 0) {
            return redirect()->back()->withInput()->withErrors(['time_overlap' => 'The selected time slot overlaps with an existing availability for this doctor on this day.']);
        }

        DayAvailable::create($validatedData);

        return redirect()->route('admin.day-availables.index')->with('success', 'Day availability added successfully!');
    }

    /**
     * Show the form for editing the specified day available.
     */
    public function edit(DayAvailable $dayAvailable)
    {
        $doctors = Doctor::orderBy('name')->get();
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        return view('admin.day-availables.edit', compact('dayAvailable', 'doctors', 'daysOfWeek'));
    }

    /**
     * Update the specified day available in storage.
     */
    public function update(Request $request, DayAvailable $dayAvailable)
    {
        $validatedData = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'day' => ['required', 'string', 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'],
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        // Cek tumpang tindih, abaikan jadwal yang sedang diedit
        $overlap = DayAvailable::where('doctor_id', $validatedData['doctor_id'])
                               ->where('day', $validatedData['day'])
                               ->where('id', '!=', $dayAvailable->id)
                               ->where(function ($query) use ($validatedData) {
                                   $query->whereBetween('start_time', [$validatedData['start_time'], Carbon::parse($validatedData['end_time'])->subMinute()->format('H:i')])
                                         ->orWhereBetween('end_time', [Carbon::parse($validatedData['start_time'])->addMinute()->format('H:i'), $validatedData['end_time']])
                                         ->orWhere(function ($query) use ($validatedData) {
                                             $query->where('start_time', '<=', $validatedData['start_time'])
                                                   ->where('end_time', '>=', $validatedData['end_time']);
                                         });
                               })->count();

        if ($overlap > 0) {
            return redirect()->back()->withInput()->withErrors(['time_overlap' => 'The selected time slot overlaps with an existing availability for this doctor on this day.']);
        }

        $dayAvailable->update($validatedData);

        return redirect()->route('admin.day-availables.index')->with('success', 'Day availability updated successfully!');
    }

    /**
     * Remove the specified day available from storage.
     */
    public function destroy(DayAvailable $dayAvailable)
    {
        $dayAvailable->delete();
        return redirect()->route('admin.day-availables.index')->with('success', 'Day availability deleted successfully!');
    }
}