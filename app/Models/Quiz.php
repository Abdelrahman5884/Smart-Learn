<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'duration_minutes',
        'attempts',
        'show_results',
        'randomize_questions',
        'status',
    ];

    protected $casts = [
        'show_results' => 'boolean',
        'randomize_questions' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }
}
