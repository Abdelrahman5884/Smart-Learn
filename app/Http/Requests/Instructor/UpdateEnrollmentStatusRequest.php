<?php

namespace App\Http\Requests\Instructor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEnrollmentStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && $user->role === 'instructor';
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:approved,rejected'
        ];
    }
}