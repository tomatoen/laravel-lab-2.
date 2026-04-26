<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Спочатку створюємо незалежні дані
        \App\Models\Genre::factory(10)->create();
        \App\Models\Author::factory(15)->create();
        \App\Models\Editor::factory(5)->create();
    
        // Тепер книги, які прив'яжуться до створених жанрів та авторів
        \App\Models\Book::factory(30)->create();
    
        // І контракти, які прив'яжуться до книг та редакторів
        \App\Models\Contract::factory(30)->create();
    }
}
