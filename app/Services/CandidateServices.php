<?php

namespace App\Services;

use App\Filters\CandidateFilter;
use App\Filters\JobFilter;
use App\Repository\CandidateRepository;
use App\Traits\HasScopes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class CandidateServices
{
    use HasScopes;
    public $repository;

    public function __construct(CandidateRepository $data)
    {
        $this->repository = $data;
    }

    public static function getInstance(): CandidateServices
    {
        return new static(CandidateRepository::getInctance());
    }

    public function list($request)
    {
        $condidate_limit = user()->customer->limit_customer->condidate_limit ?? [];
        $candidates =  new CandidateFilter($request);
        return $this->repository->list(function (Builder $builder) use ($candidates, $condidate_limit) {
            return $builder
                ->whereHas('user', function ($query) {
                    $query->where('role', 'candidate');
                })
                ->where('active', true)
                ->orderBy('id', 'DESC')
                ->filter($candidates)
                ->take($condidate_limit);
        });
    }

    public function one($id)
    {
        return $this->repository->one(function (Builder $builder) use ($id) {
            return $builder
                ->with(['user:id,email,phone,verified', 'user.resumes'])
                ->whereHas('user', function (Builder $query) {
                    $query->where('role', 'candidate');
                })
                ->where('active', true)
                ->where('id', $id);
        });
    }

    public function get_one($request, $id)
    {
        // dd(_auth()->user()->customer->jobs->where('id', 15)->first());
        return $this->repository->one(function (Builder $builder) use ($id) {
            return $builder
                ->with(['user:id,email,phone,verified', 'user.resumes'])
                ->whereHas('user', function (Builder $query) {
                    $query->where('role', 'customer');
                })
                ->where('active', true)
                ->where('id', $id)
                ->whereHas('user', function ($query) {
                    $query->whereHas('customer', function ($query) {
                        $query->where('id', request('customer_id'));
                        $query->whereHas('jobs', function ($query) {
                            $query->where('id', request('job_id'));
                        });
                    });
                });
        });
    }
}
