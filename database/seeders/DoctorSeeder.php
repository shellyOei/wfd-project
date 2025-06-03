<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Specialization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = [
            [
                'doctor_number' => 'D001',
                'name' => 'Ahmad Yusuf',
                'front_title' => 'Dr.',
                'back_title' => 'Sp.PD',
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No.1, Jakarta',
                'photo' => 'default.jpg',
                'specialization_id' => 'Umum',
            ],
            [
                'doctor_number' => 'D002',
                'name' => 'Rina Kartika',
                'front_title' => 'Dr.',
                'back_title' => 'Sp.A',
                'phone' => '081298765432',
                'address' => 'Jl. Kenangan No.12, Bandung',
                'photo' => 'default.jpg',
                'specialization_id' => 'Anak',
            ],
            [
                'doctor_number' => 'D003',
                'name' => 'Budi Santoso',
                'front_title' => 'Dr.',
                'back_title' => 'Sp.B',
                'phone' => '082112345678',
                'address' => 'Jl. Sehat No.45, Surabaya',
                'photo' => 'default.jpg',
                'specialization_id' => 'Bedah',
            ],
        ];

        foreach ($doctors as $doctor) {
            $spesialis = Specialization::where('name', $doctor['specialization_id'])->first();
            $doctor['specialization_id'] = $spesialis ? $spesialis->id : null;
            Doctor::create($doctor);
        }

    }
}
