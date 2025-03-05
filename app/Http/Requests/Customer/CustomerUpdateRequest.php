<?php

namespace App\Http\Requests\Customer;

use App\Enums\AccountType;
use App\Enums\ApplicationType;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class CustomerUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        //dd(new EnumValue(ApplicationType::class));
        return [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'mi' => ['required'],
            'address' => ['required'],
            'brgy' => ['required'],
            'phone_number' => ['nullable'],
            'work_phone_number' => ['nullable'],
            'application_type' => ['required', new EnumValue(ApplicationType::class)],
            'account_type' => ['required', new EnumValue(AccountType::class)],
        ];
    }

    public function attributes()
    {
        return [
        ];
    }
}
