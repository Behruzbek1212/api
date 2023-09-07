<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function all(Request $request): JsonResponse
    {
        $params = $request->validate([
            'limit' => ['integer', 'nullable']
        ]);

        $questions = Question::query()
            ->orderBy('position', 'ASC');

        return response()->json([
            'status' => true,
            'questions' => $questions->paginate($params['limit'] ?? null)
        ]);
    }


    public function create(Request $request): JsonResponse
    {
        $params = $request->validate([
            'question' => ['string', 'required'],
            'position' => ['numeric', 'nullable'],
        ]);

        $user = _auth()->user();

        Question::create([
            'created_by' => $user->id,
            'question' => $params['question'],
            'position' => $params['position'],
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Question successfully created',
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $question = Question::query()
            ->findOrFail($slug);
        return response()->json([
            'status' => true,
            'data' => $question
        ]);
    }

    public function edit(Request $request): JsonResponse
    {

        $params = $request->validate([
            'question' => ['string', 'required'],
            'position' => ['numeric', 'nullable'],
        ]);

        $question = Question::query()
            ->findOrFail($request->get('slug'));
        $user = _auth()->user();
        $question->update([
            'created_by' => $user->id,
            'question' => $params['question'],
            'position' => $params['position'],
        ]);

        return response()->json([
            'status' => true,
            'message' => []
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $question = Question::query()
            ->findOrFail($request->slug);

        $question->delete();
        return response()->json([
            'status' => true,
            'data' => []
        ]);
    }
}
