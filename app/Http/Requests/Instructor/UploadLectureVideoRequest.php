<?php

namespace App\Http\Requests\Instructor;

use Illuminate\Foundation\Http\FormRequest;

class UploadLectureVideoRequest extends FormRequest
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
            'video_url' => ['nullable', 'url'],
            'video_file' => ['nullable', 'file', 'mimes:mp4,mov,mkv', 'max:204800'],
            'video_duration' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'video_file.mimes' => 'Video must be mp4, mov or mkv.',
            'video_file.max' => 'Video must not exceed 200MB.',
        ];
    }
}
