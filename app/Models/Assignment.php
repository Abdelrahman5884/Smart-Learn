<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'lecture_id',
        'title',
        'description',
        'max_grade',
        'due_date',
        'attachment',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function lecture()
    {
        return $this->belongsTo(CourseLectures::class);
    }
}
