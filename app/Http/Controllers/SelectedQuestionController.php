<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnswerResource;
use App\Http\Resources\JobResource;
use App\Models\Answer;
use App\Models\Candidate;
use App\Models\Question;
use App\Models\SelectedQuestion;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SelectedQuestionController extends Controller
{
    use ApiResponse;
    /**
     * Display all jobs
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function all($slug): JsonResponse
    {
        $questions = SelectedQuestion::query()
            ->where('job_slug', $slug)->firstOrFail();

        return response()->json([
            'status' => true,
            'questions' => $questions
        ]);
    }


    public function create(Request $request): JsonResponse
    {

        // dd($request->answers);
        $user = _auth()->user();
        $request->validate([
            'job_slug' => ['string', 'required'],
            // 'question_id' => ['numeric', 'required'],
            // 'answer' => ['string'],
        ]);

        // dd($user->id);
        $candidate_id = Candidate::where('user_id', $user->id)->firstOrFail()->id;

        foreach ($request->answers as $value) {
            Answer::create([
                'job_slug' => $request->job_slug,
                'question_id' => $value['question_id'] ?? null,
                'candidate_id' => $candidate_id,
                'answer' =>  $value['answer'] ?? null,
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Answer successfully created',
        ]);
    }


    public function job_answer($slug): JsonResponse
    {

        $answers = Answer::query()
            ->where('job_slug', $slug)->get();

        $jobAnswers = AnswerResource::collection($answers);

        return response()->json([
            'status' => true,
            'answers' => $jobAnswers
        ]);
    }
}
