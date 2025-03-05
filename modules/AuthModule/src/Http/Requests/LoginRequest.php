<?php

namespace Kolette\Auth\Http\Requests;

use Kolette\Auth\Rules\ValidEmailOrPhoneNumber;
use Kolette\Auth\Support\ValidatesEmail;
use Kolette\Auth\Support\ValidatesPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
{
    use ValidatesPhone;
    use ValidatesEmail;

    public function rules(): array
    {
        return [
            'role' => [
                'required',
                'bail',
                Rule::exists('roles', 'name')
            ],
            'username' => ['required', new ValidEmailOrPhoneNumber],
            'password' => [
                'bail',
                Rule::requiredIf($this->isEmail($this->get('username'))),
            ],
            'otp' => [
                'bail',
                Rule::requiredIf(
                    $this->isPhone($this->get('username')) &&
                    !config('Kolette.auth.use_bypass_code')
                ),
            ],
        ];
    }
}
