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
                'name' => 'Test User 1',
                'email' => 'tesuser1@gmail.com',
                'password' => 'password',
            ],
            [
                'name' => 'Test User 2',
                'email' => 'tesuser2@gmail.com',
                'password' => 'password',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

    }
}
