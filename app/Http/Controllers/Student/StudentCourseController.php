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
        $courses = Course::where('status', 'active')->get();

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
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Enrollment request sent successfully.',
        ]);
    }

    public function myCourses(Request $request): JsonResponse
    {
        $user = $request->user();

        $courses = $user->enrolledCourses()
            ->wherePivot('status', 'approved')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $courses,
        ]);
    }

    public function show(Request $request, Course $course): JsonResponse
    {
        $user = $request->user();

        $isApproved = $user->enrolledCourses()
            ->where('course_id', $course->id)
            ->wherePivot('status', 'approved')
            ->exists();

        if (! $isApproved) {
            return response()->json([
                'success' => false,
                'message' => 'You are not allowed to access this course.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $course,
        ]);
    }
}
