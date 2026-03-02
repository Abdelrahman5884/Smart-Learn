<?php

namespace App\Http\Controllers\Instructor;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Instructor\GetCourseStudentsRequest;
use App\Http\Requests\Instructor\UpdateEnrollmentStatusRequest;
use App\Http\Resources\Instructor\CourseStudentResource;

class InstructorStudentController extends Controller
{

    public function index(
        GetCourseStudentsRequest $request,
        Course $course
    ): JsonResponse {

        if ($course->instructor_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 403);
        }

        $totalLectures = $course->lectures()->count();
        $lectureIds = $course->lectures()->pluck('id');

        $students = $course->students()->get()
            ->map(function ($student) use ($lectureIds, $totalLectures) {

                if ($student->pivot->status === 'approved') {

                    $completedLectures = $student->completedLectures()
                        ->whereIn('course_lectures.id', $lectureIds)
                        ->wherePivot('is_completed', true)
                        ->count();

                    $progress = $totalLectures > 0
                        ? round(($completedLectures / $totalLectures) * 100)
                        : 0;

                    $student->completed_lectures = $completedLectures;
                    $student->total_lectures = $totalLectures;
                    $student->progress_percentage = $progress;
                }

                return $student;
            });

        return response()->json([
            'success' => true,
            'course_id' => $course->id,
            'course_title' => $course->title,
            'students_count' => $students->count(),
            'data' => CourseStudentResource::collection($students)
        ]);
    }

    public function updateEnrollmentStatus(
        UpdateEnrollmentStatusRequest $request,
        Course $course,
        User $student
    ): JsonResponse {

        if ($course->instructor_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 403);
        }

        $enrollment = $course->students()
            ->where('user_id', $student->id)
            ->first();

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => 'Student not enrolled in this course.'
            ], 404);
        }

        $course->students()->updateExistingPivot($student->id, [
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Enrollment status updated successfully.',
            'student_id' => $student->id,
            'status' => $request->status
        ]);
    }
}