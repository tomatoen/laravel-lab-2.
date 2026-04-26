<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory; // Вмикає фабрики

    protected $guarded = []; // Дозволяє запис
    public function books() {
        return $this->hasMany(Book::class); // Один-до-багатьох
    }
}
