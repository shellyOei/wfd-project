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
                    'name' => 'John Doe',
                    'email' => 'abc@gmail.com',
                    'password' => Hash::make('password'),
                ],
                [
                    'name' => 'Jane Doe',
                    'email' => 'def@gmail.com',
                    'password' => Hash::make('password'),
                ],
                [
                    'name' => 'Sam Smith',
                    'email' => 'ghi@gmail.com',
                    'password' => Hash::make('password'),
                ],
            ];
            foreach ($users as $user) {
                User::create($user);
            }
        }
    }
