<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{

    protected $fillable = [
        'course_id',
        'title',
        'total_grade',
        'duration_minutes',
        'attempts_allowed',
        'start_date',
        'end_date',
        'show_result_immediately',
        'shuffle_questions'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
}