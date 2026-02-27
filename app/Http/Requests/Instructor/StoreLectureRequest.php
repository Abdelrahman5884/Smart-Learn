<?php

namespace App\Http\Requests\Instructor;

use Illuminate\Foundation\Http\FormRequest;

class StoreLectureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // authorization هنعملها في controller
    }

    public function rules(): array
    {
        return [
            'title' => ['required','string','min:3','max:255'],
            'description' => ['nullable','string','max:1000'],
            'content' => ['nullable','string'],
            'attachment' => ['nullable','file','mimes:pdf,ppt,pptx','max:51200']
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Lecture title is required.',
            'title.min' => 'Lecture title must be at least 3 characters.',
            'attachment.mimes' => 'Attachment must be PDF or PowerPoint file.',
            'attachment.max' => 'Attachment size must not exceed 50MB.'
        ];
    }
}