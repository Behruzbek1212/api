<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use App\Models\User;
use App\Services\AdminResumeService;
use App\Services\AdminResumeWithTestsService;
use App\Services\ResumeService;
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
    public function store(Request $request): JsonResponse
    {
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
    public function update(Request $request): JsonResponse
    {
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

        $user->resumes()
            ->findOrFail($id)
            ->delete();

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
    public function show(string|int $id)
    {
        $resume = Resume::query()
            ->with('user')
            ->findOrFail($id);

        $data = $resume->data;
        $candidate = $resume->user
            ->candidate;

        $experience = $resume->experience;

        $resume_id = $id;

        $resume->increment('visits');

        return (new ResumeService)
            ->load(compact('data', 'candidate', 'resume_id', 'experience'))
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

        $resume_id = $id;

        $resume->increment('downloads');
        $resume->increment('visits');

        return (new ResumeService)
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
}
