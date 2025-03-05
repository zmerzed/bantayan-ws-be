<?php

namespace Kolette\Category\Rules;

use Kolette\Category\Models\Category;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;

class CategoryExistsRule implements Rule
{
    public function __construct(private readonly ?string $type = null)
    {
        //
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return Category::query()
            ->onlyTopParent()
            ->when($this->type, fn(Builder $query) => $query->where('type', $this->type))
            ->exists();
    }

    public function message(): string
    {
        return __('validation.exists');
    }
}
