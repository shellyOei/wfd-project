<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Invoice;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = ['cash', 'credit_card', 'debit_card', 'bank_transfer', 'e_wallet'];

        for ($i = 1; $i <= 10; $i++) {
            $isBpjs = rand(0, 1) == 1;
            $basePrice = rand(50000, 500000);
            $totalPrice = $isBpjs ? $basePrice * 0.1 : $basePrice; // BPJS gets 90% discount

            Invoice::create([
                'invoice_number' => 'INV-' . date('Y') . '-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'is_bpjs' => $isBpjs,
                'total_price' => $totalPrice,
                'created_at' => now()->subDays(rand(0, 30)),
                'updated_at' => now(),
            ]);
        }
    }
}
