<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.a
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('patient_id'); 
            $table->uuid('schedule_id')->unique();

            $table->integer('queue_number')->nullable();
            $table->longText('subjective')->nullable();
            $table->longText('objective')->nullable();
            $table->longText('assessment')->nullable();
            $table->longText('plan')->nullable();
            $table->string('type')->default('general'); 
            $table->boolean('is_bpjs')->default(false);
            $table->tinyInteger('status')->default(1); // 1=confirmed, 2=Cancelled, 3=Completed
            $table->longText('notes')->nullable();

            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('schedule_id')->references('id')->on('practice_schedules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
