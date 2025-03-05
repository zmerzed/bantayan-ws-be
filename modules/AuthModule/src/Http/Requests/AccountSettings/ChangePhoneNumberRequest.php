<?php

namespace Kolette\Auth\Http\Requests\AccountSettings;

use App\Rules\UniquePhoneByRole;
use Kolette\Auth\Models\User;
use Kolette\Auth\Rules\UniquePhoneNumber;
use Kolette\Auth\Rules\ValidPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangePhoneNumberRequest extends FormRequest
{
    public function rules(): array
    {
        $role = $this->user()->getDefaultRoleName();

        return [
            'phone_number' => [
                'required',
                new ValidPhoneNumber,
                new UniquePhoneByRole($role),
            ],
        ];
    }
}
