<?php

namespace Database\Factories;

use App\Models\AcademicCharge;
use App\Models\Career;
use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Calendar>
 */
class CalendarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $career = Career::factory()->create();
        $school = School::factory()->create();
        $calendarable = fake()->randomElement([$career, $school]);
        return [
            'name' => fake()->name(),
            'uuid' => fake()->uuid(),
            'description' => fake()->text(),
            'is_public' => fake()->boolean(),
            'options' => null,
            'academic_charge_id' => AcademicCharge::factory(),
            'calendarable_id' => $calendarable->id,
            'calendarable_type' => get_class($calendarable),
            'user_id' => null
        ];
    }
}
