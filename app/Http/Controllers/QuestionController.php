<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    use ApiResponse;
    /**
     * Display all jobs
     *
     * @param Request $request
     * @return JsonResponse
     */
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
}
