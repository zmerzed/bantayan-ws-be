<?php

namespace Kolette\Auth\Http\Requests\Admin\Business;

use App\Rules\UniquePhoneByRole;
use Kolette\Auth\Rules\ValidPhoneNumber;
use Kolette\Auth\Support\ValidatesPhone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BusinessUpdateRequest extends FormRequest
{
    use ValidatesPhone;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user;
        $infoId = $user->businessInformation->id;
        $role = $user->getDefaultRoleName();
        return [
            'name'      => ['sometimes', 'string', 'unique:business_informations,name,' . $infoId],
            'address'   => ['sometimes', 'string', 'max:255'],
            'phone_number' => [
                'required',
                new ValidPhoneNumber,
                new UniquePhoneByRole($role, $user->id)
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
            'business_hours.*' => [
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
