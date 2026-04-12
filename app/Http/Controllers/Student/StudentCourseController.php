<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentCourseController extends Controller
{
    public function index(): JsonResponse
    {
        $courses = Course::where('status', 'active')
            ->with('instructor')
            ->withCount('lectures')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $courses,
        ]);
    }

    public function enroll(Request $request, Course $course): JsonResponse
    {
        $user = $request->user();

        if ($course->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Course is not available.',
            ], 400);
        }

        if ($user->role !== 'student') {
            return response()->json([
                'success' => false,
                'message' => 'Only students can enroll.',
            ], 403);
        }

        if ($user->enrolledCourses()
            ->where('course_id', $course->id)
            ->exists()) {

            return response()->json([
                'success' => false,
                'message' => 'You already enrolled or requested this course.',
            ], 400);
        }

        $user->enrolledCourses()->attach($course->id, [
            'status' => 'approved',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم الانضمام للمقرر بنجاح!',
        ]);
    }

    public function myCourses(Request $request): JsonResponse
    {
        $user = $request->user();

        $courses = $user->enrolledCourses()
            ->wherePivot('status', 'approved')
            ->with('instructor')
            ->withCount('lectures')
            ->get();

        // Add progress for each course
        $courses->transform(function ($course) use ($user) {
            $totalLectures = $course->lectures_count;
            $lectureIds = $course->lectures()->pluck('id');
            $completedLectures = $user->completedLectures()
                ->whereIn('course_lectures.id', $lectureIds)
                ->wherePivot('is_completed', true)
                ->count();

            $course->progress = $totalLectures > 0
                ? round(($completedLectures / $totalLectures) * 100)
                : 0;
            $course->completed_lectures = $completedLectures;
            $course->instructor_name = $course->instructor->name ?? null;

            return $course;
        });

        return response()->json([
            'success' => true,
            'data' => $courses,
        ]);
    }

    public function show(Request $request, Course $course): JsonResponse
    {
        $user = $request->user();

        $enrollment = $user->enrolledCourses()
            ->where('course_id', $course->id)
            ->first();

        if (! $enrollment || $enrollment->pivot->status !== 'approved') {
            $status = $enrollment ? $enrollment->pivot->status : null;
            return response()->json([
                'success' => false,
                'message' => $status === 'pending'
                    ? 'طلب انضمامك قيد المراجعة من المحاضر.'
                    : 'You are not allowed to access this course.',
                'enrollment_status' => $status,
            ], 403);
        }

        $course->load(['lectures' => function ($q) {
            $q->orderBy('order');
        }, 'quizzes.questions', 'instructor']);

        // Calculate progress
        $totalLectures = $course->lectures->count();
        $lectureIds = $course->lectures->pluck('id');
        $completedLectures = $user->completedLectures()
            ->whereIn('course_lectures.id', $lectureIds)
            ->wherePivot('is_completed', true)
            ->count();

        $progress = $totalLectures > 0
            ? round(($completedLectures / $totalLectures) * 100)
            : 0;

        $courseData = $course->toArray();
        $courseData['progress'] = $progress;
        $courseData['completed_lectures'] = $completedLectures;
        $courseData['total_lectures'] = $totalLectures;
        $courseData['instructor_name'] = $course->instructor->name ?? null;

        return response()->json([
            'success' => true,
            'data' => $courseData,
        ]);
    }
}
