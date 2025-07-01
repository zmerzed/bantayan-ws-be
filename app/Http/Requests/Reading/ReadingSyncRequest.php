<?php

namespace App\Http\Requests\Reading;

use App\Enums\AccountType;
use App\Enums\ApplicationType;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class ReadingSyncRequest extends FormRequest
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
            'meter_reading_date' => ['required'],
            'readed_by' => ['required'],
        ];
    }

    public function attributes()
    {
        return [];
    }
}
