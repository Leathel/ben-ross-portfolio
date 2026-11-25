<?php

namespace Database\Factories;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Skill>
 */
class SkillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'category' => fake()->randomElement(['Languages', 'Frameworks', 'Tools', 'Practices']),
            'proficiency' => fake()->numberBetween(50, 100),
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
