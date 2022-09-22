<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Display all jobs
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function all(Request $request): JsonResponse
    {
        /** @var Builder $jobs */
        $jobs = Job::query()
            // Check if customer status is active
            ->whereHas('customer', function (EloquentBuilder $query) {
                $query->where('active', '=', true);
            })
            ->with('customer')
            ->where('status', '=', 'approved');

        if ($title = $request->get('title'))
            $jobs->where('title', 'like', '%' . $title . '%');

        if ($type = $request->get('type'))
            $jobs->where('type', 'like', '&' . $type . '&');

        if ($location_id = $request->get('location_id'))
            $jobs->where('location_id', '=', $location_id);

        if ($salary = $request->get('salary'))
            $jobs->where('salary->amount', 'like', '%' . $salary . '%');

        /** @see https://laravel.com/docs/9.x/queries#json-where-clauses */
        if ($currency = $request->get('currency'))
            $jobs->whereJsonContains('salary->currency', $currency);

        if ($limit = $request->get('limit'))
            $jobs->limit($limit);

        return response()->json([
            'status' => true,
            'jobs' => $jobs->get()
        ]);
    }

    /**
     * Get guide information
     *
     * @param string $slug
     * @return JsonResponse
     */
    public function get(string $slug): JsonResponse
    {
        $job = Job::query()->with('customer')
            ->findOrFail($slug);

        return response()->json([
            'status' => true,
            'job' => $job
        ]);
    }
}
