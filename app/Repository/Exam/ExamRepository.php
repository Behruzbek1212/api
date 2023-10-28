<?php

namespace App\Repository\Exam;

use App\Models\Exam;
use Closure;

class ExamRepository
{
    public static function getInctance():ExamRepository
    {
        return new static();
    }


    public function list(Closure $closure)
    {
        return $closure(Exam::query())->paginate(request()->get('per_page',10));
    }


}
