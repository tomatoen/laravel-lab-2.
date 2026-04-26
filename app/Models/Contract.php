<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $guarded = [];
    /** @use HasFactory<\Database\Factories\ContractFactory> */
    use HasFactory;
    public function book() {
        return $this->belongsTo(Book::class);
    }
    public function editor() {
        return $this->belongsTo(Editor::class);
    }
}
