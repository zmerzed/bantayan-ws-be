<?php

namespace Kolette\Auth\Http\Requests;

use Kolette\Auth\Enums\Role;
use Kolette\Auth\Rules\ValidEmailOrPhoneNumber;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckUsernameRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'role' => [
                'required',
                'bail',
                Rule::exists('roles', 'name')
            ],
            'username' => ['required', new ValidEmailOrPhoneNumber]
        ];
    }
}
