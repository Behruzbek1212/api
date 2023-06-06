<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Job;
use App\Models\User;
use App\Notifications\RespondMessageNotification;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CandidatesController extends Controller
{
    /**
     * Get candidates list
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function all(Request $request): JsonResponse
    {
        $params = $request->validate([
            'limit' => ['integer', 'nullable']
        ]);

        $candidates = Candidate::query()
            ->with(['user:id,email,phone,verified', 'user.resumes'])
            ->orderByDesc('id')
            ->whereHas('user', function (Builder $query) {
                $query->where('role', '=', 'candidate');
            })
            ->where('active', '=', true);

        if ($name = $request->get('name'))
            $candidates->where(function (Builder $query) use ($name) {
                $query->where('name', 'like', '%'.$name.'%');
                $query->orWhere('surname', 'like', '%'.$name.'%');
            });

        if ($title = $request->get('title'))
            $candidates->whereHas('user.resumes', function (Builder $query) use ($title) {
                $query->whereRaw(
                    'lower(json_unquote(json_extract(`data`, \'$."position"\'))) like ?',
                    ['%'.strtolower($title).'%']
                );
            });

        /** @see https://laravel.com/docs/9.x/queries#json-where-clauses */
        if ($sphere = $request->get('sphere'))
            $candidates->whereJsonContains('spheres', $sphere);

        return response()->json([
            'status' => true,
            'data' => $candidates->paginate($params['limit'] ?? null)
        ]);
    }

    /**
     * Find candidate with slug
     *
     * @param int $id
     * @return JsonResponse
     */
    public function get(int $id): JsonResponse
    {
        $candidate = Candidate::query()
            ->with(['user:id,email,phone,verified', 'user.resumes'])
            ->whereHas('user', function (Builder $query) {
                $query->where('role', '=', 'candidate');
            })
            ->where('active', '=', true)
            ->where('id', '=', $id)
            ->firstOrFail();

        _auth()->check() && _user()->candidateStats()
            ->syncWithoutDetaching($candidate);

        return response()->json([
            'status' => true,
            'data' => $candidate
        ]);
    }

     /**
     * add candidates test result
     *
     * @param Request $request
     * @return JsonResponse
     */

    public function addTestResult(Request $request):JsonResponse
    {
        $params = $request->validate([
            'candidate_id' => ['numeric', 'required']
        ]);
        $result = $request->get('result');

        $candidate = Candidate::query()->findOrFail($params['candidate_id']);

        // $candidate -> update([
        //     'test' => $request->get('result')
        // ]);

        // Make a copy of the test attribute
        $test = $candidate->test;

        // Initialize test attribute to empty array if it is null
        if ($test === null) {
            $test = [];
        }

        // Add new test result to the end of the test array
        $test[] = $result;

        // Set the modified test attribute back to the model
        $candidate->test = $test;

        // Save changes to database
        $candidate->save();

        return response()->json([
            'status' => true,
            'data' => $candidate
        ]);
    }

    public function respond(Request $request): JsonResponse
    {
        $params = $request->validate([
            'candidate_id' => ['numeric', 'required'],
            'job_slug' => ['required', 'string'],
            'message' => ['string', 'nullable']
        ]);

        /** @var Authenticatable|User|null $user */
        $user = _auth()->user();

        $job = Job::query()->findOrFail($params['job_slug']);
        $candidate = Candidate::query()->findOrFail($params['candidate_id']);

        $job->chats()->create([
            'job_slug' => $params['job_slug'],
            'candidate_id' => $params['candidate_id'],
            'customer_id' => $user->id,
            'status' => 'approve'
        ]);

        $candidate->user->notify(new RespondMessageNotification([
            'user' => $user->toArray(),
            'customer' => $job->customer->toArray(),
            'job' => $job->toArray(),
            'message' => $params['message'] ?? null
        ]));

        return response()->json([
            'status' => true
        ]);
    }
}
