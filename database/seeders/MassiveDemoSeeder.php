<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Models\CourseLectures;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use App\Models\Conversation;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Hash;

class MassiveDemoSeeder extends Seeder
{
    public function run()
    {

        // Instructor
    $instructor = User::firstOrCreate(
    ['email' => 'instructor@test.com'],
    [
        'name' => 'Main Instructor',
        'password' => Hash::make('12345678'),
        'role' => 'instructor',
        'phone' => '01000000000'
    ]
);

        // Students
        $students = User::factory()->count(50)->create([
            'role' => 'student'
        ]);

        // Courses
        for ($c = 1; $c <= 5; $c++) {

            $course = Course::create([
                'title' => "Course $c",
                'code' => "CRS$c",
                'description' => "Description for course $c",
                'level' => 'Beginner',
                'status' => 'active',
                'instructor_id' => $instructor->id
            ]);

            // Enroll students
            foreach ($students as $student) {
           $course->students()->attach($student->id, [
    'status' => 'approved',
    'enrolled_at' => now()
]);
            }

            // Lectures
            for ($l = 1; $l <= 10; $l++) {

                $lecture = CourseLectures::create([
                    'course_id' => $course->id,
                    'title' => "Lecture $l",
                    'description' => "Lecture description",
                    'content' => "Lecture content",
                    'video_url' => "https://youtube.com/video$l",
                    'video_duration' => rand(10,40),
                    'order' => $l
                ]);

      $assignment = Assignment::create([
    'lecture_id' => $lecture->id,
    'title' => "Assignment $l",
    'description' => "Solve assignment",
    'max_grade' => 100,
    'due_date' => now()->addDays(7),
    'status' => 'published'
]);

                // Assignment Submissions
                foreach ($students as $student) {
                    AssignmentSubmission::create([
                        'assignment_id' => $assignment->id,
                        'user_id' => $student->id,
                        'file' => 'submission.pdf',
                        'grade' => rand(50,100),
                        'feedback' => 'Good job'
                    ]);
                }
            }

            // Quiz
   $quiz = Quiz::create([
    'course_id' => $course->id,
    'title' => "Quiz for Course $c",
    'duration_minutes' => 30,
    'attempts' => 2,
    'show_results' => true,
    'randomize_questions' => false,
    'status' => 'published'
]);

            // Questions
            for ($q = 1; $q <= 10; $q++) {

                $question = QuizQuestion::create([
                    'quiz_id' => $quiz->id,
                    'type' => 'mcq',
                    'question_text' => "Question $q ?",
                    'grade' => 5,
                    'order' => $q
                ]);

                // Options
                for ($o = 1; $o <= 4; $o++) {

                    QuizOption::create([
                        'question_id' => $question->id,
                        'option_text' => "Option $o",
                        'is_correct' => $o == 1
                    ]);
                }
            }

            // Quiz Attempts
            foreach ($students as $student) {

                $attempt = QuizAttempt::create([
                    'quiz_id' => $quiz->id,
                    'user_id' => $student->id,
                    'started_at' => now(),
                    'finished_at' => now()->addMinutes(20),
                    'score' => rand(40,100)
                ]);

                // Answers
                $questions = QuizQuestion::where('quiz_id',$quiz->id)->get();

                foreach ($questions as $question) {

                    $option = QuizOption::where('question_id',$question->id)->inRandomOrder()->first();

                    QuizAnswer::create([
                        'attempt_id' => $attempt->id,
                        'question_id' => $question->id,
                        'selected_option_id' => $option->id,
                        'is_correct' => $option->is_correct
                    ]);
                }
            }
        }

        // Conversations
        foreach ($students as $student) {

            $conversation = Conversation::create([
                'user_id' => $student->id,
                'title' => 'Study Assistant'
            ]);

            for ($m = 1; $m <= 5; $m++) {

                ChatMessage::create([
                    'conversation_id' => $conversation->id,
                    'user_id' => $student->id,
                    'message' => "Student question $m",
                    'response' => "AI response for question $m"
                ]);
            }
        }

    }
}