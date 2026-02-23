<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'section_id',
        'title',
        'description',
        'video_path',
        'duration',
        'order'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function files()
    {
        return $this->hasMany(LectureFile::class);
    }
}
