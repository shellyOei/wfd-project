<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'email' => 'tesuser@gmail.com',
                'password' => 'password',
                'role' => 'user',
            ],
            [
                'email' => 'tesadmin@gmail.com',
                'password' => 'password',
                'role' => 'admin',
            ],
            [
                'email' => 'tesdokter@gmail.com',
                'password' => 'password',
                'role' => 'doctor',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

    }
}
