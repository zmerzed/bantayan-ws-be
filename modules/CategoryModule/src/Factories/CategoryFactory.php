<?php

namespace Kolette\Category\Factories;

use Kolette\Category\Enums\CategoryType;
use Kolette\Category\Models\Category;
use Kolette\Category\Models\Subcategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'label' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph,
            'type' => CategoryType::GENERAL,
        ];
    }

    public function withSubcategories(int $count = 3): CategoryFactory
    {
        return $this->afterCreating(
            function (Category $category) use ($count) {
                Subcategory::factory()
                    ->count($count)
                    ->create(
                        [
                            'parent_id' => $category->getKey(),
                        ]
                    );
            }
        );
    }
}
