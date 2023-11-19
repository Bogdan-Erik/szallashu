<?php

namespace App\Imports;

use App\Models\Company;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CompaniesImport implements ToCollection, WithHeadingRow, WithStartRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Company::create([
                'company_id' => $row['companyid'],
                'company_name' => $row['companyname'],
                'company_registration_number' => $row['companyregistrationnumber'],
                'company_foundation_date' => $row['companyfoundationdate'],
                'country' => $row['country'],
                'zip_code' => $row['zipcode'],
                'city' => $row['city'],
                'street_address' => $row['streetaddress'],
                'latitude' => $row['latitude'],
                'longitude' => $row['longitude'],
                'company_owner' => $row['companyowner'],
                'employees' => $row['employees'],
                'activity' => $row['activity'],
                'active' => $row['active'],
                'email' => $row['email'],
                'password' => Hash::make($row['password']),
            ]);
        }
    }

    public function startRow(): int
    {
        return 2;
    }
}
