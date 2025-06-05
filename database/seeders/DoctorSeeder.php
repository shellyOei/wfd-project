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
                'specialization_id' => 'Penyakit Dalam',
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
            [
                'doctor_number' => 'D004',
                'name' => 'Sari Dewi',
                'front_title' => 'Dr.',
                'back_title' => 'Sp.OG',
                'phone' => '081345678901',
                'address' => 'Jl. Mawar No.23, Jakarta',
                'photo' => 'default.jpg',
                'specialization_id' => 'Kandungan',
            ],
            [
                'doctor_number' => 'D005',
                'name' => 'Hendra Wijaya',
                'front_title' => 'Dr.',
                'back_title' => 'Sp.S',
                'phone' => '082234567890',
                'address' => 'Jl. Melati No.67, Medan',
                'photo' => 'default.jpg',
                'specialization_id' => 'Saraf',
            ],
            [
                'doctor_number' => 'D006',
                'name' => 'Maya Sari',
                'front_title' => 'Dr.',
                'back_title' => 'Sp.M',
                'phone' => '083345678901',
                'address' => 'Jl. Anggrek No.89, Yogyakarta',
                'photo' => 'default.jpg',
                'specialization_id' => 'Mata',
            ],
            [
                'doctor_number' => 'D007',
                'name' => 'Rudi Hartono',
                'front_title' => 'Dr.',
                'back_title' => 'Sp.THT',
                'phone' => '084456789012',
                'address' => 'Jl. Dahlia No.34, Semarang',
                'photo' => 'default.jpg',
                'specialization_id' => 'THT',
            ],
            [
                'doctor_number' => 'D008',
                'name' => 'Lina Permata',
                'front_title' => 'Dr.',
                'back_title' => 'Sp.KK',
                'phone' => '085567890123',
                'address' => 'Jl. Tulip No.56, Malang',
                'photo' => 'default.jpg',
                'specialization_id' => 'Kulit dan Kelamin',
            ],
            [
                'doctor_number' => 'D009',
                'name' => 'Agus Prasetyo',
                'front_title' => 'Dr.',
                'back_title' => 'Sp.KG',
                'phone' => '086678901234',
                'address' => 'Jl. Kenanga No.78, Solo',
                'photo' => 'default.jpg',
                'specialization_id' => 'Gigi',
            ],
            [
                'doctor_number' => 'D010',
                'name' => 'Fitri Handayani',
                'front_title' => 'Dr.',
                'back_title' => '',
                'phone' => '087789012345',
                'address' => 'Jl. Cempaka No.90, Palembang',
                'photo' => 'default.jpg',
                'specialization_id' => 'Umum',
            ],
        ];

        foreach ($doctors as $doctor) {
            $spesialis = Specialization::where('name', $doctor['specialization_id'])->first();
            $doctor['specialization_id'] = $spesialis ? $spesialis->id : null;
            Doctor::create($doctor);
        }

    }
}
