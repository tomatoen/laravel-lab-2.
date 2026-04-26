<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'genre_id' => \App\Models\Genre::inRandomOrder()->first()->id ?? \App\Models\Genre::factory(),
            'author_id' => \App\Models\Author::inRandomOrder()->first()->id ?? \App\Models\Author::factory(),
            'title' => fake()->catchPhrase(),
            'published_year' => fake()->numberBetween(1990, 2024),
        ];
    }
}
