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
                'email' => 'testuser1@gmail.com',
                'password' => 'password',
            ],
            [
                'email' => 'testuser2@gmail.com',
                'password' => 'password',
            ],
            [
                'email' => 'testuser3@gmail.com',
                'password' => 'password',
            ],
            [
                'email' => 'testuser4@gmail.com',
                'password' => 'password',
            ],
            [
                'email' => 'testuser5@gmail.com',
                'password' => 'password',
            ],
            [
                'email' => 'testuser6@gmail.com',
                'password' => 'password',
            ],
            [
                'email' => 'testuser7@gmail.com',
                'password' => 'password',
            ],
            [
                'email' => 'testuser8@gmail.com',
                'password' => 'password',
            ],
            [
                'email' => 'testuser9@gmail.com',
                'password' => 'password',
            ],
            [
                'email' => 'testuser10@gmail.com',
                'password' => 'password',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

    }
}
