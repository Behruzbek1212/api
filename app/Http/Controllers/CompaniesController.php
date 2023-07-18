<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompaniesResource;
use App\Http\Resources\CompanyResource;
use App\Models\Customer;
use App\Models\Job;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompaniesController extends Controller
{
    /**
     * Get companies list
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function all(Request $request): JsonResponse
    {
        $params = $request->validate([
            'limit' => ['integer', 'nullable']
        ]);

        $companies = Customer::query()
            // ->with(['user:id,email,phone,verified', 'jobs'])
            ->withCount('jobs as jobs_count')
            ->orderByDesc('id')
            ->whereHas('user', function (Builder $query) {
                $query->where('role', '=', 'customer');
            })
            ->where('active', '=', true);

        if ($name = $request->get('name'))
            $companies->where('name', 'like', '%' . $name . '%');

        if ($title = $request->get('title'))
            $companies->whereHas('jobs', function (Builder $query) use ($title) {
                $query->where('title' ,'like', '%' . $title . '%');
            });
        if ($sphere = $request->get('sphere'))
            $companies->whereHas('jobs', function (Builder $query) use ($sphere) {
                $query->whereJsonContains('sphere', $sphere);
            });           

        if ($location = $request->get('location'))
            $companies->where('location', $location);

        $data = $companies->paginate($params['limit'] ?? null);    
        $list = CompaniesResource::collection($data);
        return response()->json([
            'status' => true,
            'data' => $list,
        ]);
    }

    /**
     * Find company with slug
     *
     * @param int $id
     * @return JsonResponse
     */
    public function get(int $id): JsonResponse
    {

        $company = Customer::query()
            // ->with(['user:id,email,phone,verified'])
            ->whereHas('user', function (Builder $query) {
                $query->where('role', '=', 'customer');
            })
            ->withCount('jobs as jobs_count')
            ->where('active', '=', true)
            ->where('id', '=', $id)
            ->firstOrFail();

        _auth()->check() && _user()->customerStats()
            ->syncWithoutDetaching($company);

        return response()->json([
            'status' => true,
            'data' => new CompanyResource($company)
        ]);
    }

    public function job(Request $request): JsonResponse
    {
        $params = $request->validate([
            'limit' => ['integer', 'nullable']
        ]);
    
        $customer_id = _auth()->user()->customer->id;
        //        dd($costumer_id);
        $company = Job::query()
            ->with('customer')
            ->WhereHas('customer', function ($query) use ($customer_id) {
                $query->where('id', '=', $customer_id);
                $query->where('active', '=', true);
                $query->with(['user:id,email,phone,verified'])
                    ->whereHas('user', function (Builder $query) {
                        $query->where('role', '=', 'customer');
                    });
            })->orderByDesc('id');

        if ($title = $request->get('title'))
            $company->where('title' ,'like', '%' . $title . '%');

        if ($sphere = $request->get('sphere'))
            $company->whereJsonContains('sphere', $sphere);

        // _auth()->check() && _user()->customerStats()
        //     ->syncWithoutDetaching($company);
        return response()->json([
            'status' => true,
            'data' => $company->paginate($params['limit'] ?? null)
        ]);
    }
}
