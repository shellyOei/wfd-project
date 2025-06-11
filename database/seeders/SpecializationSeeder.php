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
            [
                'name' => 'Umum',
                'icon' => 'icons/stethoscope.png'
            ],
            [
                'name' => 'Penyakit Dalam',
                'icon' => 'icons/pulse.png'
            ],
             [
                'name' => 'Anak',
                'icon' => 'icons/teddy-bear.png'
            ],
            [
                'name' => 'Kandungan',
                'icon' => 'icons/pregnant.png'
            ],
            [
                'name' => 'Saraf',
                'icon' => 'icons/nerve.png'
            ],
             [
                'name' => 'Bedah',
                'icon' => 'icons/scalpel.png'
            ],
            [
                'name' => 'Gigi',
                'icon' => 'icons/tooth.png'
            ],
            
            [
                'name' => 'Mata',
                'icon' => 'icons/eye.png'
            ],
            [
                'name' => 'THT',
                'icon' => 'icons/ear.png'
            ],
            [
                'name' => 'Kulit dan Kelamin',
                'icon' => 'icons/skin.png'
            ],
        ];


         foreach ($specializations as $specialization) {
            Specialization::create([
                'name' => $specialization['name'],
                'icon' => $specialization['icon'],

            ]);
        }
    }
}
