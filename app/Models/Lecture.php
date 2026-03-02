<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    protected $table = 'course_lectures';
    protected $fillable = [
        'course_id',
        'section_id',
        'title',
        'description',
        'video_path',
        'duration',
        'order',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class)
        ->withPivot(['is_completed','completed_at'])->withTimestamps();
    }

    public function files()
    {
        return $this->hasMany(LectureFile::class);
    }
}
