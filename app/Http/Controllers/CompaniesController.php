<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Job;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
            ->with(['user:id,email,phone,verified', 'jobs'])
            ->orderByDesc('id')
            ->whereHas('user', function (Builder $query) {
                $query->where('role', '=', 'customer');
            })
            ->where('active', '=', true);

        if ($name = $request->get('name'))
            $companies->where('name', 'like', '%' . $name . '%');

        if ($location = $request->get('location'))
            $companies->where('location', $location);

        return response()->json([
            'status' => true,
            'data' => $companies->paginate($params['limit'] ?? null)
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
            ->with(['user:id,email,phone,verified'])
            ->whereHas('user', function (Builder $query) {
                $query->where('role', '=', 'customer');
            })
            ->where('active', '=', true)
            ->where('id', '=', $id)
            ->firstOrFail();
        _auth()->check() && _user()->customerStats()
            ->syncWithoutDetaching($company);
        return response()->json([
            'status' => true,
            'data' => $company
        ]);
    }

    public function job(Request $request): JsonResponse
    {
        $params = $request->validate([
            'limit' => ['integer', 'nullable']
        ]);

        $company = Job::query()
            ->with('customer')
            ->WhereHas('customer', function ($query) {
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
