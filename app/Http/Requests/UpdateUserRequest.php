<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
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
           'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . optional($this->route('user'))->id,
            'password' => [
                'sometimes',
                'string',
                'min:8',
                'confirmed',
                new StrongPassword,
            ],
            'phone' => 'required|regex:/^\(\d{2}\) \d{5}-\d{4}$/',
        ];
    }
}
