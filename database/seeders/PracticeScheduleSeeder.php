<?php

namespace Database\Seeders;

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
        
        if ($doctors->isEmpty()) {
            return;
        }

        // Create 10 practice schedules
        for ($i = 0; $i < 10; $i++) {
            $doctor = $doctors->random();
            $baseDate = Carbon::now()->addDays(rand(0, 30));
            
            // Generate different time slots
            $timeSlots = [
                '08:00:00', '09:00:00', '10:00:00', '11:00:00',
                '13:00:00', '14:00:00', '15:00:00', '16:00:00'
            ];
            
            $time = $timeSlots[array_rand($timeSlots)];
            $datetime = $baseDate->format('Y-m-d') . ' ' . $time;
            
            PracticeSchedule::create([
                'doctor_id' => $doctor->id,
                'Datetime' => $datetime,
            ]);
        }
    }
}
