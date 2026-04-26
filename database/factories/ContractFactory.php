<?php

namespace Database\Factories;

use App\Models\Contract;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contract>
 */
class ContractFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'book_id' => \App\Models\Book::inRandomOrder()->first()->id ?? \App\Models\Book::factory(),
            'editor_id' => \App\Models\Editor::inRandomOrder()->first()->id ?? \App\Models\Editor::factory(),
            'amount' => fake()->randomFloat(2, 500, 10000),
            'is_active' => fake()->boolean(80),
        ];
    }
}
