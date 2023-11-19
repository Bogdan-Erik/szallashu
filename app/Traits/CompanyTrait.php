<?php

namespace App\Traits;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

trait CompanyTrait
{
    /**
     * Process company data.
     *
     * @param StoreCompanyRequest|UpdateCompanyRequest $request The request object containing the validated data.
     * @param Company|null $company The company object to be updated, or null if creating a new company.
     * @return JsonResponse The JSON response containing the result of the operation.
     */
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
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Company not found', 'data' => null], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Company operation failed', 'data' => $e->getMessage()], 500);
        }

        return response()->json(['message' => $message, 'data' => new CompanyResource($company)], 201);
    }
}
