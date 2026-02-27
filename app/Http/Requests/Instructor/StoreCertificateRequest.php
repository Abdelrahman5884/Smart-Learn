<?php

namespace App\Http\Requests\Instructor;

use Illuminate\Foundation\Http\FormRequest;

class StoreCertificateRequest extends FormRequest
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
            'template' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120'
            ],
            'enabled' => ['required','boolean'],
            'auto_send' => ['required','boolean'],
            'has_qr' => ['required','boolean']
        ];
    }
    public function messages(): array
    {
        return [
            'template.required' => 'Certificate template is required.',
            'template.mimes' => 'Template must be PDF, JPG or PNG.',
            'template.max' => 'File must not exceed 5MB.'
        ];
    }
}
