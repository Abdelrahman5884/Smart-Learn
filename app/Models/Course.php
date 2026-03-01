<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title',
        'code',
        'description',
        'level',
        'status',
        'instructor_id',
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function students()
    {
    return $this->belongsToMany(User::class)->withPivot(['status','enrolled_at'])->withTimestamps();
    }

    public function lectures()
    {
        return $this->hasMany(CourseLectures::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
}
