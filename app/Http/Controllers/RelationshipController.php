<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;

class RelationshipController extends Controller
{
    // 1. Отримання пов'язаної моделі та колекції
    public function getRelated() {
        $book = Book::first();
        $author = Author::first();
        
        return response()->json([
            'book_genre' => $book->genre, // Пов'язана модель
            'author_books' => $author->books // Колекція книг автора
        ]);
    }

    // 2. Eager loading (завантаження одразу зі зв'язками для уникнення N+1)
    public function eagerLoad() {
        // Завантажуємо книги разом з їх авторами та жанрами
        $books = Book::with(['author', 'genre'])->take(5)->get();
        return response()->json($books);
    }

    // 3. Фільтрація через зв'язки
    public function filterRelations() {
        // Шукаємо авторів, які мають хоча б одну книгу, видану після 2015 року
        $authors = Author::whereHas('books', function($query) {
            $query->where('published_year', '>', 2015);
        })->get();
        
        return response()->json($authors);
    }

    // 4. Агрегати по зв'язках
    public function aggregates() {
        // Рахуємо кількість книг у кожного жанру
        $genres = Genre::withCount('books')->get();
        return response()->json($genres);
    }

    // 5. Створення пов'язаних записів
    public function createRelated() {
        $genre = Genre::first();
        
        // Створюємо нову книгу і вона автоматично прив'язується до цього жанру
        $newBook = $genre->books()->create([
            'author_id' => Author::first()->id,
            'title' => 'Нова стильна книга',
            'published_year' => 2026
        ]);
        
        return response()->json(['message' => 'Успішно створено!', 'book' => $newBook]);
    }
}