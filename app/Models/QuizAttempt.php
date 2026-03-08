<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{

    protected $fillable = [
        'quiz_id',
        'user_id',
        'started_at',
        'finished_at',
        'score'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];
    public function quiz()
{
    return $this->belongsTo(Quiz::class);
}
}