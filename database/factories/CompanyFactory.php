<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "company_name" => $this->faker->company,
            "company_registration_number" => $this->faker->numerify('######-####'),
            "company_foundation_date" => $this->faker->dateTimeBetween('-30 years', 'now')->format('Y-m-d'),
            "country" => $this->faker->country,
            "zip_code" => $this->faker->postcode,
            "city" => $this->faker->city,
            "street_address" => $this->faker->address,
            "latitude" => $this->faker->latitude,
            "longitude" => $this->faker->longitude(-90, 90),
            "company_owner" => $this->faker->name,
            "employees" => $this->faker->randomNumber(3, true),
            "activity" => $this->faker->randomElement(['Car', 'Building Industry', 'Food', 'Growing Plants', 'IT']),
            "active" => $this->faker->boolean(),
            "email" => $this->faker->email,
            "password" => Hash::make($this->faker->password),
        ];
    }
}
