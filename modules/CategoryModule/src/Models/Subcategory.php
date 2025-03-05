<?php

namespace Kolette\Category\Models;

use Kolette\Category\Factories\SubcategoryFactory;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subcategory extends Category
{
    use HasFactory;

    protected $table = 'categories';

    protected static function newFactory(): SubcategoryFactory
    {
        return SubcategoryFactory::new();
    }

    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope('categoryRule', function (Builder $builder) {
            $builder->whereNotNull('parent_id');
        });

        static::creating(function (Subcategory $model) {
            throw_if(is_null($model->parent_id), new Exception('Subcategory does not contain a parent.'));
            // If no type was provided use the parent type
            $model->type = $model->type ?? $model->category->type;
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
}
