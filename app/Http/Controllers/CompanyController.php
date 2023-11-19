<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyCollection;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string|null $ids = null): CompanyCollection
    {
        $ids = $ids ? explode(',', $ids) : [];

        $query = Company::query();

        if ($ids) {
            $query->whereIn('company_id', $ids);
        }

        $records = $query->paginate();


        return new CompanyCollection($records);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request): \Illuminate\Http\JsonResponse
    {
        $validatedData = $request->validated();
        $validatedData['password'] = Hash::make($validatedData['password']);

        $company = Company::create($validatedData);

        return response()->json(['message' => 'Company created successfully', 'data' => new CompanyResource($company)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        return new CompanyResource($company);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $validatedData = $request->validated();

        $validatedData['password'] = Hash::make($validatedData['password']);

        try {
        $company->update($validatedData);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Company not updated', 'data' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Company updated successfully', 'data' => new CompanyResource($company)], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        //
    }
}
