<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\ActivityCollection;
use App\Http\Resources\ActivityResource;
use App\Http\Resources\CompanyCollection;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\CreationListCollection;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    /**
     * Display a listing of companies.
     *
     * @param string|null $ids Comma-separated list of company IDs for filtering.
     * @return \App\Http\Resources\CompanyCollection
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
     * Store a newly created company in storage.
     *
     * @param \App\Http\Requests\StoreCompanyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCompanyRequest $request): \Illuminate\Http\JsonResponse
    {
        $validatedData = $request->validated();
        $validatedData['password'] = Hash::make($validatedData['password']);

        $company = Company::create($validatedData);

        return response()->json(['message' => 'Company created successfully', 'data' => new CompanyResource($company)], 201);
    }

    /**
     * Display the specified company.
     *
     * @param \App\Models\Company $company
     * @return \App\Http\Resources\CompanyResource
     */
    public function show(Company $company)
    {
        return new CompanyResource($company);
    }

    /**
     * Update the specified company in storage.
     *
     * @param \App\Http\Requests\UpdateCompanyRequest $request
     * @param \App\Models\Company $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCompanyRequest $request, Company $company): \Illuminate\Http\JsonResponse
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
     * Get a list of companies grouped by activity.
     *
     * @return \Illuminate\Http\JsonResponse|\App\Http\Resources\ActivityCollection
     */
    public function activityQuery(): \Illuminate\Http\JsonResponse|ActivityCollection
    {
        $companies = Company::groupBy('activity')
            ->select('activity', DB::raw('JSON_ARRAYAGG(company_name) as company_names'))
            ->get();

        if ($companies->isNotEmpty()) {
            return new ActivityCollection($companies);
        }

        return response()->json(['message' => 'No results'], 404);
    }

    /**
     * Build a query to retrieve a list of creation dates from the 'recursive_calendar' table
     * along with associated company names based on their foundation dates.
     *
     * The query uses a recursive join with the 'companies' table and aggregates the
     * company names into a JSON array for each unique creation date.
     *
     * @return CreationListCollection
     */
    public function creationDateQuery(): CreationListCollection
    {
        $results =  DB::table('recursive_calendar')
            ->leftJoin('companies', 'recursive_calendar.date', '=', 'companies.company_foundation_date')
            ->groupBy('recursive_calendar.date')
            ->orderBy('recursive_calendar.date', 'ASC')
            ->select(
                'recursive_calendar.date',
                DB::raw('JSON_ARRAYAGG(companies.company_name) AS company_names')
            )->get();

        return new CreationListCollection($results);
    }
}
