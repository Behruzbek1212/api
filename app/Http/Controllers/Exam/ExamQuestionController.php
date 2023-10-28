<?php

namespace App\Http\Controllers\Exam;


use App\Http\Controllers\Controller;
use App\Services\Exam\ExamQuestionServices;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ExamQuestionController extends Controller
{

    use ApiResponse;

    public function index(Request $request)
    {

        return ExamQuestionServices::getInstance()->list($request);
    }

    public function add(Request $request)
    {
        return ExamQuestionServices::getInstance()->add($request);
    }

    public function finish(Request $request)
    {
        return ExamQuestionServices::getInstance()->finish($request);
    }
}
