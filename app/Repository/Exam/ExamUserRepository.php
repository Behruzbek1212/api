<?php

namespace App\Repository\Exam;

use App\Models\Exam\ExamUser;
use Closure;

class ExamUserRepository
{
    public static function getInctance():ExamUserRepository
    {
        return new static();
    }

    public function list(Closure $closure)
    {
        return $closure(ExamUser::query())->get();
    }


}
