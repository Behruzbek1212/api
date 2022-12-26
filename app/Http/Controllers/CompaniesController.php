<?php

namespace App\Http\Controllers;

use App\Models\Customer;
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
        $companies = Customer::query()
            ->with(['user:id,email,phone,verified', 'jobs'])
            ->whereHas('user', function (Builder $query) {
                $query->where('role', '=', 'customer');
            })
            ->where('active', '=', true);

        if ($name = $request->get('name'))
            $companies->where('name', 'like', '%'.$name.'%');

        if ($location = $request->get('location'))
            $companies->where('location', $location);

        if ($limit = $request->get('limit'))
            $companies->limit($limit);

        return response()->json([
            'status' => true,
            'data' => $companies->get()
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
            ->with(['user:id,email,phone,verified', 'jobs'])
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
}
