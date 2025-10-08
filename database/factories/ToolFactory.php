<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tool>
 */
class ToolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->company() . ' AI';
        
        return [
            'category_id' => Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'long_description' => fake()->paragraph(),
            'website_url' => fake()->url(),
            'documentation_url' => fake()->url(),
            'logo_url' => null,
            'pricing_model' => fake()->randomElement(['free', 'freemium', 'paid', 'enterprise']),
            'price_description' => fake()->randomElement(['Free', '$20/month', '$100/month', 'Contact for pricing']),
            'ryan_rating' => fake()->numberBetween(1, 10),
            'ryan_notes' => fake()->paragraph(),
            'ryan_last_used' => fake()->dateTimeBetween('-1 year', 'now'),
            'features' => fake()->words(5),
            'use_cases' => fake()->words(3),
            'integrations' => fake()->words(4),
            'is_featured' => false,
            'is_active' => true,
            'views_count' => fake()->numberBetween(0, 1000),
            'first_reviewed_at' => fake()->dateTimeBetween('-2 years', 'now'),
        ];
    }

    /**
     * Indicate that the tool is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    /**
     * Indicate that the tool is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the tool is free.
     */
    public function free(): static
    {
        return $this->state(fn (array $attributes) => [
            'pricing_model' => 'free',
            'price_description' => 'Free',
        ]);
    }

    /**
     * Indicate that the tool is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'pricing_model' => 'paid',
            'price_description' => '$' . fake()->numberBetween(10, 100) . '/month',
        ]);
    }
}