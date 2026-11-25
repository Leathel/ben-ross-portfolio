<?php

namespace Database\Factories;

use App\Models\Experience;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Experience>
 */
class ExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-8 years', '-1 year');

        return [
            'company' => fake()->company(),
            'role' => fake()->jobTitle(),
            'location' => fake()->city(),
            'employment_type' => 'Full-time',
            'start_date' => $startDate,
            'end_date' => fake()->optional()->dateTimeBetween($startDate, 'now'),
            'is_current' => false,
            'description' => fake()->paragraph(),
            'skill_tags' => [],
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
