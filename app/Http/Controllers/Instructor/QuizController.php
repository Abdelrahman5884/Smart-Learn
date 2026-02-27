<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instructor\StoreQuizQuestionRequest;
use App\Http\Requests\Instructor\StoreQuizRequest;
use App\Models\Quiz;
use App\Models\QuizOption;
use App\Models\QuizQuestion;
use Illuminate\Http\JsonResponse;

class QuizController extends Controller
{
    public function store(StoreQuizRequest $request, $courseId): JsonResponse
    {
        $quiz = Quiz::create([
            'course_id' => $courseId,
            ...$request->validated(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Quiz created successfully.',
            'data' => $quiz,
        ], 201);
    }

    public function addQuestion(
        StoreQuizQuestionRequest $request,
        $quizId
    ): JsonResponse {

        $question = QuizQuestion::create([
            'quiz_id' => $quizId,
            'type' => $request->type,
            'question_text' => $request->question_text,
            'grade' => $request->grade,
            'order' => QuizQuestion::where('quiz_id', $quizId)->count() + 1,
        ]);

        if ($request->type !== 'short_answer') {

            foreach ($request->options as $option) {

                QuizOption::create([
                    'question_id' => $question->id,
                    'option_text' => $option['option_text'],
                    'is_correct' => $option['is_correct'],
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Question added successfully.',
            'data' => $question->load('options'),
        ], 201);
    }

    public function publish($quizId): JsonResponse
    {
        $quiz = Quiz::with('questions')->findOrFail($quizId);

        if ($quiz->questions->count() === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz must contain at least one question.',
            ], 400);
        }

        $quiz->update(['status' => 'published']);

        return response()->json([
            'success' => true,
            'message' => 'Quiz published successfully.',
            'data' => $quiz,
        ]);
    }
}
