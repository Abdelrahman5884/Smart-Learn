<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'university_id',
        'phone',
        'profile_image',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }
    // Student Enrollments
    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class)->withPivot(['status', 'enrolled_at'])->withTimestamps();
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
       public function lectures()
   {
    return $this->hasMany(CourseLectures::class);
   }
    public function completedLectures()
    {
        return $this->belongsToMany(
            CourseLectures::class,
            'lecture_user',
            'user_id',
            'lecture_id'
        )->withPivot(
            'is_completed',
            'completed_at'
        )->withTimestamps();
    }

}
