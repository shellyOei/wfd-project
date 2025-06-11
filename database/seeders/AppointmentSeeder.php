<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\PracticeSchedule;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = Patient::all();
        $schedules = PracticeSchedule::all();
        
        if ($patients->isEmpty() || $schedules->isEmpty()) {
            return;
        }

        $appointmentTypes = ['consultation', 'follow_up', 'emergency', 'routine_check'];
        
        for ($i = 0; $i < 10 && $i < $schedules->count(); $i++) {
            $patient = $patients->random();
            $schedule = $schedules[$i];
            
            Appointment::create([
                'patient_id' => $patient->id,
                'schedule_id' => $schedule->id,
                'queue_number' => $i + 1,
                'subjective' => 'Patient complains of headache and fever for 2 days. No nausea or vomiting.',
                'objective' => 'Temperature: 38.5°C, Blood pressure: 120/80 mmHg, Heart rate: 88 bpm. Patient appears alert and oriented.',
                'assessment' => 'Viral fever syndrome. Rule out bacterial infection.',
                'plan' => 'Prescribe paracetamol for fever. Increase fluid intake. Follow up in 3 days if symptoms persist.',
                'type' => $appointmentTypes[array_rand($appointmentTypes)],
                'is_bpjs' => rand(0, 1) == 1,
                'notes' => 'Patient advised to rest and avoid strenuous activities.',
            ]);
        }
    }
}
