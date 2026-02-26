<?php

namespace App\Http\Requests\Course;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->role === 'instructor';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required','string','min:3','max:255'],
            'code' => ['required','string','min:3','max:50','unique:courses,code'],
            'description' => ['nullable','string','min:10'],
            'level' => ['nullable','string','max:100'],
            'status' => ['required', Rule::in(['active','archived','draft'])],
        ];
    }
    public function messages(): array
    {
        return [
            'title.required' => 'Course title is required.',
            'code.required' => 'Course code is required.',
            'code.unique' => 'This course code already exists.',
            'status.in' => 'Status must be active, archived or draft.',
        ];
    }
}
