<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users and patients
        $users = User::all();
        $patients = Patient::all();

        // Create profiles linking users to patients
        $profiles = [];
        
        for ($i = 0; $i < 10 && $i < $users->count() && $i < $patients->count(); $i++) {
            $profiles[] = [
                'user_id' => $users[$i]->id,
                'patient_id' => $patients[$i]->id,
            ];
        }

        DB::table('profiles')->insert($profiles);
    }
}
