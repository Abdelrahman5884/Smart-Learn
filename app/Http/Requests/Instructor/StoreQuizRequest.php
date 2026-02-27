<?php

namespace App\Http\Requests\Instructor;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'attempts' => ['required', 'integer', 'min:1'],
            'show_results' => ['required', 'boolean'],
            'randomize_questions' => ['required', 'boolean'],
        ];
    }
}
