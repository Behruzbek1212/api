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
            ->where('job_slug', $slug)->get();

        return response()->json([
            'status' => true,
            'questions' => $questions
        ]);
    }


    public function create(Request $request): JsonResponse
    {

        $params = $request->validate([
            'job_slug' => ['string', 'required'],
            'question_id' => ['numeric', 'required'],
            'answer' => ['string'],
        ]);

        $user = _auth()->user();
        // dd($user->id);
        $candidate_id = Candidate::where('user_id', $user->id)->firstOrFail()->id;
        Answer::create([
            'job_slug' => $params['job_slug'],
            'question_id' => $params['question_id'],
            'candidate_id' => $candidate_id,
            'answer' => $request->answer,
        ]);

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
