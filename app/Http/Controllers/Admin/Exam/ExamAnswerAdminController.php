<?php

namespace App\Http\Controllers\Admin\Exam;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExamAdmin\StoreAnswerAdminRequest;
use App\Http\Requests\ExamAdmin\UpdateAnswerAdminRequest;
use App\Models\Exam\AnswerVariant;
use App\Services\ExamAdmin\ExamAnswerAdminServices;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;

class ExamAnswerAdminController extends Controller
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
    public function store(StoreAnswerAdminRequest $request)
    {
        $request->validated();
        try {
            return $this->successResponse(ExamAnswerAdminServices::getInstance()->store($request));
        }catch (Exception $e){
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show( $variant)
    { 
        try {
            return $this->successResponse(AnswerVariant::find($variant));
        }catch (Exception $e){
            return $this->errorResponse($e->getMessage());
        }
    }

   

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnswerAdminRequest $request,  $variant)
    {
        try {
            return $this->successResponse(ExamAnswerAdminServices::getInstance()->update( $request, $variant));
        }catch (Exception $e){
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AnswerVariant $answerVariant)
    {  
        try {
            return $this->successResponse(ExamAnswerAdminServices::getInstance()->destroy($answerVariant));
        }catch (Exception $e){
            return $this->errorResponse($e->getMessage());
        }
    }
}
