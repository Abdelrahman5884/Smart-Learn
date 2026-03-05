<?php

namespace App\Http\Controllers\Student;

use App\Models\CourseLectures;
use App\Models\LectureNote;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Student\LectureResource;
use App\Http\Requests\Student\StoreLectureNoteRequest;

class StudentLectureController extends Controller
{
    public function show($courseId,$lectureId): JsonResponse {
        $student = auth()->user();
        $lecture = CourseLectures::with('course.instructor')
        ->where('course_id', $courseId)
        ->findOrFail($lectureId);

        $pivot = $student->completedLectures()->where('lecture_id',$lecture->id)
        ->first();

        $lecture->is_completed =
            $pivot?->pivot?->is_completed ?? false;

        $lecture->completed_at =
            $pivot?->pivot?->completed_at;

        $lecture->notes = LectureNote::where([

            'lecture_id' => $lecture->id,
            'user_id' => $student->id
        ])->get();

        return response()->json(['success' => true,'data' => new LectureResource($lecture)],200);

        
    }


    public function storeNote(StoreLectureNoteRequest $request,$lectureId): JsonResponse {

        $note = LectureNote::create([
            'lecture_id' => $lectureId,
            'user_id' => auth()->id(),
            'content' => $request->input('content'),
            'timestamp_seconds' =>
                $request->timestamp_seconds
        ]);

        return response()->json([
            'success' => true,
            'message' =>'Note added successfully',
            'data' => $note

        ]);
    }

}