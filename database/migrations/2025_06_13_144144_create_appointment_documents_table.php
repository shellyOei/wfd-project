<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointment_documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('appointment_id')->constrained()->onDelete('cascade');
            $table->string('file_name'); // e.g., "Medical Report.pdf"
            $table->string('file_path'); // The path where the file is stored
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_documents');
    }
};