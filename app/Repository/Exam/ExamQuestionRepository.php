<?php

namespace App\Repository\Exam;

use App\Models\Exam\ExamQuestion;
use Closure;

class ExamQuestionRepository
{
    public static function getInctance():ExamQuestionRepository
    {
        return new static();
    }


    public function getQuestion(Closure $closure)
    {
        return $closure(ExamQuestion::query())->get();
    }

}
