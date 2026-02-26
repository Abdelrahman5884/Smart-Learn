<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LectureFile extends Model
{

    protected $fillable = [
        'lecture_id',
        'file_name',
        'file_path',
        'file_type'
    ];

    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }
}
