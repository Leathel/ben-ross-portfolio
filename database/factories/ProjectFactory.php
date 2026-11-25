<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'summary' => fake()->sentence(),
            'description' => fake()->paragraphs(3, true),
            'case_study_html' => '<h2>Overview</h2><p>'.fake()->paragraph().'</p>',
            'category' => fake()->randomElement(['Web App', 'Open Source', 'Design', 'API']),
            'organization' => fake()->company(),
            'problem' => fake()->paragraph(),
            'role' => fake()->jobTitle(),
            'approach' => fake()->paragraph(),
            'challenges' => fake()->paragraph(),
            'results' => fake()->paragraph(),
            'technologies' => fake()->randomElements(['PHP', 'Laravel', 'Vue', 'TypeScript', 'Tailwind CSS'], 3),
            'live_url' => fake()->url(),
            'source_url' => 'https://github.com/'.fake()->userName().'/'.fake()->slug(),
            'image_url' => null,
            'card_image_url' => null,
            'is_featured' => fake()->boolean(30),
            'is_published' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
