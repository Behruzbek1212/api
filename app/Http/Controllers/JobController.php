<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Resume;
use App\Models\User;
use App\Notifications\AuthorizedNotification;
use App\Notifications\RespondMessageNotification;
use App\Notifications\RespondNotification;
use Illuminate\Contracts\Auth\Authenticatable;
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
            ->whereHas('customer', function (EloquentBuilder $query) {
                $query->where('active', '=', true);
            })
            ->where('status', '=', 'approved')
            ->findOrFail($slug);

        return response()->json([
            'status' => true,
            'job' => $job
        ]);
    }

    public function respond(Request $request): JsonResponse
    {
        $request->validate([
            'resume_id' => ['required', 'numeric'],
            'job_slug' => ['required', 'string'],
            'message' => ['nullable', 'string']
        ]);

        /** @var Authenticatable|User $user */
        $user = _auth()->user();
        $resume = Resume::query()->findOrFail($request->input('resume_id'));
        $job = Job::query()->findOrFail($request->input('job_slug'));
        $message = $request->input('message');
        $customer = $job->customer;

        $customer->user->notify(new RespondMessageNotification([
            'candidate' => $user->toArray(),
            'resume' => $resume->toArray(),
            'job' => $job->toArray()
        ]));

        $user->notify(new RespondNotification([
            'user' => $user->toArray(),
            'customer' => $customer->toArray(),
            'job' => $job->toArray(),
            'message' => $message ?? null
        ]));

        return response()->json([
            'status' => true
        ]);
    }
}
