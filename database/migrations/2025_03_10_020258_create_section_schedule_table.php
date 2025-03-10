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
        Schema::create('section_schedule', function (Blueprint $table) {
            $table->id();

            $table->foreignId("section_id")->constrained();
            $table->foreignId("schedule_id")->constrained();

            $table->unique(['section_id', 'schedule_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('section_schedule');
    }
};
