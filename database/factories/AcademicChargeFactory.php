<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AcademicCharge>
 */
class AcademicChargeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'year' => fake()->numberBetween(2020, 2024),
            'semester' => fake()->randomElement(['1', '2', 'TAV', 'TAI']),
            'is_hidden' => fake()->boolean(),
        ];
    }
}
