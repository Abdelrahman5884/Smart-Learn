<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseCertificate extends Model
{
    protected $fillable = [
        'course_id',
        'template',
        'enabled',
        'auto_send',
        'has_qr',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'auto_send' => 'boolean',
        'has_qr' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
