<?php

namespace Kolette\Auth\Http\Requests;

use Kolette\Auth\Rules\UsernameExist;
use Kolette\Auth\Rules\ValidEmailOrPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => ['required', 'bail', new ValidEmailOrPhoneNumber, 'bail', new UsernameExist],
        ];
    }
}
