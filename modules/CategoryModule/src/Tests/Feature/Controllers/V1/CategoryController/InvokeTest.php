<?php

namespace Kolette\Category\Tests\Feature\Controllers\V1\CategoryController;

use Kolette\Category\Models\Category;
use Kolette\Category\Models\Subcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvokeTest extends TestCase
{
    use RefreshDatabase;

    public function testGetCategories(): void
    {
        Category::factory(3)
            ->create();

        $categories = Category::query()
            ->orderBy('label')
            ->get();

        $this->getJson('/api/v1/categories')
            ->assertOk()
            ->assertJsonStructure(
                [
                    'data' => [
                        [
                            'id',
                            'label',
                            'type',
                        ],
                    ],
                ]
            )
            ->assertJsonCount($categories->count(), 'data')
            ->assertJson(
                [
                    'data' => $categories->map(fn (Category $category) => $category->only(['id', 'label', 'type']))
                        ->toArray(),
                ]
            );
    }

    public function testItAlsoReturnsSubcategories(): void
    {
        Category::factory(3)
            ->withSubcategories()
            ->create();

        $categories = Category::query()
            ->orderBy('label')
            ->onlyTopParent()
            ->get();

        $this->getJson('/api/v1/categories?include=subcategories')
            ->assertOk()
            ->assertJsonStructure(
                [
                    'data' => [
                        '*' => [
                            'id',
                            'label',
                            'type',
                            'subcategories' => [
                                '*' => [
                                    'id',
                                    'label',
                                    'type',
                                ],
                            ],
                        ],
                    ],
                ]
            )
            ->assertJsonCount($categories->count(), 'data')
            ->assertJson(
                [
                    'data' => $categories->map(fn (Category $category) => array_merge(
                        $category->only(['id', 'label', 'type']),
                        [
                            'subcategories' => $category->subcategories->map(
                                fn (Subcategory $subcategory) => $subcategory->only(['id', 'label', 'type'])
                            )->toArray(),
                        ]
                    ))
                        ->toArray(),
                ]
            );
    }
}
