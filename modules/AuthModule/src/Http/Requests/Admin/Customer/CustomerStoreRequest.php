<?php

namespace Kolette\Auth\Http\Requests\Admin\Customer;

use App\Rules\UniqueEmailByRole;
use App\Rules\UniquePhoneByRole;
use Kolette\Auth\Rules\ValidPhoneNumber;
use Kolette\Auth\Support\BypassCodeValidator;
use Kolette\Auth\Support\OneTimePassword\InteractsWithOneTimePassword;
use Kolette\Auth\Support\ValidatesPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class CustomerStoreRequest extends FormRequest
{
    use ValidatesPhone;
    use InteractsWithOneTimePassword;
    use BypassCodeValidator;

    protected $stopOnFirstFailure = false;

    public function rules(): array
    {
        return [
            'first_name' => [
                'required',
            ],
            'last_name' => [
                'required',
            ],
            'email' => [
                'required',
                'bail',
                'email',
                'max:255',
                "unique:users,email",
            ],
            'phone_number' => [
                'required',
                'bail',
                'nullable',
                new ValidPhoneNumber,
                "unique:users,phone_number",
            ],
            'password' => [
                'required',
                'string',
                'min:8'
            ]
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->phone_number) {
            $this->merge([
                'phone_number' => $this->cleanPhoneNumber($this->phone_number),
            ]);
        }
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if ($this->has('otp')) {
                $otp = $this->get('otp');
                $phoneNumber = $this->uncleanPhoneNumber($this->get('phone_number'));
                if (!$this->isUsingBypassCode($otp) && !$this->hasValidOneTimePassword($phoneNumber, $otp)) {
                    $validator->errors()->add('otp', 'The one time password is invalid');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'First Name must not be empty.',
            'first_name.string' => 'First Name must not be empty.',
            'last_name.required' => 'Last Name must not be empty.',
            'last_name.string' => 'Last Name must not be empty.'
        ];
    }
}
