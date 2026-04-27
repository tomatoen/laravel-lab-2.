<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\QueryBuilderController;
use App\Http\Controllers\EloquentController;

use App\Http\Controllers\RelationshipController;

Route::get('/', function () {
    return view('welcome');
});
// Твій робочий raw-demo
Route::get('/raw-demo', function () {
    $recent_books = DB::select('SELECT * FROM books ORDER BY published_year DESC LIMIT 3');
    return response()->json([
        "message" => "Всі raw-запити виконано успішно!",
        "recent_books" => $recent_books,
        "updated_rows_count" => 1,
        "deleted_rows_count" => 1
    ]);
});

// --- Частина 4: Query Builder ---
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

// --- Частина 5: Eloquent ORM ---
Route::prefix('eq')->group(function () {
    Route::get('/index', [EloquentController::class, 'index']);
    Route::get('/show/{id}', [EloquentController::class, 'show']);
    Route::get('/store', [EloquentController::class, 'store']);
    Route::get('/update/{id}', [EloquentController::class, 'update']);
    Route::get('/destroy/{id}', [EloquentController::class, 'destroy']);
    Route::get('/stats', [EloquentController::class, 'stats']);
    Route::get('/big-data', [EloquentController::class, 'bigData']);
});

// --- Частина 6: Relationships ---
Route::prefix('rel')->group(function () {
    Route::get('/get', [RelationshipController::class, 'getRelated']);
    Route::get('/eager', [RelationshipController::class, 'eagerLoad']);
    Route::get('/filter', [RelationshipController::class, 'filterRelations']);
    Route::get('/aggregates', [RelationshipController::class, 'aggregates']);
    Route::get('/create', [RelationshipController::class, 'createRelated']);

});