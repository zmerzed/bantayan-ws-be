<?php

namespace App\Http\Requests;

use App\Enums\AccountType;
use App\Enums\ApplicationType;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class ReadingUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'meter_reading' => ['required'],
            'comment' => ['nullable']
        ];
    }

    public function attributes()
    {
        return [
        ];
    }
}
