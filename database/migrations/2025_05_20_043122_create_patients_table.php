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
        Schema::create('patients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('patient_number')->unique();
            $table->string('name');
            $table->string('phone');
            $table->enum('sex', ['male', 'female']);
            $table->date('date_of_birth');
            $table->text('address');
            $table->text('occupation');
            $table->string('blood_type')->nullable();
            $table->string('rhesus_factor')->nullable();
            $table->string('id_card_number');
            $table->string('BPJS_number')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
