<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Base data (no dependencies)
        $this->call([
            UserSeeder::class,
            SpecializationSeeder::class,
        ]);

        // Patients and related data
        $this->call([
            PatientSeeder::class,
            ProfileSeeder::class, // depends on users and patients
        ]);

        // Doctors (depends on specializations)
        $this->call([
            DoctorSeeder::class,
        ]);

        // Admins (depends on doctors)
        $this->call([
            AdminSeeder::class,
        ]);

        // Medical data
        $this->call([
            MedicineSeeder::class,
            PracticeScheduleSeeder::class, // depends on doctors
            DayAvailableSeeder::class, 
        ]);

        // Appointments and related data (depends on patients and schedules)
        $this->call([
            AppointmentSeeder::class,
            PrescriptionSeeder::class, // depends on medicines and appointments
            InvoiceSeeder::class,
            LabResultSeeder::class, // depends on patients
        ]);
    }
}
