<?php

namespace App\Http\Controllers;

use App\Http\Resources\CandidateExamResource;
use App\Models\Candidate;
use App\Models\CandidateExam;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CandidateExamController extends Controller
{


    public function list(Request $request)
    {
        $params = $request->validate([
            'customer_id' => ['numeric', 'required']
        ]);
        $user = _auth()->user();

        $candidate_id = Candidate::where('user_id', $user->id)->first()->id;
        $candidate_exams = CandidateExam::with('exams')
            ->where('id', $request->candidate_exam_id)
            ->where('customer_id', $request->customer_id)->first();
        // $list = CandidateExamResource::collection($candidate_exams);
        return response()->json([
            'status' => true,
            'data' => $candidate_exams ?? []
        ]);
    }


    public function create(Request $request): JsonResponse
    {

        $params = $request->validate([
            'candidate_id' => ['numeric', 'nullable'],
            'customer_id' => ['numeric', 'required'],
            'exams' => ['array', 'required'],
        ]);

        $candidate_exams = new CandidateExam();
        $candidate_exams->candidate_id = $params['candidate_id'] ?? null;
        $candidate_exams->customer_id =  $params['customer_id'] ?? null;
        $candidate_exams->save();
        foreach ($params['exams'] ?? [] as $value) {
            $candidate_exams->exams()->attach($value['exam_id']);
        }
        return response()->json([
            'status' => true,
            'candidate_exam_id' => $candidate_exams->id ?? []
        ]);
    }
}
