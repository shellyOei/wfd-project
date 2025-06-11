<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Doctor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'password' => 'password',
                'doctor_id' => null, // bukan dokter
            ],
            [
                'name' => 'Dr. Ahmad Yusuf',
                'email' => 'ahmad.yusuf@gmail.com',
                'password' => 'password',
                'doctor_id' => Doctor::where('doctor_number', 'D001')->value('id'),
            ],
            [
                'name' => 'Dr. Rina Kartika',
                'email' => 'rina.kartika@gmail.com',
                'password' => 'password',
                'doctor_id' => Doctor::where('doctor_number', 'D002')->value('id'),
            ],
            [
                'name' => 'Dr. Budi Santoso',
                'email' => 'budi.santoso@gmail.com',
                'password' => 'password',
                'doctor_id' => Doctor::where('doctor_number', 'D003')->value('id'),
            ],
            [
                'name' => 'Dr. Sari Dewi',
                'email' => 'sari.dewi@gmail.com',
                'password' => 'password',
                'doctor_id' => Doctor::where('doctor_number', 'D004')->value('id'),
            ],
            [
                'name' => 'Dr. Hendra Wijaya',
                'email' => 'hendra.wijaya@gmail.com',
                'password' => 'password',
                'doctor_id' => Doctor::where('doctor_number', 'D005')->value('id'),
            ],
            [
                'name' => 'Dr. Maya Sari',
                'email' => 'maya.sari@gmail.com',
                'password' => 'password',
                'doctor_id' => Doctor::where('doctor_number', 'D006')->value('id'),
            ],
            [
                'name' => 'Dr. Rudi Hartono',
                'email' => 'rudi.hartono@gmail.com',
                'password' => 'password',
                'doctor_id' => Doctor::where('doctor_number', 'D007')->value('id'),
            ],
            [
                'name' => 'Dr. Lina Permata',
                'email' => 'lina.permata@gmail.com',
                'password' => 'password',
                'doctor_id' => Doctor::where('doctor_number', 'D008')->value('id'),
            ],
            [
                'name' => 'Admin Staff',
                'email' => 'admin.staff@gmail.com',
                'password' => 'password',
                'doctor_id' => null, // bukan dokter
            ],
        ];

        foreach ($admins as $admin) {
            Admin::create($admin);
        }
    }
}
