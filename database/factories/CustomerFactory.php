<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        $faker = fake();

        return [
            'job_title' => $faker->jobTitle(),
            'email_address' => $faker->unique()->safeEmail(),
            'first_name' => $faker->firstName(),
            'last_name' => $faker->lastName(),
            'phone' => $faker->phoneNumber(),
            'registered_since' => $faker->dateTimeThisYear(),
        ];
    }
}
