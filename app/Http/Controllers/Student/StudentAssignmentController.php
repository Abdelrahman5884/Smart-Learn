<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;

class StudentAssignmentController extends Controller
{

    public function show($assignmentId)
    {
        $assignment = Assignment::findOrFail($assignmentId);
        $submission = AssignmentSubmission::where('assignment_id',$assignmentId)
        ->where('user_id',auth()->id())
        ->first();
        return response()->json([
              'success'=>true,
            'message'=>'Assignment details',
            'id'=>$assignment->id,
            'title'=>$assignment->title,
            'description'=>$assignment->description,
            'max_grade'=>$assignment->max_grade,
            'due_date'=>$assignment->due_date,
            'file'=>$assignment->attachment,
            'submission'=>$submission
        ]);
    }
   public function submit(Request $request,$assignmentId)
{
    if(auth()->user()->role !== 'student'){
        return response()->json([
            'success'=>false,
            'message'=>'Only students can submit assignments'
        ],403);
    }
    $request->validate([
        'file'=>'required|file|mimes:pdf,zip,jpg,png|max:10240'
    ]);
    $assignment = Assignment::findOrFail($assignmentId);
    if(now()->greaterThan($assignment->due_date)){
        return response()->json([
            'success'=>false,
            'message'=>'Deadline passed'
        ],403);
    }
    $path = $request->file('file')->store('assignments','public');
    AssignmentSubmission::updateOrCreate(
        [
            'assignment_id'=>$assignmentId,
            'user_id'=>auth()->id()
        ],
        [
            'file'=>$path
        ]
    );
    return response()->json([
        'success'=>true,
        'message'=>'Assignment submitted successfully'
    ]);
    }
}