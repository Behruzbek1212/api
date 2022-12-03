<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Resume;
use App\Models\User;
use App\Notifications\RespondMessageNotification;
use App\Notifications\RespondNotification;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
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
        $jobs = Job::query()
            // Check if customer status is active
            ->with('customer')
            ->whereHas('customer', function (Builder $query) {
                $query->where('active', '=', true);
            });
            // TODO: ->where('status', '=', 'approved');

        if ($title = $request->get('title'))
            $jobs->where('title', 'like', '%' . $title . '%');

        if ($type = $request->get('type'))
            $jobs->where('type', 'like', '%' . $type . '%');

        if ($location_id = $request->get('location_id'))
            $jobs->where('location_id', '=', $location_id);

        /** @see https://laravel.com/docs/9.x/queries#json-where-clauses */
        if ($salary = $request->get('salary'))
            $jobs->where('salary->amount', 'like', '%' . $salary . '%');

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
            ->whereHas('customer', function (Builder $query) {
                $query->where('active', '=', true);
            })
            // TODO: ->where('status', '=', 'approved')
            ->findOrFail($slug);

        return response()->json([
            'status' => true,
            'job' => $job
        ]);
    }

    public function respond(Request $request): JsonResponse
    {
        $params = $request->validate([
            'resume_id' => ['required', 'numeric'],
            'job_slug' => ['required', 'string'],
            'message' => ['nullable', 'string']
        ]);

        /** @var Authenticatable|User $user */
        $user = _auth()->user();

        $resume = Resume::query()->findOrFail($request->input('resume_id'));
        $job = Job::query()->findOrFail($request->input('job_slug'));

        $job->chats()->create([
            'job_slug' => $params['job_slug'],
            'resume_id' => $params['resume_id'],
            'customer_id' => $job->customer->id,
            'candidate_id' => $user->id,
            'status' => 'review'
        ]);

        $job->customer->user->notify(new RespondMessageNotification([
            'candidate' => $user->toArray(),
            'resume' => $resume->toArray(),
            'job' => $job->toArray()
        ]));

        $user->notify(new RespondNotification([
            'user' => $user->toArray(),
            'customer' => $job->customer->toArray(),
            'job' => $job->toArray(),
            'message' => $params['message'] ?? null
        ]));

        return response()->json([
            'status' => true
        ]);
    }

    /**
     * Create vacancy
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $params = $request->validate([
            'position' => ['string', 'required'],
            'location' => ['numeric', 'required'],
            'experience' => ['string', 'required'],
            'salary' => ['array:amount,currency,agreement', 'required'],
            'work_type' => ['string', 'required', 'in:fulltime,remote,partial,hybrid'],
            'about' => ['string', 'required'],
        ]);

        $request->user()->customer->jobs()->create([
            'title' => $params['position'],
            'type' => $params['position'],
            'salary' => $params['salary'],
            'about' => $params['about'],
            'work_type' => $params['work_type'],
            'experience' => $params['experience'],
            'location_id' => $params['location'],
            'slug' => null
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Job successfully created',
        ]);
    }

    /**
     * Update vacancy
     *
     * @param Request $request
     * @param string $slug
     * @return JsonResponse
     */
    public function edit(Request $request, string $slug): JsonResponse
    {
        $params = $request->validate([
            'position' => ['string', 'required'],
            'location' => ['numeric', 'required'],
            'experience' => ['string', 'required'],
            'salary' => ['array:amount,currency,agreement', 'required'],
            'work_type' => ['string', 'required', 'in:fulltime,remote,partial,hybrid'],
            'about' => ['string', 'required'],
        ]);

        $job = $request->user()->customer->jobs()->findOrFail($slug);
        $job->update([
            'title' => $params['position'],
            'type' => $params['position'],
            'salary' => $params['salary'],
            'about' => $params['about'],
            'work_type' => $params['work_type'],
            'experience' => $params['experience'],
            'location_id' => $params['location'],
            'status' => 'moderating'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Successfully updated'
        ]);
    }

    /**
     * Destroy vacancy
     *
     * @param Request $request
     * @param string $slug
     * @return JsonResponse
     */
    public function destroy(Request $request, string $slug): JsonResponse
    {
        $job = $request->user()->customer->jobs()->findOrFail($slug);
        $job->delete();

        return response()->json([
            'status' => true,
            'message' => 'Successfully deleted'
        ]);
    }
}
