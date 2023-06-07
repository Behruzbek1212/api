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
    public function all(Request $request)
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
            ->where('active', '=', true)->paginate($params['limit'] ?? null);

        if ($name = $request->get('name'))
            $companies->where('name', 'like', '%' . $name . '%');

        if ($location = $request->get('location'))
            $companies->where('location', $location);
        $list = CompaniesResource::collection($companies);
        return [
            'status' => true,
            'data' => $list,
        ];
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

        $costumer_id = Customer::query()->where('user_id', auth()->id())->first()->id;
        $company = Job::query()
            ->with('customer')
            ->WhereHas('customer', function ($query) use ($costumer_id) {
                $query->where('id', '=', $costumer_id);
                $query->where('active', '=', true);
                $query->with(['user:id,email,phone,verified'])
                    ->whereHas('user', function (Builder $query) {
                        $query->where('role', '=', 'customer');
                    });
            })->orderByDesc('id');
        // _auth()->check() && _user()->customerStats()
        //     ->syncWithoutDetaching($company);
        return response()->json([
            'status' => true,
            'data' => $company->paginate($params['limit'] ?? null)
        ]);
    }
}
