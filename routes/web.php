<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\QueryBuilderController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/raw-demo', function () {
    // 1. Створення допоміжної таблиці та запис у неї (через statement та unprepared)
    DB::statement('CREATE TABLE IF NOT EXISTS log_entries (id INT AUTO_INCREMENT PRIMARY KEY, message VARCHAR(255), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)');
    DB::unprepared("INSERT INTO log_entries (message) VALUES ('Запуск raw-demo роуту')");

    // 2. SELECT з параметрами (шукаємо книги, видані після 2010 року)
    $recentBooks = DB::select('SELECT * FROM books WHERE published_year > ? ORDER BY published_year DESC LIMIT 3', [2010]);

    // 3. INSERT, UPDATE, DELETE
    DB::insert('INSERT INTO genres (name, description, created_at, updated_at) VALUES (?, ?, NOW(), NOW())', ['Комікси', 'Графічні романи']);
    
    $affected = DB::update('UPDATE genres SET description = ? WHERE name = ?', ['Оновлений опис для коміксів', 'Комікси']);
    
    $deleted = DB::delete('DELETE FROM genres WHERE name = ?', ['Комікси']);

    // 4. Транзакція (створюємо автора і одразу книгу для нього)
    DB::transaction(function () {
        DB::insert('INSERT INTO authors (first_name, last_name, created_at, updated_at) VALUES (?, ?, NOW(), NOW())', ['Тарас', 'Шевченко']);
        
        // Отримуємо ID щойно створеного автора
        $author = DB::select('SELECT id FROM authors WHERE first_name = ? AND last_name = ? LIMIT 1', ['Тарас', 'Шевченко']);
        $authorId = $author[0]->id;
        
        // Беремо будь-який існуючий жанр
        $genre = DB::select('SELECT id FROM genres LIMIT 1');
        $genreId = $genre[0]->id;

        DB::insert('INSERT INTO books (genre_id, author_id, title, published_year, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())', [$genreId, $authorId, 'Кобзар', 1840]);
    });

    // Повертаємо результат у форматі JSON
    return response()->json([
        'message' => 'Всі raw-запити виконано успішно!',
        'recent_books' => $recentBooks,
        'updated_rows_count' => $affected,
        'deleted_rows_count' => $deleted
    ]);

Route::prefix('qb')->group(function () {
    Route::get('/all', [QueryBuilderController::class, 'all']);
    Route::get('/filter', [QueryBuilderController::class, 'filter']);
    Route::get('/selected', [QueryBuilderController::class, 'selectedColumns']);
    Route::get('/paginated', [QueryBuilderController::class, 'paginated']);
    Route::get('/aggregates', [QueryBuilderController::class, 'aggregates']);
    Route::get('/join-inner', [QueryBuilderController::class, 'joinInner']);
    Route::get('/join-left', [QueryBuilderController::class, 'joinLeft']);
    Route::get('/join-right', [QueryBuilderController::class, 'joinRight']);
    Route::get('/iud', [QueryBuilderController::class, 'insertUpdateDelete']);
});
Route::prefix('eq')->group(function () {
    Route::get('/index', [EloquentController::class, 'index']);
    Route::get('/show/{id}', [EloquentController::class, 'show']);
    Route::get('/store', [EloquentController::class, 'store']);
    Route::get('/update/{id}', [EloquentController::class, 'update']);
    Route::get('/destroy/{id}', [EloquentController::class, 'destroy']);
    Route::get('/stats', [EloquentController::class, 'stats']);
    Route::get('/big-data', [EloquentController::class, 'bigData']);
});
Route::prefix('rel')->group(function () {
    Route::get('/get', [RelationshipController::class, 'getRelated']);
    Route::get('/eager', [RelationshipController::class, 'eagerLoad']);
    Route::get('/filter', [RelationshipController::class, 'filterRelations']);
    Route::get('/aggregates', [RelationshipController::class, 'aggregates']);
    Route::get('/create', [RelationshipController::class, 'createRelated']);
});
});