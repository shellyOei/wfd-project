<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'test user 1',
                'email' => 'testuser1@gmail.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'test user 2',
                'email' => 'testuser2@gmail.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'test user 3',
                'email' => 'testuser3@gmail.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'test user 4',
                'email' => 'testuser4@gmail.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'test user 5',
                'email' => 'testuser5@gmail.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'test user 6',
                'email' => 'testuser6@gmail.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'test user 7',
                'email' => 'testuser7@gmail.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'test user 8',
                'email' => 'testuser8@gmail.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'test user 9',
                'email' => 'testuser9@gmail.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'test user 10',
                'email' => 'testuser10@gmail.com',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

    }
}
