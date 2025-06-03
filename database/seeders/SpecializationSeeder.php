<?php

namespace Database\Seeders;

use App\Models\Specialization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specializations = [
            'Umum',
            'Penyakit Dalam',
            'Anak',
            'Kandungan',
            'Saraf',
            'Bedah',
            'Gigi',
            'Mata',
            'THT',
            'Kulit dan Kelamin',
        ];

        foreach ($specializations as $special) {
            Specialization::create(['name' => $special]);
        }
    }
}
