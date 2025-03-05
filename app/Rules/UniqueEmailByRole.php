<?php

namespace App\Rules;

use Kolette\Auth\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueEmailByRole implements ValidationRule
{
    public function __construct(public ?string $role, public ?int $id = null)
    {
        //
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (User::whereHas('roles', fn ($query) => $query->where('name', $this->role))
            ->when(!empty($this->id), fn ($query) => $query->where('id', '!=', $this->id))
            ->where('email', $value)
            ->exists()
        ) {
            $fail(':attribute already exists');
        }
    }
}
