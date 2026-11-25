<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Profile>
 */
class ProfileFactory extends Factory
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
            'headline' => fake()->jobTitle(),
            'tagline' => fake()->sentence(),
            'hero_intro' => fake()->paragraph(),
            'now_building' => null,
            'now_learning' => null,
            'now_cooking' => null,
            'bio' => fake()->paragraphs(3, true),
            'working_style' => fake()->paragraph(),
            'education' => fake()->sentence(),
            'outside_the_code_html' => null,
            'location' => fake()->city(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'website_url' => fake()->url(),
            'github_url' => 'https://github.com/'.fake()->userName(),
            'linkedin_url' => 'https://www.linkedin.com/in/'.fake()->userName(),
            'available_for_work' => true,
            'years_experience' => fake()->numberBetween(2, 15),
            'resume_url' => null,
            'logo_url' => null,
            'headshot_url' => null,
            'about_image_url' => null,
            'favicon_url' => null,
        ];
    }
}
