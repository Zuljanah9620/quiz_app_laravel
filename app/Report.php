<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{

    protected $table = 'reports';
    protected $guarded = [];

    public function question() {
        return $this->belongsTo(Question::class);
    }
}
