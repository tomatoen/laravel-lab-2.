<?php

namespace Database\Factories;

use App\Models\Editor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Editor>
 */
class EditorFactory extends Factory
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
                'department' => fake()->randomElement(['Фантастика', 'Наукова', 'Дитяча', 'Академічна']),
            ];
    
    }
}
