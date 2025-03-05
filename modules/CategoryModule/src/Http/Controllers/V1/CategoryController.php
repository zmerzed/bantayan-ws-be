<?php

namespace Kolette\Category\Http\Controllers\V1;

use Kolette\Category\Enums\CategoryType;
use Kolette\Category\Http\Controllers\Controller;
use Kolette\Category\Http\Resources\CategoryResource;
use Kolette\Category\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryController extends Controller
{
    public function __invoke(Request $request): AnonymousResourceCollection
    {
        $collection = QueryBuilder::for(Category::class)
            ->onlyTopParent()
            ->allowedIncludes('subcategories')
            ->allowedFilters(AllowedFilter::exact('type')->default(CategoryType::GENERAL))
            ->allowedSorts('label')
            ->defaultSort('label')
            ->get();

        return CategoryResource::collection($collection);
    }
}
