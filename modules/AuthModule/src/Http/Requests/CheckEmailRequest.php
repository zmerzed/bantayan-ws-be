<?php

namespace Kolette\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckEmailRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'role' => [
                'required',
                'bail',
                Rule::exists('roles', 'name')
            ],
            'email' => ['required', 'email'],
        ];
    }
}
