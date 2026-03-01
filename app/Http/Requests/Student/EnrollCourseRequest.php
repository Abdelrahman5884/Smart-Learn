<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class EnrollCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
      $user = $this->user();
        return $user && $user->role === 'student';
    }

    public function rules(): array
    {
        return [
            'course_id' => ['required','integer','exists:courses,id']
        ];
    }

    public function messages(): array
    {
        return [
            'course_id.required' => 'Course id is required.',
            'course_id.exists' => 'Selected course does not exist.'
        ];
    }
}