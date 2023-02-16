<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobsController extends Controller
{
    public function index(): JsonResponse
    {
        $jobs = Job::query()
            ->with('customer')
            ->whereHas('customer', function (Builder $builder) {
                $builder->where('active', '=', true);
            })
            ->orderByDesc('updated_at')
            ->withTrashed();

        return response()->json([
            'status' => true,
            'data' => $jobs->paginate(20)
        ]);
    }

    public function create(Request $request): JsonResponse
    {
        //
    }

    public function show(string $slug): JsonResponse
    {
        //
    }

    public function edit(Request $request): JsonResponse
    {
        //
    }

    public function destroy(Request $request): JsonResponse
    {
        //
    }
}
