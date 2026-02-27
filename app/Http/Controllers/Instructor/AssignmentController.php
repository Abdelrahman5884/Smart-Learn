<?php

namespace App\Http\Controllers\Instructor;

use App\Models\Assignment;
use App\Models\CourseLectures;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Instructor\StoreAssignmentRequest;
use App\Http\Responses\Assignment\AssignmentResponse;

class AssignmentController extends Controller
{
    public function store(StoreAssignmentRequest $request, $lectureId)
    {
        $lecture = CourseLectures::find($lectureId);

        if (!$lecture || $lecture->course->instructor_id !== $request->user()->id) {
            return AssignmentResponse::error('Unauthorized.',403);
        }

        $data = $request->validated();

        if ($request->hasFile('attachment')) {

            $data['attachment'] = $request
                ->file('attachment')
                ->store('assignments','public');
        }

        $data['lecture_id'] = $lecture->id;

        $assignment = Assignment::create($data);

        return AssignmentResponse::success(
            $assignment,
            'Assignment created successfully.',
            201
        );
    }
}