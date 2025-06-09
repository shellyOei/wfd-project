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
        Schema::create('doctors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('specialization_id');
            $table->uuid('admin_id')->nullable();

            $table->string('doctor_number')->unique();
            $table->string('name');
            $table->string('front_title');
            $table->string('back_title');
            $table->string('phone')->unique();
            $table->string('address');
            $table->string('photo');

            $table->foreign('specialization_id')->references('id')->on('specializations')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
