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
        Schema::create('academic_charge_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_charge_id')->constrained();
            $table->foreignId('subject_id')->constrained();
            $table->foreignId('career_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('school_id')->nullable()->constrained()->onDelete('set null');
            $table->string('code'); // Like ASY5512-004D
            $table->string('teacher');
            $table->string('shift');
            // $table->json('options');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_charge_subject');
    }
};
