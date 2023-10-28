<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Services\Exam\ExamServices;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function all()
    {
        $exams = Exam::get();
        return response()->json([
            'status' => true,
            'result' => $exams
        ]);
    }

    public function add(Request $request)
    {
        return ExamServices::getInstance()->add($request);
    }
}
