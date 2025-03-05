<?php

namespace App\Rules;

use App\Models\BusinessInformation;
use Kolette\Auth\Enums\Role;
use Kolette\Auth\Models\User;
use Kolette\Auth\Support\ValidatesPhone;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniquePhoneByRole implements ValidationRule
{
    use ValidatesPhone;

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
            ->where('phone_number', $this->cleanPhoneNumber($value))
            ->exists()
        ) {
            $fail(':attribute already exists');
        }

        if ($this->role == Role::MERCHANT) {
            if (BusinessInformation::when(!empty($this->id), fn ($query) => $query->where('user_id', '!=', $this->id))
                ->where('phone', $this->cleanPhoneNumber($value))
                ->exists()
            ) {
                $fail(':attribute number already exists');
            }
        }
    }
}
