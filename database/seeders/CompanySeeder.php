<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CountriesImport;
use Illuminate\Support\Facades\File;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $filePath = public_path('Seeders/migration.csv');
            Excel::import(new CountriesImport, $filePath, null);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
