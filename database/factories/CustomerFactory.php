<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Enums\AccountType;
use App\Enums\CustomerStatus;
use App\Enums\ApplicationType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = \App\Models\Customer::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $brgy = 'Atop-atop';

        $data = [
            'account_number' => Customer::generateAccountNo(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'mi' => $this->faker->randomLetter(),
            'address' => 'Kandugyap',
            'brgy' => $brgy,
            'phone_number' => $this->faker->numerify('09#########'),
            'work_phone_number' => $this->faker->numerify('09#########'),
            'status' => CustomerStatus::ACTIVE,
            'account_type' => AccountType::RESIDENCE,
            'application_type' => ApplicationType::NEW,
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        return $data;
    }
}
