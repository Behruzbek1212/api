<?php

namespace App\Services\Exam;

use App\Http\Resources\Exam\ExamQuestionResource;
use App\Models\Exam;
use App\Models\Exam\AnswerVariant;
use App\Models\Exam\ExamUser;
use App\Models\Exam\UserAnswer;
use App\Repository\Exam\ExamQuestionRepository;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response;

class ExamQuestionServices
{
    use ApiResponse;
    public $repository;
    protected $list_answers = [];

    public function __construct(ExamQuestionRepository $data)
    {
        $this->repository = $data;
    }

    public static function getInstance(): ExamQuestionServices
    {
        return new static(ExamQuestionRepository::getInctance());
    }

    public function list()
    {
        $exam = Exam::where('id', request('exam_id'))
            ->withWhereHas('examUser', function ($query) {
                $query->where('user_id', user()->id);
            })
            // ->where('datetime_start', '<', date('Y-m-d H:i:s'))
            // ->where('datetime_end', '>', date('Y-m-d H:i:s'))
            ->first();
        if (empty($exam))
            return $this->errorResponse(__('message.Test_dined'), 403);
        $examStudent = ExamUser::where('exam_id', request('exam_id'))
            ->where('user_id', user()->id)
            ->where('attempt', '<', $exam->attemps_count)
            ->first();
        if (empty($examStudent))
            return $this->errorResponse(__('message.Test_dined'), 403);

        $examList = $this->repository->getQuestion(function (Builder $builder) use ($exam) {
            return $builder->where('exam_id', $exam->id)
                ->with(['question']);
        });
        if ($examList?->count() < 1) {
            return $this->errorResponse(__('message.Test_dined'), 403);
        }
        // if ($examStudent->key == ExamUser::EXAM_USER_START) {
        // return ExamQuestionResource::collection($examList);
        // }
        return ExamQuestionResource::collection($examList->random($exam->questions_count));
    }

    public function add($request)
    {
        // dd($request['answers']);
        $examUser = ExamUser::where('id', request('exam_user_id'))
            ->where('user_id', user()->id)
            ->first();

        if (empty($examUser))
            return $this->errorResponse(__('message.Test_dined'), 403);

        $exam = Exam::where('id', $examUser->exam_id)
            ->where('attemps_count', '>', intval($examUser->attempt))
            // ->where('datetime_start', '<', date('Y-m-d H:i:s'))
            // ->where('datetime_end', '>', date('Y-m-d H:i:s'))
            ->first();
        if (empty($exam))
            return $this->errorResponse(__('message.Test_dined'), 403);

        $answerVariant = AnswerVariant::where('id', $request->answer_variant_id)->first();
        if (empty($answerVariant))
            return $this->errorResponse(__('message.Not Found'), 404);
        $studentAnswer = UserAnswer::updateOrCreate(
            [
                'exam_user_id' => $request->exam_user_id,
                'questions_for_exam_id' => $request->question_id,
            ],
            [
                'exam_user_id' => $request->exam_user_id,
                'questions_for_exam_id' => $request->question_id,
                'answer_variant_id' => $request->answer_variant_id,
                'score' => $answerVariant->score,
            ],
        );

        try {
            $studentAnswer->save();
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }

        return $this->successResponse(__('message.Successfully saved'));
    }

    public function finish($request)
    {
        $exam = Exam::where('id', request('exam_id'))
            ->withWhereHas('examUser', function ($query) {
                $query->where('user_id', user()->id);
            })
            // ->where('datetime_start', '<', date('Y-m-d H:i:s'))
            // ->where('datetime_end', '>', date('Y-m-d H:i:s'))
            ->first();
        if (empty($exam))
            return $this->errorResponse(__('message.Test_dined'), 403);

        $examUser = ExamUser::where('exam_id', request('exam_id'))
            ->where('key', ExamUser::EXAM_USER_START)
            ->where('user_id', user()->id)
            ->first();
        if (empty($examUser))
            return $this->errorResponse(__('message.Test_dined'), 403);
        $answers = UserAnswer::where('exam_user_id', $examUser->id)->get();
        $total_score = 0;
        foreach ($answers as $answer) {
            $total_score += $answer->score;
        }

        // $question = $examUser?->exam?->questions_count;
        // $mak_ball = $examUser?->exam?->max_ball;
        // $true_answers = StudentAnswer::where('exam_user_id', $examUser->id)->count();
        // $percent = (($true_answers * 100) / $question);
        // $ball = (($true_answers / $question) * $mak_ball);
        $examUser->update([
            'attempt' => $this->attempt($request),
            // 'procent' => number_format($percent, 2, '.'),
            'rating' => number_format($total_score, 2, '.'),
            'datetime_end' => date('Y-m-d H:i:s'),
            'key' => ExamUser::EXAM_USER_END,
        ]);

        try {
            $examUser->save();
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }

        return $this->successResponse(__('message.Successfully saved'));
    }

    public function attempt($request)
    {
        $access = ExamUser::where('exam_id', $request->exam_id)
            ->where('user_id', user()->id)
            ->first();
        if (empty($access) || !isset($access->attempt) || $access->attempt == 0) {
            return 1;
        }
        if (!empty($access) && $access->attempt) {
            return ++$access->attempt;
        }
    }
}
