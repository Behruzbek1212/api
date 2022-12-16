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

        $chat = $job->chats()->create([
            'job_slug' => $params['job_slug'],
            'resume_id' => $params['resume_id'],
            'customer_id' => $job->customer->id,
            'candidate_id' => $user->candidate->id,
            'status' => 'review'
        ]);

        @$params['message'] && $job->chats()->find($chat->id)->messages()->create([
            'message' => $params['message'],
            'role' => $user->role
        ]);

        $job->customer->user->notify(new RespondMessageNotification([
            'candidate' => $user->toArray(),
            'resume' => $resume->toArray(),
            'job' => $job->toArray()
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
            'slug' => null,
            'status' => 'approved'
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
            'status' => 'approved'
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

    /**
     * Description
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function acceptance(Request $request): JsonResponse
    {
        $params = $request->validate([
            'job_slug' => ['string', 'required'],
            'candidate_id' => ['numeric', 'required'],
            'status' => ['string', 'in:approve,reject', 'required'],
            'message' => ['string', 'nullable']
        ]);

        /** @var Authenticatable|User $user */
        $user = _auth()->user();
        $chat = $user->customer->chats()->where(function (Builder $query) use ($params) {
            $query->where('candidate_id', '=', $params['candidate_id']);
            $query->where('job_slug', '=', $params['job_slug']);
        })->firstOrFail();

        $chat->update([
            'status' => $params['status']
        ]);

        @$params['message'] && $chat->messages()->create([
            'message' => $params['message']
        ]);

        $chat->candidate->user->notify(new RespondMessageNotification([
            'candidate' => $chat->candidate->toArray(),
            'customer' => $user->customer->toArray(),
            'job' => $chat->job->toArray(),
            'message' => $params['message'] ?? null
        ]));

        return response()->json([
            'status' => true
        ]);
    }

    /**
     * Get applications list
     *
     * @param string $slug
     * @return JsonResponse
     */
    public function applications(string $slug): JsonResponse
    {
        $job = Job::query()->findOrFail($slug);

        $data = $job->chats()->with('candidate')->get()->makeHidden([
            'id', 'job_slug', 'resume_id',
            'customer_id', 'candidate_id',
            'status', 'created_at', 'updated_at',
            'customer', 'job'
        ]);

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }
}
