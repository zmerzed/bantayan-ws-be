<?php

namespace App\Http\Requests\Admin;

use App\Enums\AccountType;
use Kolette\Auth\Enums\Role;
use App\Enums\ApplicationType;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required'],
            'email' => ['nullable', 'email', Rule::unique('admins', 'email')->ignore($this->admin->id)],
            'role' => [
                'required',
                new EnumValue(Role::class, false), // Validate as Enum
                function ($attribute, $value, $fail) {
                    if (!in_array($value, [Role::ADMIN, Role::READER])) {
                        $fail("The selected role must be either 'admin' or 'reader'.");
                    }
                },
            ],
            'password' => ['nullable', Password::defaults()],
        ];
    }

    public function attributes()
    {
        return [
        ];
    }
}
