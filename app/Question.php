<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'question';
    protected $guarded = [];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function report() {
        return $this->hasMany(Report::class);
    }
}
