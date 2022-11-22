<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
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
        $candidates = Candidate::query()
            ->with(['user:id,email,phone,verified', 'user.resumes'])
            ->whereHas('user', function (Builder $query) {
                $query->where('role', '=', 'candidate');
            })
            ->where('active', '=', true);

        if ($name = $request->get('name'))
            $candidates->where(function (Builder $query) use ($name) {
                $query->where('name', 'like', '%'.$name.'%')
                    ->orWhere('surname', 'like', '%'.$name.'%');
            });

        /** @see https://laravel.com/docs/9.x/queries#json-where-clauses */
        if ($sphere = $request->get('sphere'))
            $candidates->whereJsonContains('spheres', $sphere);

        if ($limit = $request->get('limit'))
            $candidates->limit($limit);

        return response()->json([
            'status' => true,
            'data' => $candidates->get()
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

        return response()->json([
            'status' => true,
            'data' => $candidate
        ]);
    }
}
