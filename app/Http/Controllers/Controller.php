<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Contract;
use App\Models\Genre;
use App\Models\Author;

class EloquentController extends Controller
{
    // Читання (index)
    public function index() {
        return response()->json([
            'all_genres' => Genre::all(),
            'recent_books' => Book::where('published_year', '>', 2000)->orderBy('title')->take(10)->get()
        ]);
    }

    // Читання одного запису (show)
    public function show($id) {
        return response()->json([
            'book_by_id' => Book::findOrFail($id),
            'author_by_email' => Author::whereNotNull('email')->first() 
        ]);
    }

    // Створення (store)
    public function store() {
        // Спосіб 1: через new + save()
        $genre = new Genre();
        $genre->name = 'Новий тестовий жанр ' . rand(1, 999);
        $genre->description = 'Опис створений через save()';
        $genre->save();

        // Спосіб 2: через create()
        $author = Author::create([
            'first_name' => 'Іван',
            'last_name' => 'Франко',
            'email' => 'franko' . rand(1, 999) . '@example.com'
        ]);

        return response()->json(['genre_saved' => $genre, 'author_created' => $author]);
    }

    // Оновлення (update)
    public function update($id) {
        // Спосіб 1: find + update()
        $genre = Genre::find($id);
        if($genre) {
            $genre->update(['description' => 'Оновлено через Eloquent']);
        }

        // Спосіб 2: масове оновлення
        Book::where('published_year', '<', 1950)->update(['published_year' => 1950]);

        return response()->json(['message' => "Жанр з ID $id оновлено, старі книги підтягнуто до 1950 року."]);
    }

    // Видалення (destroy)
    public function destroy($id) {
        $genre = Genre::find($id);
        if($genre) {
            $genre->delete(); // Звичайне видалення
        }
        
        // Масове видалення: Author::destroy([1, 2, 3]); (закоментовано, щоб не зламати базу)

        return response()->json(['message' => "Жанр з ID $id успішно видалено."]);
    }

    // Агрегатні функції (stats)
    public function stats() {
        return response()->json([
            'total_contracts' => Contract::count(),
            'max_contract_amount' => Contract::max('amount'),
            'avg_contract_amount' => Contract::avg('amount')
        ]);
    }

    // Робота з великими даними (bigData)
    public function bigData() {
        $memory = [];
        
        Book::chunk(10, function ($books) {});
        $memory['peak_after_chunk_kb'] = round(memory_get_peak_usage() / 1024, 2);

        foreach (Book::cursor() as $book) {}
        $memory['peak_after_cursor_kb'] = round(memory_get_peak_usage() / 1024, 2);

        foreach (Book::lazy() as $book) {}
        $memory['peak_after_lazy_kb'] = round(memory_get_peak_usage() / 1024, 2);

        return response()->json($memory);
    }
}