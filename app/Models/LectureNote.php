<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LectureNote extends Model
{
    protected $fillable = [
        'lecture_id',
        'user_id',
        'content',
        'timestamp_seconds'
    ];

 public function lectures()
{
    return $this->hasMany(CourseLectures::class);
}

    public function student()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}