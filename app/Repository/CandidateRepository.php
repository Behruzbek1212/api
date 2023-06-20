<?php

namespace App\Repository;

use App\Models\Candidate;
use App\Models\Job;
use Closure;

class CandidateRepository
{
    public static function getInctance(): CandidateRepository
    {
        return new static();
    }

    public function list(Closure $closure)
    {
        return $closure(Candidate::query())->get();
    }
}
