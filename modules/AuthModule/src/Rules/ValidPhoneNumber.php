<?php

namespace Kolette\Auth\Rules;

use Kolette\Auth\Support\ValidatesPhone;
use Illuminate\Contracts\Validation\Rule;

class ValidPhoneNumber implements Rule
{
    use ValidatesPhone;

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return $this->isPhone($value);
    }

    public function message(): string
    {
        return trans('auth::validation.valid_phone_number');
    }
}
