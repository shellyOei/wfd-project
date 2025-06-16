<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('practice_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Foreign key ke tabel doctors
            $table->uuid('doctor_id');
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');

            // Foreign key ke tabel day_availables (opsional tapi disarankan)
            // Ini akan menunjukkan dari pola DayAvailable mana booking ini berasal.
            $table->uuid('day_available_id')->nullable(); // Bisa nullable jika ada booking manual
            $table->foreign('day_available_id')->references('id')->on('day_availables')->onDelete('set null');

            $table->dateTime('Datetime'); // Tanggal dan waktu spesifik booking

            $table->timestamps();

            // Penting: Pastikan tidak ada dua booking yang tumpang tindih untuk dokter yang sama
            $table->unique(['doctor_id', 'Datetime']);
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practice_schedules');
    }
};
