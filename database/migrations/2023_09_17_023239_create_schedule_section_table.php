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
        Schema::create('schedule_section', function (Blueprint $table) {
            $table->foreignId('schedule_id')->constrained();
            // The constraint is added later because the section table is an alias
            $table->unsignedBigInteger('section_id');

            $table->foreign('section_id')
                ->references('id')
                ->on('academic_charge_subject')
                ->onDelete('cascade');

            $table->primary(['schedule_id', 'section_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_section');
    }
};
