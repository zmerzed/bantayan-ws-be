<?php

namespace Kolette\Auth\Http\Requests;

use Kolette\Auth\Enums\Role;
use Kolette\Auth\Support\ValidatesPhone;
use Kolette\Media\Rules\UnassignedMedia;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    use ValidatesPhone;

    public function rules(): array
    {
        if ($this->user()->hasRole(Role::MERCHANT)) {
            $infoId = $this->user()->businessInformation->id;
        }

        $rules = [
            'role' => [
                'required',
                'bail',
                Rule::exists('roles', 'name')
            ],
            'first_name' => [Rule::requiredIf($this->input('role') == Role::USER), 'string', 'max:255'],
            'last_name' => [Rule::requiredIf($this->input('role') == Role::USER), 'string', 'max:255'],
        ];

        if (!empty($infoId)) {
            $rules['business_name'] = [Rule::requiredIf($this->input('role') == Role::MERCHANT), 'string', 'max:255', 'unique:business_informations,name,' . $infoId];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'first_name.sometimes' => 'First Name must not be empty.',
            'first_name.required' => 'First Name must not be empty.',
            'first_name.string' => 'First Name must not be empty.',
            'last_name.sometimes' => 'Last Name must not be empty.',
            'last_name.required' => 'Last Name must not be empty.',
            'last_name.string' => 'Last Name must not be empty.',
            'business_name.sometimes' => 'Business Name must not be empty.',
            'business_name.required' => 'Business Name must not be empty.',
            'business_name.string' => 'Business Name must not be empty.',
        ];
    }
}
