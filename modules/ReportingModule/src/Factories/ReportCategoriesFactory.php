<?php

namespace Kolette\Reporting\Factories;

use Kolette\Auth\Models\User;
use Kolette\Reporting\Models\ReportCategories;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportCategoriesFactory extends Factory
{
    protected $model = ReportCategories::class;

    public function definition(): array
    {
        return [
            'label' => $this->faker->name,
        ];
    }
}
