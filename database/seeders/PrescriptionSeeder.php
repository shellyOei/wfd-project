<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Medicine;
use App\Models\Appointment;
use App\Models\Prescription;

class PrescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicines = Medicine::all();
        $appointments = Appointment::all();
        
        if ($medicines->isEmpty() || $appointments->isEmpty()) {
            return;
        }

        for ($i = 0; $i < 10; $i++) {
            $medicine = $medicines->random();
            $appointment = $appointments->random();
            $quantity = rand(5, 30);
            $quantityBought = rand(0, $quantity);
            
            Prescription::create([
                'medicine_id' => $medicine->id,
                'appointment_id' => $appointment->id,
                'quantity' => $quantity,
                'quantity_bought' => $quantityBought,
                'price' => $medicine->price * $quantity,
            ]);
        }
    }
}
