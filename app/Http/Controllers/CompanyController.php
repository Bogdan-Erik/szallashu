<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\ActivityCollection;
use App\Http\Resources\CompanyCollection;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\CreationListCollection;
use App\Models\Company;
use App\Models\ViewRecursiveCalendar;
use App\Traits\CompanyTrait;
use Illuminate\Http\JsonResponse;

class CompanyController extends Controller
{
    use CompanyTrait;

    /**
     * Display a listing of companies.
     *
     * @param string|null $ids Comma-separated list of company IDs for filtering.
     * @return \App\Http\Resources\CompanyCollection
     */
    public function index(string|null $ids = null): CompanyCollection
    {
        $records = Company::filterByIds($ids)->paginate();

        return new CompanyCollection($records);
    }


    /**
     * Store a newly created company in storage.
     *
     * @param \App\Http\Requests\StoreCompanyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCompanyRequest $request): JsonResponse
    {
        return $this->processCompanyData($request);
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
    public function update(UpdateCompanyRequest $request, Company $company): JsonResponse
    {
        return $this->processCompanyData($request, $company);
    }

    /**
     * Get a list of companies grouped by activity.
     *
     * @return \Illuminate\Http\JsonResponse|\App\Http\Resources\ActivityCollection
     */
    public function activityQuery(): JsonResponse|ActivityCollection
    {
        $companies = Company::groupBy('activity')
            ->select('activity')
            ->jsonAgg('company_name', 'company_names')
            ->get();

        return $companies->isNotEmpty()
            ? new ActivityCollection($companies)
            : response()->json(['message' => 'No results'], 404);
    }

    /**
     * Build a query to retrieve a list of creation dates from the 'view_recursive_calendar' table
     * along with associated company names based on their foundation dates.
     *
     * The query uses a recursive join with the 'companies' table and aggregates the
     * company names into a JSON array for each unique creation date.
     *
     * @return CreationListCollection
     */
    public function creationDateQuery(): CreationListCollection
    {
        $results = ViewRecursiveCalendar::leftJoin('companies', 'view_recursive_calendar.date', '=', 'companies.company_foundation_date')
            ->groupBy('view_recursive_calendar.date')
            ->orderBy('view_recursive_calendar.date', 'ASC')
            ->select('view_recursive_calendar.date')
            ->jsonAgg('companies.company_name', 'company_names')
            ->get();

        return new CreationListCollection($results);
    }
}
