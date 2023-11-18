<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use WithFaker, RefreshDatabase;

 /**
     * Test storing an API company.
     *
     * @return void
     */
    public function testCompanyStore()
    {
        // Generate new data for creating the company
        $userData = [
            "company_name" => $this->faker->company,
            "company_registration_number" => $this->faker->numerify('######-####'),
            "company_foundation_date" => $this->faker->dateTimeBetween('-30 years', 'now')->format('Y-m-d'),
            "country" => $this->faker->country,
            "zip_code" => $this->faker->postcode,
            "city" => $this->faker->city,
            "street_address" => $this->faker->address,
            "latitude" => $this->faker->latitude,
            "longitude" => $this->faker->longitude,
            "company_owner" => $this->faker->name,
            "employees" => $this->faker->randomNumber(3, true),
            "activity" => $this->faker->randomElement(['Car', 'Building Industry', 'Food', 'Growing Plants', 'IT']),
            "active" => $this->faker->boolean(),
            "email" => $this->faker->email,
            "password" => Hash::make($this->faker->password),
        ];

        // Send a POST request to store the company
        $response = $this->postJson('/api/companies', $userData);

        // Assert that the request was successful (status code 201)
        $response->assertStatus(201);

        // Assert that the user was stored in the database with the provided data
        $this->assertDatabaseHas('companies', [
            'company_name' => $userData['company_name'],
            'company_registration_number' => $userData['company_registration_number'],
            'company_foundation_date' => $userData['company_foundation_date'],
            'country' => $userData['country'],
            'zip_code' => $userData['zip_code'],
            'city' => $userData['city'],
            'street_address' => $userData['street_address'],
            'latitude' => $userData['latitude'],
            'longitude' => $userData['longitude'],
            'company_owner' => $userData['company_owner'],
            'employees' => $userData['employees'],
            'activity' => $userData['activity'],
            'active' => $userData['active'],
            'email' => $userData['email'],
        ]);
    }
}
