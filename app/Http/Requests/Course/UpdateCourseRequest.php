<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
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
            'title' => ['sometimes','string','min:3','max:255'],
            'code' => [
                'sometimes',
                'string',
                'min:3',
                'max:50',
                Rule::unique('courses','code')->ignore($this->course)
            ],
            'description' => ['sometimes','nullable','string','min:10'],
            'level' => ['sometimes','nullable','string','max:100'],
            'status' => ['sometimes', Rule::in(['active','archived','draft'])],
        ];
    }
}
