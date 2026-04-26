<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueryBuilderController extends Controller
{
    // 1. Отримати всі записи
    public function all() {
        return response()->json(DB::table('genres')->get());
    }

    // 2. Записи з фільтрами
    public function filter() {
        return response()->json(
            DB::table('books')
                ->where('published_year', '>', 2000)
                ->whereNotNull('title')
                ->get()
        );
    }

    // 3. Вибір конкретних колонок з аліасами
    public function selectedColumns() {
        return response()->json(
            DB::table('authors')
                ->select('first_name', 'last_name as surname', 'email')
                ->get()
        );
    }

    // 4. Пагінація
    public function paginated() {
        return response()->json(DB::table('books')->paginate(10));
    }

    // 5. Агрегатні функції
    public function aggregates() {
        return response()->json([
            'count' => DB::table('contracts')->count(),
            'sum' => DB::table('contracts')->sum('amount'),
            'avg' => DB::table('contracts')->avg('amount'),
            'max' => DB::table('contracts')->max('amount')
        ]);
    }

    // 6. З'єднання трьох таблиць (INNER JOIN)
    public function joinInner() {
        return response()->json(
            DB::table('contracts')
                ->join('books', 'contracts.book_id', '=', 'books.id')
                ->join('editors', 'contracts.editor_id', '=', 'editors.id')
                ->select('books.title as book_title', 'editors.name as editor_name', 'contracts.amount')
                ->get()
        );
    }

    // 7. LEFT JOIN (всі автори, навіть без книг)
    public function joinLeft() {
        return response()->json(
            DB::table('authors')
                ->leftJoin('books', 'authors.id', '=', 'books.author_id')
                ->select('authors.last_name', 'books.title')
                ->get()
        );
    }

    // 8. RIGHT JOIN (всі жанри та кількість книг у них)
    public function joinRight() {
        return response()->json(
            DB::table('books')
                ->rightJoin('genres', 'books.genre_id', '=', 'genres.id')
                ->select('genres.name as genre_name', DB::raw('COUNT(books.id) as books_count'))
                ->groupBy('genres.id', 'genres.name')
                ->get()
        );
    }

    // 9. INSERT, UPDATE, DELETE
    public function insertUpdateDelete() {
        // Додаємо
        DB::table('genres')->insert(['name' => 'Тимчасовий жанр', 'created_at' => now(), 'updated_at' => now()]);
        // Оновлюємо
        DB::table('genres')->where('name', 'Тимчасовий жанр')->update(['description' => 'Тестовий опис']);
        // Видаляємо
        $deleted = DB::table('genres')->where('name', 'Тимчасовий жанр')->delete();

        return response()->json(['message' => 'Всі 3 операції виконано успішно', 'deleted_status' => $deleted]);
    }
}