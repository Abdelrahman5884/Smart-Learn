<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use App\Models\QuizOption;

class StudentQuizController extends Controller
{

    public function show($quizId)
    {
        $quiz = Quiz::with(['questions.options'])->find($quizId);

        if (!$quiz) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz not found'
            ],404);
        }

        return response()->json([
            'success' => true,
            'quiz_id' => $quiz->id,
            'title' => $quiz->title,
            'duration_minutes' => $quiz->duration_minutes,
            'duration_seconds' => $quiz->duration_minutes * 60,
            'questions' => $quiz->questions->map(function ($q) {
                return [
                    'id' => $q->id,
                    'question' => $q->question_text,
                    'options' => $q->options->map(function ($o) {
                        return [
                            'id' => $o->id,
                            'text' => $o->option_text
                        ];
                    })
                ];
            })
        ]);
    }

    public function start($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);

        $existingAttempt = QuizAttempt::where('quiz_id',$quizId)
            ->where('user_id',auth()->id())
            ->first();

        if ($existingAttempt) {
            return response()->json([
                'success' => false,
                'message' => 'You have already taken this quiz'
            ],403);
        }

        $attempt = QuizAttempt::create([
            'quiz_id' => $quizId,
            'user_id' => auth()->id(),
            'started_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'attempt_id' => $attempt->id
        ]);
    }

    public function submit(Request $request,$attemptId)
    {

        $request->validate([
            'answers' => 'required|array'
        ]);

        $attempt = QuizAttempt::find($attemptId);

        if (!$attempt) {
            return response()->json([
                'success' => false,
                'message' => 'Attempt not found'
            ],404);
        }

        if($attempt->finished_at){
            return response()->json([
                'success'=>false,
                'message'=>'Quiz already submitted'
            ],403);
        }

        $quiz = $attempt->quiz;

        $endTime = $attempt->started_at->addMinutes($quiz->duration_minutes);

        if(now()->greaterThan($endTime)){
            return response()->json([
                'success' => false,
                'message' => 'Quiz time is over'
            ],403);
        }

        $score = 0;

        foreach ($request->answers as $answer) {

            $option = QuizOption::where('id',$answer['option_id'])
                ->where('question_id',$answer['question_id'])
                ->first();

            if (!$option) {
                continue;
            }

            $isCorrect = $option->is_correct;

            if ($isCorrect) {
                $score++;
            }

            QuizAnswer::create([
                'attempt_id' => $attempt->id,
                'question_id' => $answer['question_id'],
                'selected_option_id' => $answer['option_id'],
                'is_correct' => $isCorrect
            ]);
        }

        $attempt->update([
            'score' => $score,
            'finished_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'score' => $score
        ]);
    }
}