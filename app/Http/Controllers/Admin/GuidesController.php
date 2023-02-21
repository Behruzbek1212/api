<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guide;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GuidesController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $guides = Guide::query()
            ->withTrashed();

        if ($request->has('title'))
            $guides->where(function (Builder $query) use ($request) {
                $query->where('title_uz', 'like', '%'.$request->get('title').'%');
                $query->orWhere('title_ru', 'like', '%'.$request->get('title').'%');
                $query->orWhere('title_en', 'like', '%'.$request->get('title').'%');
            });

        return response()->json([
            'status' => true,
            'data' => $guides->paginate(20)
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $guide = Guide::query()
            ->withTrashed()
            ->findOrFail($slug);

        return response()->json([
            'status' => true,
            'data' => $guide
        ]);
    }

    public function create(Request $request): JsonResponse
    {
        $this->validateParams($request, []);

        try {
            Guide::query()->create(array_merge(
                $request->only(['background', 'role']),
                $request->only(['content_uz', 'content_ru', 'content_en']),
                $request->only(['title_uz', 'title_ru', 'title_en']),
                ['button' => '{}', 'slug' => Str::slug($request->get('title_uz'))]
            ));
        } catch (QueryException $exception) {
            return response()->json([
                'status' => false,
                'error' => true,
                'message' => $exception->getMessage()
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => []
        ]);
    }

    public function edit(Request $request): JsonResponse
    {
        $this->validateParams($request, [
            'slug' => ['string', 'required']
        ]);

        $guide = Guide::query()
            ->findOrFail($request->get('slug'));

        $guide->update(array_merge(
            $request->only(['background', 'role']),
            $request->only(['content_uz', 'content_ru', 'content_en']),
            $request->only(['title_uz', 'title_ru', 'title_en']),
            ['button' => '{}']
        ));

        return response()->json([
            'status' => true,
            'data' => []
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $params = $request->validate([
            'slug' => ['string', 'required']
        ]);

        $guide = Guide::query()
            ->findOrFail($params['slug']);

        $guide->delete();

        return response()->json([
            'status' => true,
            'data' => []
        ]);
    }

    private function validateParams(Request $request, array $rule): void
    {
        $request->validate(array_merge([
            'background' => ['json', 'required'],
            'role' => ['in:all,customer,candidate', 'required'],

            // Content
            'content_uz' => ['string', 'required'],
            'content_ru' => ['string', 'required'],
            'content_en' => ['string', 'required'],

            // Title
            'title_uz' => ['string', 'required'],
            'title_ru' => ['string', 'required'],
            'title_en' => ['string', 'required'],
        ], $rule));
    }
}
