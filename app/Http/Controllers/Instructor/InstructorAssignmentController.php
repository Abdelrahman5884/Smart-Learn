<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssignmentSubmission;

class InstructorAssignmentController extends Controller
{

    public function submissions($assignmentId)
    {

        $submissions = AssignmentSubmission::with('student')
        ->where('assignment_id',$assignmentId)
        ->get();
        return response()->json($submissions);
    }

    public function grade(Request $request,$submissionId)
    {

        $request->validate([
            'grade'=>'required|integer',
            'feedback'=>'nullable|string'
        ]);
        $submission = AssignmentSubmission::findOrFail($submissionId);
        $submission->update([
            'grade'=>$request->grade,
            'feedback'=>$request->feedback
        ]);

        return response()->json([
            'success'=>true,
            'message'=>'Assignment graded'
        ]);
    }

}