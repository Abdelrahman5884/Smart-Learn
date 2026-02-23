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
        return $this->belongsToMany(Course::class, 'course_student', 'student_id', 'course_id');
    }

    public function assignmentSubmissions()
    {
        return $this->hasMany(AssignmentSubmission::class, 'student_id');
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class, 'student_id');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'student_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Chat
    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }
}
