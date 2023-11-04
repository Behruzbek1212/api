<?php

namespace App\Http\Controllers\Admin\Exam;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExamAdmin\StoreExamAdminRequest;
use App\Http\Requests\ExamAdmin\UpdateExamAdminRequest as ExamAdminUpdateExamAdminRequest;
use App\Models\Exam;
use App\Services\ExamAdmin\ExamAdminServices;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;

class ExamAdminController extends Controller
{
    use ApiResponse;

    public function list()
    {
        try {
            return $this->successPaginate(ExamAdminServices::getInstance()->list());
        } catch (Exception $e){
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
    

    public function store(StoreExamAdminRequest $request)
    {
        $request->validated();
        try {
            return $this->successResponse(ExamAdminServices::getInstance()->store($request));
        }catch (Exception $e){
            return $this->errorResponse($e->getMessage());
        }
    }
    

    public function show(Exam $exam)
    {
        try {
            return $this->successResponse($exam);
        }catch (Exception $e){
            return $this->errorResponse($e->getMessage());
        }
    }

    public function showWithQuestion(Exam $exam)
    {
        try {
            return $this->successPaginate(ExamAdminServices::getInstance()->showWithQuestion($exam));
        }catch (Exception $e){
            return $this->errorResponse($e->getMessage());
        }
    }


    public function update(ExamAdminUpdateExamAdminRequest $request)
    {
        $request->validated();
        try {
            return $this->successResponse(ExamAdminServices::getInstance()->update($request));
        }catch (Exception $e){
            return $this->errorResponse($e->getMessage());
        }
    }

    public function destroy(Exam $exam)
    {
        try {
            return $this->successResponse(ExamAdminServices::getInstance()->delete($exam));
        }catch (Exception $e){
            return $this->errorResponse($e->getMessage());
        }
    }


}
