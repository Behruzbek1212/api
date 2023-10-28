<?php

namespace App\Services\Exam;

use App\Filters\ExamFilter;
use App\Models\Exam;
use App\Models\Exam\ExamUser;
use App\Repository\Exam\ExamRepository;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Builder;

class ExamServices
{
    use ApiResponse;
    public $repository;

    public function __construct(ExamRepository $data)
    {
        $this->repository = $data;
    }

    public static function getInstance(): ExamServices
    {
        return new static(ExamRepository::getInctance());
    }

    // public function list($request)
    // {
    //     $filter = new ExamFilter($request);
    //     return $this->repository->list(function (Builder $builder) use ($filter) {
    //         return $builder
    //             ->where('status', 1)
    //             ->withWhereHas('academicGroupExam', function ($query) {
    //                 $query->where('academic_group_id', studentAcademicGroupId());
    //             })
    //             ->filter($filter)
    //             ->withWhereHas('examStudent', function ($query) {
    //                 $query->where('student_id', studentId())
    //                     ->where('status', 1);
    //             })
    //             ->with(['subject', 'examType', 'employee', 'examStudent']);
    //     });
    // }

    public function add($request)
    {
        $examUser = ExamUser::where('exam_id', request('exam_id'))
            ->where('user_id', user()->id)
            ->first();
        $exam = Exam::where('id', request('exam_id'))
            ->where('attemps_count', '>', intval($examUser->attempt ?? 0))
            // ->where('datetime_start', '<', date('Y-m-d H:i:s'))
            // ->where('datetime_end', '>', date('Y-m-d H:i:s'))
            ->first();
        if (empty($exam)) {
            return $this->errorResponse(__('message.Test_dined'), 403);
        }


        // if (isset($examStudent) && $examStudent->key == ExamStudent::EXAM_STUDENT_END) {
        //     foreach ($examStudent->studentAnswers as $studentAnswer) {
        //         $studentAnswer->delete();
        //     }
        // }

        $examUser = ExamUser::updateOrCreate(
            [
                'exam_id' => $request->exam_id,
                'user_id' => user()->id,
            ],
            [
                'exam_id' => $request->exam_id,
                'user_id' => user()->id,
                // 'datetime_start' => ($examUser->key == ExamUser::EXAM_USER_END) ? date('Y-m-d H:i:s') : $examUser->datetime_start,
                'datetime_start' => date('Y-m-d H:i:s'),
                // 'status' => $request->status ?? true,
                'key' => ExamUser::EXAM_USER_START,
            ]
        );

        return [
            'exam_duration' => $exam->duration ?? null,
            'exam_start' => $examUser->datetime_start,
        ];
    }


    // public function exportExam($request)
    // {
    //     $examStudent = ExamStudent::where('student_id', studentId())
    //         ->where('exam_id', $request->exam_id)->first();
    //     $studentAnswers = $this->studentAnswer($examStudent);
    //     $dateStart = formatDateTime($examStudent->datetime_start);
    //     $dateEnd = formatDateTime($examStudent->datetime_end);
    //     $count = $examStudent->studentAnswerTrue->count();

    //     // return view('exam', compact('studentAnswers', 'examStudent', 'dateStart', 'dateEnd', 'count'));

    //     $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exam', [
    //         'studentAnswers' => $studentAnswers,
    //         'examStudent' => $examStudent,
    //         'dateStart' => $dateStart,
    //         'dateEnd' => $dateEnd,
    //         'count' => $count,
    //     ]);
    //     return $pdf->download('exam.pdf');
    // }


    // public function studentAnswer($receiver)
    // {
    //     return StudentAnswer::where('exam_student_id', $receiver->id)->get();
    // }


    // public function examCheck()
    // {
    //     $examStudent = ExamStudent::where('exam_id', request('exam_id'))
    //         ->where('academic_group_id', studentAcademicGroupId())
    //         ->where('student_id', studentId())
    //         ->first();
    //     if ($examStudent == null) {
    //         return $this->errorResponse(__('message.Test_dined'), 403);
    //     }

    //     $exam = Exam::where('id', request('exam_id'))
    //         ->where('attemps_count', '>', intval($examStudent->attempt ?? 0))
    //         ->where('datetime_start', '<', date('Y-m-d H:i:s'))
    //         ->where('datetime_end', '>', date('Y-m-d H:i:s'))
    //         ->first();
    //     if (empty($exam)) {
    //         return $this->errorResponse(__('message.Test_dined'), 403);
    //     }

    //     return $this->successResponse(__('message.Successfull'));
    // }
}
