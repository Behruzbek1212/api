<?php

namespace App\Http\Controllers;

use App\Http\Resources\CandidateGetOneResource;
use App\Http\Resources\CandidateOneResource;
use App\Http\Resources\CandidateResource;
use App\Models\Candidate;
use App\Models\Job;
use App\Models\User;
use App\Notifications\RespondMessageNotification;
use App\Services\CandidateServices;
use App\Traits\ApiResponse;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CandidatesController extends Controller
{
    use ApiResponse;
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
        $data = CandidateServices::getInstance()->all($request) ?? [];
        
        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    public function candidates(Request $request)
    {
        return $this->successResponse(CandidateResource::collection(CandidateServices::getInstance()->list($request)));
    }

    public function get_one_candidate($id)
    {
        return $this->successResponse(new CandidateOneResource(CandidateServices::getInstance()->one($id)));
    }

    //without limit
    public function get_one(Request $request,$id)
    {
        return $this->successResponse(new CandidateGetOneResource(CandidateServices::getInstance()->get_one($request,$id)));
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

    public function addTestResult(Request $request): JsonResponse
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

        foreach ($test as $testkey) {
            if ($testkey['quizGroup'] === $result['quizGroup']) {
                return response()->json([
                    'status' => false,
                    'data' => $candidate
                ]);
            }
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


    public function createTelegram(Request $request)
    {
        $request->validate([
          'chat_id' => 'integer|required'
        ]);

        $user = _auth()->user();
        if($user !== null){
        $candidate = $user->candidate()->firstOrFail();
       
       
            if($candidate->telegram_id == null) {
                $candidate->telegram_id = [$request->chat_id];
                $candidate->save();
                return response()->json([
                    'status' => true,
                    'message' => 'success'
                ]);
            }
            
            if (!in_array($request->chat_id, $candidate->telegram_id)) {
                $data =   $candidate->telegram_id;
                $data[] = $request->chat_id;
                $candidate->telegram_id = $data;
                $candidate->save();
            }
            
            return response()->json([
                'status' => true,
                'message' => 'success'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'not user '
        ]);
    }
}
