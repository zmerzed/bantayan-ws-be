<?php

namespace Kolette\Auth\Rules;

use Kolette\Auth\Enums\Role;
use Kolette\Auth\Models\User;
use Illuminate\Contracts\Validation\Rule;

class ValidUser implements Rule
{
    private $user;

    public function __construct($userId)
    {
        $this->user = User::find($userId);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $user = auth()->user();

        return $user->id === $this->user->id || $user->hasRole(Role::ADMIN);
    }

    public function message(): string
    {
        return trans('auth::validation.valid_user.non_admin_editing');
    }
}
