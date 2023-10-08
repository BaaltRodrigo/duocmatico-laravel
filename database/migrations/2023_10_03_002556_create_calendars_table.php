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
        Schema::create('calendars', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique(); // for public purposes
            $table->boolean('is_public')->default(false); // for public purposes
            $table->string('name');
            $table->string('description')->nullable();
            $table->json('options')->nullable();

            $table->string('user_id')->nullable();
            $table->foreignId('academic_charge_id')->constrained()->cascade('set null');
            $table->morphs('calendarable'); // schools or careers

            $table->timestamps();

            // Constraints
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascade('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendars');
    }
};
