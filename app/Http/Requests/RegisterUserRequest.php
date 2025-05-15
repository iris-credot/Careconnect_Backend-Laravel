<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow all for now, or implement access logic
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:6',
            'username' => 'required|string',
        ];
    }

    public function prepareForValidation()
    {
        // Force 'role' to 'patient' and ignore incoming 'role'
        $this->merge([
            'role' => 'patient',
        ]);
    }

    public function messages()
    {
        return [
            'email.required' => 'Email is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'username.required' => 'Username is required.',
        ];
    }
}
