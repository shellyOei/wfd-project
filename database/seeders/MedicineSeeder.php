<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Medicine;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicines = [
            [
                'name' => 'Paracetamol',
                'unit' => 'tablet',
                'price' => 2500.00,
            ],
            [
                'name' => 'Amoxicillin',
                'unit' => 'capsule',
                'price' => 5000.00,
            ],
            [
                'name' => 'Ibuprofen',
                'unit' => 'tablet',
                'price' => 3000.00,
            ],
            [
                'name' => 'Omeprazole',
                'unit' => 'capsule',
                'price' => 8000.00,
            ],
            [
                'name' => 'Cetirizine',
                'unit' => 'tablet',
                'price' => 4000.00,
            ],
            [
                'name' => 'Metformin',
                'unit' => 'tablet',
                'price' => 6000.00,
            ],
            [
                'name' => 'Simvastatin',
                'unit' => 'tablet',
                'price' => 12000.00,
            ],
            [
                'name' => 'Amlodipine',
                'unit' => 'tablet',
                'price' => 7500.00,
            ],
            [
                'name' => 'Losartan',
                'unit' => 'tablet',
                'price' => 9000.00,
            ],
            [
                'name' => 'Vitamin D3',
                'unit' => 'softgel',
                'price' => 15000.00,
            ],
        ];

        foreach ($medicines as $medicine) {
            Medicine::create($medicine);
        }
    }
}
