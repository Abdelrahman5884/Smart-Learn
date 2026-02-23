<?php

namespace App\Http\Requests\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;


class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
{
    return [
        'name' => ['sometimes','string','min:3','max:100'],

        'email' => [
            'sometimes',
            'email',
            Rule::unique('users','email')->ignore($this->user()->id),
        ],

        'phone' => [
            'sometimes',
            'regex:/^01[0-9]{9}$/',
            Rule::unique('users','phone')->ignore($this->user()->id),
        ],

        'university_id' => [
            'sometimes',
            'string',
            'min:6',
            'max:20',
            Rule::unique('users','university_id')->ignore($this->user()->id),
        ],

        'profile_image' => [
            'sometimes',
            'image',
            'mimes:jpg,jpeg,png',
            'max:2048'
        ],
    ];
}
    public function messages(): array
    {
        return [

            'name.string' => 'Name must be text.',
            'name.min' => 'Name must be at least 3 characters.',
            'name.max' => 'Name must not exceed 100 characters.',

            'email.email' => 'Enter a valid email address.',
            'email.unique' => 'This email is already used.',

            'phone.regex' => 'Phone must be valid Egyptian number.',
            'phone.unique' => 'This phone number already exists.',

            'university_id.min' => 'University ID must be at least 6 characters.',
            'university_id.max' => 'University ID must not exceed 20 characters.',
            'university_id.unique' => 'This university ID already exists.',

            'profile_image.image' => 'Profile image must be an image.',
            'profile_image.mimes' => 'Image must be jpg, jpeg or png.',
            'profile_image.max' => 'Image must not exceed 2MB.',
        ];
    }
}