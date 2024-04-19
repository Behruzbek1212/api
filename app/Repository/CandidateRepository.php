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

    public function all()
    {
        return Candidate::with(['user:id,email,phone,verified', 'user.resumes'])
            ->orderByDesc('id')
            ->whereHas('user', function ($query) {
                $query->where('role', '=', 'candidate');
            })
            ->whereHas('testResult', function ($query){
                $query->where('customer_id', null);
            })
            ->where('active', '=', true);
    }

    public function list(Closure $closure)
    {
        return $closure(Candidate::query())->get();
    }

    public function one(Closure $closure)
    {
        return $closure(Candidate::query())->firstOrFail();
    }
}
