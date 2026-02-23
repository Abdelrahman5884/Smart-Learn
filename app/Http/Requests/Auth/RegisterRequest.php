<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'max:50'],
            'role' => ['required', 'in:student,instructor'],
            'university_id' => ['required', 'string', 'min:6', 'max:12','unique:users,university_id'],
        ];
    }

    public function messages(): array
    {
        return [

            // Name
            'name.required' => 'Full name is required.',
            'name.string' => 'Full name must be text.',
            'name.min' => 'Full name must be at least 3 characters.',
            'name.max' => 'Full name must not exceed 100 characters.',

            // Email
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email must not exceed 255 characters.',
            'email.unique' => 'This email is already registered.',

            // Password
            'password.required' => 'Password is required.',
            'password.string' => 'Password must be text.',
            'password.min' => 'Password must be at least 6 characters.',
            'password.max' => 'Password must not exceed 50 characters.',

            // Role
            'role.required' => 'User role is required.',
            'role.in' => 'Role must be either student or instructor.',

            // University ID
            'university_id.required' => 'University ID is required.',
            'university_id.string' => 'University ID must be text.',
            'university_id.min' => 'University ID must be at least 6 characters.',
            'university_id.max' => 'University ID must not exceed 12 characters.',
        ];
    }
}