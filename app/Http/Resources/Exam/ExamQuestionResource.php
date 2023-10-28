<?php

namespace App\Http\Resources\Exam;

use Illuminate\Http\Resources\Json\JsonResource;

class ExamQuestionResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'question_id' => optional($this->question)->id,
            'exam_user_id' => optional(optional($this->exam)->checkExamUser)->id,
            'question' => optional($this->question)->question,
            'answer_variants' => $this->getAnswers(),

        ];
    }



    public function getAnswers()
    {
        $list = [];
        foreach (optional($this->question)->answerVariants ?? [] as $answer) {

            $list[] = [
                'id' => $answer->id ?? '',
                'title' => $answer->answer ?? '',
                'image' => $answer->image ?? null,
                // 'selected' => ($this->pupilAnswer($answer->id)) ? true : null,
            ];
        }
        return $list;
    }


    public function pupilAnswer($receiver)
    {
        $value = false;
        if ($this->exam?->checkExamStudent === null) {
            return null;
        }
        foreach ($this->exam?->checkExamStudent?->studentAnswers as $studentAnswer) {
            $value = ($value or $receiver == $studentAnswer->answer_variant_id);
        }
        return $value;
    }
}
