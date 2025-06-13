<?php

namespace Database\Seeders;

use App\Models\DayAvailable;
use App\Models\Doctor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DayAvailableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $doctors = Doctor::all();

        $availabilities = [
            // Monday
            ['day' => 'Monday', 'start_time' => '09:00:00', 'end_time' => '12:00:00'],
            ['day' => 'Monday', 'start_time' => '14:00:00', 'end_time' => '17:00:00'],

            // Tuesday
            ['day' => 'Tuesday', 'start_time' => '10:00:00', 'end_time' => '13:00:00'],
            ['day' => 'Tuesday', 'start_time' => '15:00:00', 'end_time' => '18:00:00'],

            // Wednesday
            ['day' => 'Wednesday', 'start_time' => '08:00:00', 'end_time' => '12:00:00'],

            // Thursday
            ['day' => 'Thursday', 'start_time' => '09:00:00', 'end_time' => '12:00:00'],
            ['day' => 'Thursday', 'start_time' => '14:00:00', 'end_time' => '17:00:00'],

            // Friday
            ['day' => 'Friday', 'start_time' => '10:00:00', 'end_time' => '16:00:00'],

            // Saturday
            ['day' => 'Saturday', 'start_time' => '09:00:00', 'end_time' => '13:00:00'],

            // Note: Sunday is intentionally left out to show some doctors might not work on weekends.
        ];


        foreach ($doctors as $doctor) {
            foreach ($availabilities as $availability) {
                DayAvailable::create([
                    'doctor_id' => $doctor->id, // Assign to the current doctor
                    'day' => $availability['day'],
                    'start_time' => $availability['start_time'],
                    'end_time' => $availability['end_time'],
                ]);
            }
        }

    }
}
