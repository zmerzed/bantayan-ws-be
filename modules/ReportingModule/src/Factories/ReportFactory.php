<?php

namespace Kolette\Reporting\Factories;

use Kolette\Auth\Models\User;
use Kolette\Reporting\Models\Report;
use Kolette\Reporting\Models\ReportCategories;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        return [
            'reportable_type' => (new User())->getMorphClass(),
            'reportable_id' => User::factory()->create()->id,
            'reason_id' => ReportCategories::factory()->create()->id,
            'reported_by' => User::factory()->create()->id,
            'description' => $this->faker->sentence,
        ];
    }
}
