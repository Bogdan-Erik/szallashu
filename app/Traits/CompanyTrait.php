<?php

namespace App\Traits;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

trait CompanyTrait
{
    private function processCompanyData(StoreCompanyRequest|UpdateCompanyRequest $request, Company $company = null): JsonResponse
    {
        $validatedData = $request->validated();

        $validatedData['password'] = Hash::make($validatedData['password']);

        try {
            if ($company) {
                $company->update($validatedData);
                $message = 'Company updated successfully';
            } else {
                $company = Company::create($validatedData);
                $message = 'Company created successfully';
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Company not updated', 'data' => $e->getMessage()], 500);
        }

        return response()->json(['message' => $message, 'data' => new CompanyResource($company)], 201);
    }

    private function parseIds(?string $ids): array
    {
        return $ids ? explode(',', $ids) : [];
    }
}
