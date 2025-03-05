<?php

namespace Kolette\Category\Models;

use Kolette\Category\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'type',
    ];

    protected static function newFactory(): mixed
    {
        return CategoryFactory::new();
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany(Subcategory::class, 'parent_id');
    }

    public function scopeOnlyTopParent(Builder $query): void
    {
        $query->whereNull('parent_id');
    }
}
