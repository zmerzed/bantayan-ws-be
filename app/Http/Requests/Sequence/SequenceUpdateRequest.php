<?php

namespace App\Http\Requests\Sequence;

use App\Enums\AccountType;
use Kolette\Auth\Enums\Role;
use App\Enums\ApplicationType;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SequenceUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reader' => ['required', 'exists:admins,id']
        ];
    }

    public function attributes()
    {
        return [
        ];
    }
}
