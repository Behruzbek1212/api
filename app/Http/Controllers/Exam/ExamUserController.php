<?php
namespace App\Http\Controllers\Exam;

use App\Http\Resources\Exam\ExamUserResource;
use App\Http\Controllers\Controller;
use App\Models\SubjectTopicResource\CurriculumSubjectEployee;
use App\Services\Exam\ExamUserServices;
use App\Services\ExamSchedule\ExamStudentServices;
use App\Traits\ApiResponse;

class ExamUserController extends Controller {

    use ApiResponse;

    public function index()
    {
        return $this->successResponse(ExamUserResource::collection(ExamUserServices::getInstance()->list()));
    }

}
