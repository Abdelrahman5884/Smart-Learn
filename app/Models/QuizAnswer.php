<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model
{
      protected $fillable = [
        'attempt_id',
        'question_id',
        'selected_option_id',
        'is_correct'
    ];
}
