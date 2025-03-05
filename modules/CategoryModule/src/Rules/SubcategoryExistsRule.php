<?php

namespace Kolette\Category\Rules;

use Kolette\Category\Models\Subcategory;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;

class SubcategoryExistsRule implements Rule
{
    public function __construct(public ?int $parentId = null)
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
        return Subcategory::query()
            ->when($this->parentId, fn(Builder $builder) => $builder->where('parent_id', $this->parentId))
            ->exists();
    }

    public function message(): string
    {
        return __('validation.exists');
    }
}
