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
            $table->uuid('day_available_id')->nullable(); 
            $table->foreign('day_available_id')->references('id')->on('day_availables')->onDelete('set null');
            $table->dateTime('Datetime'); 
            $table->timestamps();
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
