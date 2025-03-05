<?php

namespace Kolette\Auth\Http\Requests\Admin\Business;

use Kolette\Auth\Enums\Role;
use Illuminate\Validation\Rule;
use App\Rules\UniquePhoneByRole;
use Kolette\Auth\Rules\ValidPhoneNumber;
use Kolette\Auth\Support\ValidatesPhone;
use Illuminate\Foundation\Http\FormRequest;

class BusinessStoreRequest extends FormRequest
{
    use ValidatesPhone;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'      => ['sometimes', 'string', 'unique:business_informations,name'],
            'address'   => ['sometimes', 'string', 'max:255'],
            'phone_number' => [
                Rule::requiredIf(!$this->has('email')),
                'bail',
                'nullable',
                new ValidPhoneNumber,
                new UniquePhoneByRole(Role::MERCHANT),
            ],
            'logo' => 'nullable|image',
            'lock_reward_system' => ['required', 'boolean'],
            'reward_system' => [
                Rule::requiredIf($this->has('lock_reward_system') && !$this->lock_reward_system),
                'nullable',
            ],
            'redeem_amount' => [
                Rule::requiredIf($this->has('lock_reward_system') && !$this->lock_reward_system),
                'integer',
            ],
            'business_hours.*'     => [
                'required',
                'array',
                // Rule::in('monday,tuesday,wednesday,thursday,friday,saturday,sunday'),
            ],
            'business_hours.*.from' => ['nullable', 'date_format:H:i'],
            'business_hours.*.to'  => ['nullable', 'date_format:H:i'],
            'business_hours.*.availability' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->phone) {
            $this->merge([
                'phone' => $this->cleanPhoneNumber($this->phone),
            ]);
        }
    }
}
