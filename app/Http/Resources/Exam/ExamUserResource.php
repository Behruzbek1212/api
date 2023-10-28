<?php

namespace App\Http\Resources\Exam;

use Illuminate\Http\Resources\Json\JsonResource;

class ExamUserResource extends JsonResource
{

    public function toArray($request)
    {
        // dd(32324);
        return [
            'id' => $this->id,
            'answer_variants' => $this->getAnswers(),
            // 'user_fio' => $this->candidate->surname ?? null . ' ' . $this->candidate->name ?? null,
            'date' => $this->created_at ?? null,
            // 'true_answers' => optional($this->UserAnswerTrue)->count(),
            // 'procent' => $this->procent,
            'score' => $this->rating ?? null,
            // 'download' => "https://media.eduni.uz/storage".$this->file,
        ];
    }

    public function userAnswer($receiver)
    {
        $value = false;
        foreach ($this->userAnswers as $userAnswer) {
            $value = ($value or $receiver == $userAnswer->answer_variant_id);
        }
        return $value;
    }


    public function getAnswers()
    {
        $examQuestionCount = $this->exam->questions_count;
        $list = [];
        if ($this->examQuestions)
            foreach ($this->examQuestions->take($examQuestionCount) as $examQuestion) {
                foreach ($examQuestion->questions as $question) {
                    $answers = [];

                    foreach ($question->answerVariants as $receiver) {
                        if ($this->userAnswer($receiver->id)) {
                            $answers[] = [
                                'id' => $receiver->id,
                                'answer' => $receiver->answer,
                                'score' => $receiver->score,
                                'user_answer' => true ?? null,
                            ];
                        } else {
                            $answers[] = [
                                'id' => $receiver->id,
                                'answer' => $receiver->answer,
                                'score' => $receiver->score,
                                'user_answer' => null,
                            ];
                        }
                    }
                    $list[] = [
                        'id' => $question->id,
                        'question' => $question->question,
                        'answers' => $answers,
                    ];
                }
            }
        return $list;
    }
}
