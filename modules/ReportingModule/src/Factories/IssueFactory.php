<?php

namespace Kolette\Reporting\Factories;

use Kolette\Auth\Models\User;
use Kolette\Reporting\Models\Issue;
use Illuminate\Database\Eloquent\Factories\Factory;

class IssueFactory extends Factory
{
    protected $model = Issue::class;

    public function definition(): array
    {
        return [
            'subject' => $this->faker->words(3, true),
            'reported_by' => fn () => User::factory()->create()->id,
            'description' => $this->faker->sentence,
        ];
    }
}
