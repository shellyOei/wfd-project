<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\LabResult;
use Carbon\Carbon;

class LabResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = Patient::all();
        
        if ($patients->isEmpty()) {
            return;
        }

        $testTypes = [
            'Complete Blood Count (CBC)',
            'Blood Chemistry Panel',
            'Lipid Profile',
            'Liver Function Test',
            'Kidney Function Test',
            'Thyroid Function Test',
            'Urine Analysis',
            'Blood Glucose Test',
            'HbA1c Test',
            'Chest X-Ray'
        ];
        
        for ($i = 1; $i <= 10; $i++) {
            $patient = $patients->random();
            $testDate = Carbon::now()->subDays(rand(1, 30));
            $resultDate = $testDate->copy()->addDays(rand(1, 3));
            
            LabResult::create([
                'lab_result_number' => 'LAB-' . date('Y') . '-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'patient_id' => $patient->id,
                'test_type' => $testTypes[array_rand($testTypes)],
                'test_date' => $testDate,
                'result_date' => $resultDate,
                'comments' => 'Test results are within normal limits. No significant abnormalities detected. Patient advised to maintain healthy lifestyle.',
                'price' => rand(100000, 500000),
            ]);
        }
    }
}
