<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory; // Вмикає фабрики

    protected $guarded = []; // Дозволяє запис

    public function genre() {
        return $this->belongsTo(Genre::class);
    }
    public function author() {
        return $this->belongsTo(Author::class);
    }
    public function contract() {
        return $this->hasOne(Contract::class); // Один-до-одного
    }
}

