<?php

namespace Kolette\Auth\Http\Requests\AccountSettings;

use App\Rules\UniqueEmailByRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeEmailRequest extends FormRequest
{
    public function rules(): array
    {
        $role = $this->user()->getDefaultRoleName();

        return [
            'email' => [
                'required',
                'email',
                'max:255',
                new UniqueEmailByRole($role, $this->user()->id),
            ],
        ];
    }
}
