<?php

namespace App\Http\Controllers\Course;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Http\Responses\Course\CourseResponse;

class CourseController extends Controller
{
    // Create 
    public function store(StoreCourseRequest $request)
    {
        $data = $request->validated();
        $data['instructor_id'] = $request->user()->id;

        $course = Course::create($data);

        return new CourseResponse(
            $course,
            'Course created successfully.',
            201
        );
    }

    // Update 
    public function update(UpdateCourseRequest $request, Course $course)
    {
        if ($course->instructor_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to edit this course.'
            ], 403);
        }

        $course->update($request->validated());

        return new CourseResponse(
            $course->fresh(),
            'Course updated successfully.'
        );
    }

    //  Delete 
    public function destroy(Request $request, Course $course)
    {
         if (!$course) {
             return response()->json([
               'success' => false,
               'message' => 'Course not found.'
           ], 404);
       
           }
        if ($course->instructor_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 403);
        }

        $course->delete();

        return response()->json([
            'success' => true,
            'message' => 'Course deleted successfully.'
        ]);
    }

    //  My Courses 
    public function myCourses(Request $request)
    {
        $courses = Course::where('instructor_id', $request->user()->id)->get();

        return new CourseResponse($courses);
    }

    //  Public Courses 
    public function index()
    {
        $courses = Course::where('status','active')->get();

        return new CourseResponse($courses);
    }

    //  Show Course 
    public function show(Course $course)
    {
        return new CourseResponse($course);
    }
}