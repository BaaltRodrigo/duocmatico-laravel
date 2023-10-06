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
        Schema::create('calendar_section', function (Blueprint $table) {
            $table->foreignId('calendar_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('section_id');

            $table->foreign('section_id')
                ->references('id')
                ->on('academic_charge_subject')
                ->cascadeOnDelete();

            $table->primary(['calendar_id', 'section_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_section');
    }
};
