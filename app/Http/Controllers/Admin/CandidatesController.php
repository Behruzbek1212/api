<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\User;
use App\Services\MobileService;
use Carbon\Carbon;
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
         $sortBy = $request->sortBy ?? null;
         $sortType = $request->sortType ??  null;
        $candidates = Candidate::query()
            ->withTrashed()
            ->whereHas('user', fn (Builder $query) => $query->where('role', '=', 'candidate'))
            ->where('active', '=', true)
            ->with(['user', 'user.resumes']);


        if ($request->has('title'))
            $candidates->where(function (Builder $query) use ($request) {
                $query->where('name', 'like', '%' . $request->get('title') . '%');
                $query->orWhere('surname', 'like', '%' . $request->get('title') . '%');
                $query->orWhere('specialization', 'like', '%' . $request->get('title') . '%');
                $query->orWhereHas('user', function ($query) use ($request) {
                    $query->where('phone', 'like', '%' . $request->get('title') . '%');
                });
            });

        /** @see https://laravel.com/docs/9.x/queries#json-where-clauses */
        if ($sphere = $request->get('sphere'))
            $candidates->whereJsonContains('spheres', $sphere);

        if ($sortBy !== null && $sortType !== null) {
            $candidates->orderBy($sortBy, $sortType);
        } else {
            $candidates->orderByDesc('created_at', 'updated_at');
        }

        $candidates = $candidates->paginate($request->limit ?? 10);
        $_data = $candidates->makeVisible(['__comment', '__conversation', '__conversation_date']);

        return response()->json([
            'status' => true,
            'data' => array_merge(
                $candidates->toArray(),
                ['data' => $_data]
            )
        ]);
    }

    public function create(Request $request): JsonResponse
    {
        $this->validateParams($request, []);
        $password = Random::generate();

        try {
            $user = User::query()->create(array_merge(
                $request->only(['phone', 'email']),
                ['password' => Hash::make($password)],
                ['role' => 'candidate']
            ));

            $candidate =  $user->candidate()->create(array_merge(
                $request->except([ 'phone', 'email' ]),
                ['__conversation_date' => $request->get('__conversation') ? date('Y-m-d') : null],
                ['avatar' => $request->get('avatar') ?? null],
//                ['__comment' => $request->get('__comment') ?? null],
                ['active' => true]
            ));

            $candidate->__comment = $request->get('__comment') ?? null;
            $candidate->save();
        } catch (QueryException $exception) {
            return response()->json([
                'status' => false,
                'error' => true,
                'message' => $exception->getMessage()
            ]);
        }

        (new MobileService())->send(
            $request->get('phone'),
            "Sizning JOBO.uz ga kirish parolingiz: " . $password .
            "\nQuyidagi link orqali tezkor kirishni amalga oshirishingiz mumkin: " .
            vsprintf("https://jobo.uz/auth/verifier/%s/?p=%s", [$password, $request->get('phone')])
        );

        return response()->json([
            'status' => true,
            'password' => $password,
            'candidate_id' =>  $candidate->id,
            'user_id'=> $user->id
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $candidate = Candidate::query()
            ->withTrashed()
            ->whereHas('user', fn (Builder $query) => $query->where('role', '=', 'candidate'))
            ->where('active', '=', true)
            ->with(['user', 'user.resumes'])
            ->findOrFail($id)
            ->makeVisible(['__comment', '__conversation', '__conversation_date']);

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
            $request->except(['id', 'phone', 'email']),
            ['avatar' => $request->get('avatar') ?? null]
        ));

        $candidate->__conversation = $request->get('__conversation');
        $candidate->__conversation_date = $request->get('__conversation') ? Carbon::now() : null;
        $candidate->save();

        $candidate->user()->update(array_merge(
            $request->only(['phone', 'email'])
        ));

        return response()->json([
            'status' => true,
            'message' => []
        ]);
    }

    /**
     * Update candidate services data's
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateServices(Request $request): JsonResponse
    {
        $candidate = Candidate::query()
            ->withTrashed()
            ->whereHas('user', fn (Builder $query) => $query->where('role', '=', 'candidate'))
            ->where('active', '=', true)
            ->findOrFail($request->get('id'));

        $services = [
            'resume' => $request->get('resume') ?? false,
            'conversation' => $request->get('conversation') ?? false,
        ];

        $candidate->update(['services' => $services]);

        return response()->json([
            'status' => true,
            'message' => 'Candidate services successfully updated'
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

        if (!$candidate->trashed())
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
            'languages' => ['array', 'nullable'] ,
            'specialization' => ['string', 'required'],
            'birthday' => ['date', 'required'],
            'address' => ['numeric', 'required'],
            'phone' => ['numeric', 'unique:users,phone', 'required'],
            'email' => ['email', 'unique:users,email', 'required'],
            'services' => ['json', 'nullable'],
            'test' => ['array', 'nullable'],
            '__comment' => ['string', 'nullable'],
            '__conversation' => ['boolean', 'required'],
            '__conversation_date' => ['date', 'nullable']
        ], $rule));
    }
}
