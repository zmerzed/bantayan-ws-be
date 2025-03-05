<?php

namespace Kolette\Category\Factories;

use Kolette\Category\Enums\CategoryType;
use Kolette\Category\Models\Category;
use Kolette\Category\Models\Subcategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubcategoryFactory extends Factory
{
    protected $model = Subcategory::class;

    public function definition(): array
    {
        return [
            'label' => 'Sub:' . $this->faker->words(3, true),
            'description' => $this->faker->paragraph,
            'type' => CategoryType::GENERAL,
        ];
    }

    public function configure(): SubcategoryFactory
    {
        return $this->afterMaking(
            function (Subcategory $subcategory) {
                if (!$subcategory->parent_id) {
                    $subcategory->parent_id = Category::factory()->create()->getKey();
                }
            }
        );
    }
}
