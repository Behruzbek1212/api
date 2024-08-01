<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResumeRequest;
use App\Http\Requests\UpdateResumeRequest;
use App\Models\Candidate;
use App\Models\Chat\Chat;
use App\Models\Resume;
use App\Models\TestResult;
use App\Models\User;
use App\Services\AdminResumeService;
use App\Services\AdminResumeWithTestsService;
use App\Services\ResumeService;
use eloquentFilter\QueryFilter\Queries\WhereHas;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JsonException;

class ResumeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        /** @var Authenticatable|User|null $user */
        $user = _auth()->user();


        return response()->json([
            'status' => true,
            'resumes' => $user->resumes,

        ]);
    }

    /**
     * Get resume information
     *
     * @param int $id
     * @return JsonResponse
     */
    public function get(int $id): JsonResponse
    {
        /** @var Authenticatable|User|null $user */
        $user = _auth()->user();

        $resume = $user->resumes()->findOrFail($id);
        return response()->json([
            'status' => true,
            'data' => $resume['data']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(StoreResumeRequest $request): JsonResponse
    {
        $request->validated();
        /** @var Authenticatable|User|null $user */
        $user = auth()->user();

        $user->resumes()->updateOrCreate([
            'data' => $request->toArray()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Ok'
        ]);
    }

    /**
     * Update data
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(UpdateResumeRequest $request): JsonResponse
    {
        $request->validated();
        /** @var Authenticatable|User|null $user */
        $user = _auth()->user();

        $user->resumes()->findOrFail($request->input('resume_id'))
            ->update([
                'data' => $request->input('data')
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Ok'
        ]);
    }

    public function updateStatus(Request $request): JsonResponse
    {
        $request->validate([
            'resume_id' => 'required|integer',
            'status' => 'required|in:active,no-active'
        ]);
        /** @var Authenticatable|User|null $user */
        $user = _auth()->user();

        $resume = $user->resumes()->findOrFail($request->input('resume_id'));

        $data = $resume->data;
        $data['status'] = $request->status ?? 'active'; // Update the status key within the data JSON

        $resume->update([
            'data' => $data, // Re-encode and update the entire data JSON column
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Ok'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string|int $id
     * @return JsonResponse
     */
    public function destroy(string|int $id): JsonResponse
    {
        /** @var Authenticatable|User $user */
        $user = _auth()->user();



         $resume = $user->resumes()->findOrFail($id);
         $resume->delete();
        $chats = Chat::query()->where('resume_id', $resume->id)->where('deleted_at', null)->get();

        if($chats !== null){
             foreach($chats as $chat){
                $chat->delete();
             }
        }

        return response()->json([
            'status' => true,
            'message' => 'Ok'
        ]);
    }

    /**
     * Display a resume.
     *
     * @param string|int $id
     *
     * @return Response
     * @throws JsonException
     */
    public function show(string|int $id): Response
    {
        $resume = Resume::query()
            ->with('user')
            ->findOrFail($id);

        $data = $resume->data;
        $candidate = $resume->user
            ->candidate;
        $experience = $resume -> experience;

        $experience = $resume->experience;

        $candidateTest = Candidate::with('user' , 'testResult')
                   ->whereHas('testResult', function ($query) {
                        $query->where('customer_id', null)
                            ->where('deleted_at', null);
                   })->find($candidate->id);

        $testResult =   $candidateTest->testResult ?? [];
        $resume_id = $id;

        $resume->increment('visits');

        return (new ResumeService)
            ->load(compact('data', 'candidate', 'testResult', 'resume_id', 'experience'))
            ->load(compact('data', 'candidate', 'testResult', 'resume_id', 'experience'))
            ->stream($candidate->name . '.pdf');
    }

    /**
     * Display a resume for admin.
     *
     * @param string|int $id
     *
     * @return Response
     * @throws JsonException
     */
    public function showForAdmin(string|int $id): Response
    {
        $resume = Resume::query()
            ->with('user')
            ->findOrFail($id);

        $data = $resume->data;
        $candidate = $resume->user
            ->candidate;
        $experience = $resume -> experience;

        $resume_id = $id;

        $resume->increment('visits');

        return (new AdminResumeService)
            ->load(compact('data', 'candidate', 'resume_id', 'experience'))
            ->stream($candidate->name . '.pdf');
    }


    /**
     * Download resume.
     *
     * @param string|int $id
     *
     * @return Response
     * @throws JsonException
     */
    public function download(string|int $id): Response
    {
        $resume = Resume::query()
            ->with('user')
            ->findOrFail($id);

        $data = $resume->data;
        $candidate = $resume->user
            ->candidate;

        $experience = $resume->experience;

        $candidateTest = Candidate::with(['user', 'testResult' => function ($query) {
            $query->latest();
        }])->find($candidate->id);

        $testResult =   $candidateTest->testResult ?? [];
        $resume_id = $id;

        $experience = $resume -> experience;

        $resume->increment('downloads');
        $resume->increment('visits');

        return (new ResumeService)
            ->load(compact('data', 'candidate', 'testResult', 'resume_id', 'experience'))
            ->download($candidate->name . '.pdf');
    }

    /**
     * Download resume for admin.
     *
     * @param string|int $id
     *
     * @return Response
     * @throws JsonException
     */
    public function downloadForAdmin(string|int $id): Response
    {
        $resume = Resume::query()
            ->with('user')
            ->findOrFail($id);

        $data = $resume->data;
        $candidate = $resume->user
            ->candidate;

        $resume_id = $id;

        $experience = $resume -> experience;

        $resume->increment('downloads');
        $resume->increment('visits');

        return (new AdminResumeService)
            ->load(compact('data', 'candidate', 'resume_id', 'experience'))
            ->download($candidate->name . '.pdf');
    }

    /**
     * Download resume for admin.
     *
     * @param string|int $id
     *
     * @return Response
     * @throws JsonException
     */
    public function downloadForAdminWithTests(string|int $id): Response
    {

        $resume = Resume::query()
            ->with('user')
            ->findOrFail($id);

        $data = $resume->data;
        $candidate = $resume->user
            ->candidate;

        $resume_id = $id;

        $experience = $resume -> experience;

        $resume->increment('downloads');
        $resume->increment('visits');

        return (new AdminResumeWithTestsService)
            ->load(compact('data', 'candidate', 'resume_id', 'experience'))
            ->download($candidate->name . '.pdf');
    }

    public function downloadtestCus(string|int $id,int $customer_id)
    {

        $candidate = Candidate::with('user' , 'testResult')
                   ->whereHas('testResult', function ($query) use ($customer_id) {
                        $query->where('customer_id', $customer_id)
                            ->where('deleted_at', null);
                   })->findOrFail($id);

        $testResult =  $candidate->testResult ?? [];

        $resume = Resume::where('user_id', $candidate->user->id)
                 ->where('deleted_at', null)
                 ->orderByDesc('updated_at')
                 ->first();

        $data = $resume->data ?? [];
        $resume_id = $resume->id ?? null;
        $experience = $resume->experience ?? null;


        return (new AdminResumeWithTestsService)
        ->load(compact('data', 'candidate', 'testResult', 'resume_id', 'experience'))
        ->download($candidate->name . '.pdf');
    }

}
