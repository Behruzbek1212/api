<?php

namespace App\Repository;

use App\Models\Job;
use Closure;

class JobRepository
{
    public static function getInctance(): JobRepository
    {
        return new static();
    }

    public function list(Closure $closure)
    {
        return $closure(Job::query())->paginate(request()->get('limit', 10));
    }
}
