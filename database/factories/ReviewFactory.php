<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'book_id' => null,
            'review' => fake()->paragraph(),
            'rating' => fake()->numberBetween(1, 5),
            'created_at' => fake()->dateTimeBetween('-2 years'),
            'updated_at' => function (array $attributes) {
                return fake()->dateTimeBetween($attributes['created_at'], 'now');
            }
        ];
    }

    public function make_review_spectrum(int $stars)
    {
        return $this->state(function (array $attributes) use ($stars) {
            return [
                'rating' => fake()->numberBetween(
                    max($stars - 1, 1),
                    min($stars + 1, 5)
                ),
            ];
        });
    }
}
