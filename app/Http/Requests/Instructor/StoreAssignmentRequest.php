<?php

namespace App\Http\Requests\Instructor;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssignmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['required', 'string'],
            'max_grade' => ['required', 'integer', 'min:1', 'max:1000'],
            'due_date' => ['required', 'date', 'after:today'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,doc,docx,zip', 'max:25600'],
            'status' => ['nullable', 'in:draft,published'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Assignment title is required.',
            'description.required' => 'Assignment description is required.',
            'due_date.after' => 'Due date must be in the future.',
            'attachment.mimes' => 'File must be PDF, DOC, DOCX or ZIP.',
            'attachment.max' => 'File size must not exceed 25MB.',
        ];
    }
}
