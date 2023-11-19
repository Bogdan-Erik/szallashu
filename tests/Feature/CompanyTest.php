<?php

namespace Tests\Feature;

use App\Http\Controllers\CompanyController;
use App\Http\Kernel;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    protected $seed = true;

    public function testIndexMethod()
    {
        $mockedRecords = Company::factory()->count(5)->create();

        $idsToFilter = $mockedRecords->pluck('company_id')->toArray();
        $idsString = implode(',', $idsToFilter);

        $response = $this->get("/api/companies/$idsString");

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    "company_name",
                    "company_registration_number",
                    "company_foundation_date",
                    "country",
                    "zip_code",
                    "city",
                    "street_address",
                    "latitude",
                    "longitude",
                    "company_owner",
                    "employees",
                    "activity",
                    "active",
                    "email",
                    "password",
                ],
            ],
            'links',
            'meta',
        ]);

        $response->assertJsonCount(count($mockedRecords), 'data');

        $response->assertJsonFragment(['company_id' => $mockedRecords->first()->company_id]);
    }

    /**
     * Test storing an API company.
     *
     * @return void
     */
    public function testCompanyStore()
    {
        $userData = [
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

        $response = $this->postJson('/api/companies', $userData);

        $response->assertStatus(201);

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

    /**
     * Test updating an API company.
     *
     * @return void
     */
    public function testCompanyUpdate()
    {
        $company = Company::factory()->create();

        $updatedData = [
            "company_name" => $this->faker->company,
            "company_registration_number" => $this->faker->numerify('######-####'),
            "company_foundation_date" => $company->company_foundation_date,
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
            "password" => $this->faker->password,
        ];

        $response = $this->putJson('/api/companies/' . $company->company_id, $updatedData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('companies', [
            'company_id' => $company->company_id,
            'company_name' => $updatedData['company_name'],
            'company_registration_number' => $updatedData['company_registration_number'],
            "company_foundation_date" => $company->company_foundation_date,
            'country' => $updatedData['country'],
            'zip_code' => $updatedData['zip_code'],
            'city' => $updatedData['city'],
            'street_address' => $updatedData['street_address'],
            'latitude' => $updatedData['latitude'],
            'longitude' => $updatedData['longitude'],
            'company_owner' => $updatedData['company_owner'],
            'employees' => $updatedData['employees'],
            'activity' => $updatedData['activity'],
            'active' => $updatedData['active'],
            'email' => $updatedData['email'],
        ]);
    }
}
