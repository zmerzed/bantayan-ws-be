<?php

namespace Kolette\Auth\Http\Requests;

use App\Rules\UniqueEmailByRole;
use App\Rules\UniquePhoneByRole;
use Kolette\Auth\Rules\ValidPhoneNumber;
use Kolette\Auth\Support\BypassCodeValidator;
use Kolette\Auth\Support\OneTimePassword\InteractsWithOneTimePassword;
use Kolette\Auth\Support\ValidatesPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class RegisterUserRequest extends FormRequest
{
    use ValidatesPhone;
    use InteractsWithOneTimePassword;
    use BypassCodeValidator;

    protected $stopOnFirstFailure = false;

    public function rules(): array
    {
        return [
            'role' => [
                'required',
                'bail',
                Rule::exists('roles', 'name')
            ],
            'email' => [
                Rule::requiredIf(!$this->has('phone_number')),
                'bail',
                'email',
                'max:255',
                new UniqueEmailByRole($this->input('role')),
            ],
            'phone_number' => [
                Rule::requiredIf(!$this->has('email')),
                'bail',
                'nullable',
                new ValidPhoneNumber,
                new UniquePhoneByRole($this->input('role')),
            ],
            'password' => [
                Rule::requiredIf($this->has('email')),
                'string',
                'min:8',
                'confirmed',
            ],
            'otp' => [
                Rule::requiredIf(
                    $this->has('phone_number') &&
                        filled($this->input('phone_number')) &&
                        !config('Kolette.auth.use_bypass_code')
                ),
                'string',
                'max:5',
            ],
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
