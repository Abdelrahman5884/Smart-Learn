<?php

namespace App\Http\Controllers\Instructor;

use App\Models\Course;
use App\Models\CourseLectures;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Instructor\StoreLectureRequest;
use App\Http\Requests\Instructor\UploadLectureVideoRequest;
use App\Http\Responses\Lecture\LectureResponse;

class LectureController extends Controller
{
    public function store(StoreLectureRequest $request, $courseId)
    {
        $course = Course::find($courseId);

        if (!$course || $course->instructor_id !== $request->user()->id) {
            return LectureResponse::error('Unauthorized.', 403);
        }

        $lastOrder = $course->lectures()->max('order');
        $newOrder = $lastOrder ? $lastOrder + 1 : 1;

        $data = $request->validated();

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request
                ->file('attachment')
                ->store('lectures','public');
        }

        $data['order'] = $newOrder;

        $lecture = $course->lectures()->create($data);

        return LectureResponse::success(
            $lecture,
            'Lecture created successfully.',
            201
        );
    }
    public function uploadVideo(UploadLectureVideoRequest $request, $lectureId)
{
    $lecture = CourseLectures::find($lectureId);

    if (!$lecture || $lecture->course->instructor_id !== $request->user()->id) {
        return LectureResponse::error('Unauthorized.', 403);
    }

    $data = [];

    if ($request->hasFile('video_file')) {

        if ($lecture->video_path) {
            Storage::disk('public')->delete($lecture->video_path);
        }

        $data['video_path'] = $request
            ->file('video_file')
            ->store('lecture_videos','public');
    }

    if ($request->video_url) {
        $data['video_url'] = $request->video_url;
    }

    if ($request->video_duration) {
        $data['video_duration'] = $request->video_duration;
    }

    $lecture->update($data);

    return LectureResponse::success(
        $lecture,
        'Video uploaded successfully.'
    );
}
}