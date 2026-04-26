<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Editor extends Model
{
    use HasFactory; // Вмикає фабрики

    protected $guarded = []; // Дозволяє запис
    public function contracts() {
        return $this->hasMany(Contract::class); // Один-до-багатьох
    }
}
