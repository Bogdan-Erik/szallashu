<?php

namespace Database\Seeders;

use App\Imports\CompaniesImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $filePath = public_path('Seeders/migration.csv');
            Excel::import(new CompaniesImport, $filePath, null);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
