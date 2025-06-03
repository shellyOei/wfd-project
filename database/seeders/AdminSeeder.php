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
                'doctor_id' => null, // bkn dokter
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
        ];

        foreach ($admins as $admin) {
            Admin::create($admin);
        }
    }
}
