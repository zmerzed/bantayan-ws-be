<?php

namespace Kolette\Auth\Http\Requests;

use Kolette\Auth\Rules\UsernameExist;
use Kolette\Auth\Rules\ValidEmailOrPhoneNumber;
use Kolette\Auth\Rules\ValidResetPasswordToken;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => ['required', new ValidEmailOrPhoneNumber, new UsernameExist],
            'token' => ['required', 'bail', new ValidResetPasswordToken($this->username)],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.exists' => trans('auth::passwords.user_password_reset'),
            'password' => trans('auth::passwords.password'),
        ];
    }
}
