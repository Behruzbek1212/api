<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\User;
use App\Services\MobileService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Random;

class CandidatesController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $candidates = Candidate::query()
            ->withTrashed()
            ->whereHas('user', fn (Builder $query) => $query->where('role', '=', 'candidate'))
            ->where('active', '=', true)
            ->with(['user', 'user.resumes'])
            ->orderByDesc('updated_at');

        if ($request->has('title'))
            $candidates->where(function (Builder $query) use ($request) {
                $query->where('name', 'like', '%'.$request->get('title').'%');
                $query->orWhere('surname', 'like', '%'.$request->get('title').'%');
            });

        /** @see https://laravel.com/docs/9.x/queries#json-where-clauses */
        if ($sphere = $request->get('sphere'))
            $candidates->whereJsonContains('spheres', $sphere);

        return response()->json([
            'status' => true,
            'data' => $candidates->paginate(20)->makeVisible([
                '__comment',
                '__conversation',
                '__conversation_date'
            ])
        ]);
    }

    public function create(Request $request): JsonResponse
    {
        $this->validateParams($request, []);
        $password = Random::generate();

        try {
            $user = User::query()->create(array_merge(
                $request->only([ 'phone', 'email' ]),
                ['password' => Hash::make($password)],
                ['role' => 'candidate']
            ));

            $user->candidate()->create(array_merge(
                $request->except([ 'phone', 'email' ]),
                ['__conversation_date' => $request->get('__conversation') ? date('Y-m-d') : null],
                ['avatar' => $request->get('avatar') ?? null],
                ['active' => true]
            ));
        } catch (QueryException $exception) {
            return response()->json([
                'status' => false,
                'error' => true,
                'message' => $exception->getMessage()
            ]);
        }

        (new MobileService())->send(
            $request->get('phone'),
            'Sizning JOBO.uz ga kirish parolingiz: ' . $password
        );

        return response()->json([
            'status' => true,
            'password' => $password,
            'data' => []
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $candidate = Candidate::query()
            ->withTrashed()
            ->with(['user' => function (BelongsTo $query) {
                $query->where('role', '=', 'candidate');
            }, 'user.resumes'])
            ->where('active', '=', true)
            ->findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => $candidate
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        $this->validateParams($request, [
            'id' => ['integer', 'required'],
            'phone' => ['numeric', 'required'],
            'email' => ['email', 'required']
        ]);

        $candidate = Candidate::query()
            ->withTrashed()
            ->findOrFail($request->get('id'));

        $candidate->update(array_merge(
            $request->except( ['id', 'phone', 'email'] ),
            ['avatar' => $request->get('avatar') ?? null]
        ));

        $candidate->user()->update(array_merge(
            $request->only([ 'phone', 'email' ])
        ));

        return response()->json([
            'status' => true,
            'message' => []
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $params = $request->validate([
            'id' => ['integer', 'required']
        ]);

        $candidate = Candidate::query()
            ->withTrashed()
            ->findOrFail($params['id']);

        if (! $candidate->trashed())
            $candidate->delete();

        return response()->json([
            'status' => true,
            'data' => []
        ]);
    }

    /**
     * Validate request params
     *
     * @param Request $request
     * @param array $rule
     * @return void
     */
    private function validateParams(Request $request, array $rule): void
    {
        $request->validate(array_merge([
            'name' => ['string', 'required'],
            'surname' => ['string', 'required'],
            'sex' => ['string', 'in:male,female', 'nullable'],
            'spheres' => ['array', 'nullable'],
            'education_level' => ['string', 'nullable'],
            'languages' => ['array', 'required'],
            'specialization' => ['string', 'required'],
            'birthday' => ['date', 'required'],
            'address' => ['numeric', 'required'],
            'phone' => ['numeric', 'unique:users,phone', 'required'],
            'email' => ['email', 'unique:users,email', 'required'],

            '__comment' => ['string', 'nullable'],
            '__conversation' => ['boolean', 'required'],
            '__conversation_date' => ['date', 'nullable']
        ], $rule));
    }
}
