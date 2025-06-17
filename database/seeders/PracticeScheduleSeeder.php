<?php

namespace Database\Seeders;

use App\Models\DayAvailable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\PracticeSchedule;
use Carbon\Carbon;

class PracticeScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = Doctor::all();
        $daysAvailable = DayAvailable::all();

        if ($doctors->isEmpty() || $daysAvailable->isEmpty()) {
            return;
        }

        $schedulesPerDoctor = 5;

        foreach ($doctors as $doctor) {
            $selectedDayAvailabilities = $daysAvailable->random(rand(1, min(3, $daysAvailable->count())))->unique('id');

            for ($i = 0; $i < $schedulesPerDoctor; $i++) {
                $dayAvailable = $selectedDayAvailabilities->random();

                // Extract the day name (e.g., 'Monday', 'Tuesday') from the chosen DayAvailable record.
                $targetDayName = $dayAvailable->day;

                // Parse the start and end times for this specific availability slot.
                $startTime = Carbon::parse($dayAvailable->start_time);
                $endTime = Carbon::parse($dayAvailable->end_time);

                // --- Generate the Date for the Practice Schedule ---
                // Start from the current date and find the next occurrence of the target day of the week.
                // This ensures all generated schedule dates are in the future.
                $baseDate = Carbon::now();
                $baseDate->next($targetDayName); // Move to the next instance of the target day.

                // Add a random number of weeks (0 to 4) to further spread out the schedules
                // across approximately one month from the current date.
                $baseDate->addWeeks(rand(0, 4));

                // --- Generate a Random Time within the Slot's Range ---
                // Calculate the total duration of the available slot in minutes.
                $durationMinutes = $endTime->diffInMinutes($startTime);
                // Define the desired interval for time slots (e.g., 30 minutes).
                $slotIntervalMinutes = 30;

                // Calculate how many distinct time slots (of the defined interval) fit within the duration.
                $numSlots = floor($durationMinutes / $slotIntervalMinutes);

                $generatedTime = null;
                if ($numSlots > 0) {
                    // If there are valid slots, pick a random index to select a time slot.
                    $randomSlotIndex = rand(0, $numSlots - 1);
                    // Create a copy of the start time and add the minutes corresponding to the random slot,
                    // then format it to 'HH:MM:SS'.
                    $generatedTime = $startTime->copy()->addMinutes($randomSlotIndex * $slotIntervalMinutes)->format('H:i:s');
                } 

                // --- Combine the generated Date and Time to form the final Datetime string ---
                $datetime = $baseDate->format('Y-m-d') . ' ' . $generatedTime;

                // --- Create the PracticeSchedule Record ---
                // Create a new PracticeSchedule entry.
                // IMPORTANT: As per your requested $fillable, 'doctor_id' is no longer included here.
                // This means PracticeSchedule entries are now general slots, not explicitly tied to a doctor in this table.
                PracticeSchedule::create([
                    'day_available_id' => $dayAvailable->id, 
                    'Datetime' => $datetime,                
                ]);
            }
        }
    }
}
