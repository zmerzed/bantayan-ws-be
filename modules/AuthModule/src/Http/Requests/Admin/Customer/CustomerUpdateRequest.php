<?php

namespace Kolette\Auth\Http\Requests\Admin\Customer;

use Kolette\Auth\Rules\ValidPhoneNumber;
use Kolette\Auth\Rules\ValidUser;
use Kolette\Auth\Support\ValidatesPhone;
use Illuminate\Foundation\Http\FormRequest;

class CustomerUpdateRequest extends FormRequest
{
    use ValidatesPhone;

    public function rules(): array
    {

        if ($this->route('user')) {
            $userId = $this->route('user')->id;
        } else {
            $userId = $this->route('id');
        }
        //dd($userId);
        return [
            'first_name' => [
                'required',
            ],
            'last_name' => [
                'required',
            ],
            'phone_number' => [
                'sometimes',
                'required',
                new ValidPhoneNumber,
                "unique:users,phone_number,{$userId},id",
                new ValidUser($userId),
            ],
            'email' => [
                'sometimes',
                'required',
                'email',
                "unique:users,email,{$userId},id",
                new ValidUser($userId),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->get('phone_number')) {
            $this->merge([
                'phone_number' => $this->cleanPhoneNumber($this->get('phone_number')),
            ]);
        }
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
        ];
    }
}
