<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TokenGenerateApiRequest extends FormRequest
{
    // Authorization: control who can make this request
    public function authorize(): bool
    {
        // Example: only logged-in users can create
        // return auth()->check();
        return true;
    }

    // Validation rules
    public function rules(): array
    {
        return [
            // 'client_id'     => ['required', 'string'],
            // 'client_secret' => ['required', 'string'],
            'email'         => ['required', 'email'],
            'password'      => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            // 'client_id.required'        => 'Client ID is required.',
            // 'client_id.string'          => 'Invalid Client ID.',
            // 'client_secret.required'    => 'Client Secret is required.',
            // 'client_secret.string'      => 'Invalid Client Secret.',
            'email.required'            => 'Email is required.',
            'email.email'               => 'Invalid Email.',
            'password.required'         => 'Password is required.',
        ];
    }
}
