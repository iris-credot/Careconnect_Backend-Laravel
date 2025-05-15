<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
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
