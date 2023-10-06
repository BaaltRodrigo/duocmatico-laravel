<?php

namespace Database\Factories;

use App\Models\AcademicCharge;
use App\Models\Career;
use App\Models\School;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Section>
 */
class SectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => fake()->unique()->randomNumber(4),
            'shift' => fake()->randomElement(['M', 'T', 'N']),
            'teacher' => fake()->name(),
            'academic_charge_id' => AcademicCharge::factory(),
            'subject_id' => Subject::factory(),
            'career_id' => Career::factory(),
            'school_id' => School::factory(),
        ];
    }
}
