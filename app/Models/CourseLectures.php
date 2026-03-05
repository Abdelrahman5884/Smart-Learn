<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseLectures extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'content',
        'attachment',
        'video_url',
        'video_path',
        'video_duration',
        'order',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function students()
    {
        return $this->belongsToMany(
            User::class,
            'lecture_user',
            'lecture_id',
            'user_id'
        )->withPivot([
            'is_completed',
            'completed_at'
        ])->withTimestamps();
    }

    public function notes()
    {
        return $this->hasMany(LectureNote::class,'lecture_id');
    }
}
