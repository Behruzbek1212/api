<?php

namespace App\Http\Controllers\Admin\Exam;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExamAdmin\StoreQuestionAdminRequest;
use App\Http\Requests\ExamAdmin\UpdateQuestionAdminRequest;
use App\Models\Exam\Question;
use App\Services\ExamAdmin\ExamQuestionsAdminServices;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;

class ExamQuestionAdminController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuestionAdminRequest $request)
    {
        $request->validated();
        try {
            return $this->successResponse(ExamQuestionsAdminServices::getInstance()->store($request));
        }catch (Exception $e){
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        try {
            return $this->successResponse($question);
        }catch (Exception $e){
            return $this->errorResponse($e->getMessage());
        }
    }
    public function showWithAnswer(Question $question)
    {
        try {
            return $this->successResponse($question->answerVariants()->get());
        }catch (Exception $e){
            return $this->errorResponse($e->getMessage());
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuestionAdminRequest $request, Question $question)
    {
        
        try {
            return $this->successResponse(ExamQuestionsAdminServices::getInstance()->update( $request, $question));
        }catch (Exception $e){
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        try {
            return $this->successResponse(ExamQuestionsAdminServices::getInstance()->destroy($question));
        }catch (Exception $e){
            return $this->errorResponse($e->getMessage());
        }
    }
}
