<?php

namespace Kolette\Auth\Http\Requests;

use Kolette\Auth\Rules\ValidPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class GenerateOTPRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phone_number' => ['required', new ValidPhoneNumber],
        ];
    }
}
