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
        'order'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

}
