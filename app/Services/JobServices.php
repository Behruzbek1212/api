<?php

namespace App\Services;

use App\Filters\JobFilter;
use App\Models\Job;
use App\Models\Trafic;
use App\Repository\JobRepository;
use App\Traits\HasScopes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class JobServices
{
    use HasScopes;
    public $repository;

    public function __construct(JobRepository $data)
    {
        $this->repository = $data;
    }

    public static function getInstance(): JobServices
    {
        return new static(JobRepository::getInctance());
    }

    public function dropTrafic()
    {
        Job::whereNotNull('trafic_id')
            ->whereHas('trafic', function ($query) {
                $query->whereIn('type', Trafic::NOT_DROP_TYPE);
            })
            ->where('trafic_expired_at', '<=', date('Y-m-d H:i:s'))
            ->update([
                'trafic_id' => null,
                'trafic_expired_at' => null,
            ]);

        return true;
    }

    public function list($request)
    {
        $jobs =  new JobFilter($request);
        return $this->repository->list(function (Builder $builder) use ($jobs) {
            return $builder
                ->with('trafic')
                ->join('trafics', 'jobs.trafic_id', '=', 'trafics.id', 'left')
                ->orderBy('trafics.type', 'DESC')
                ->orderBy('trafics.vip_day', 'DESC')
                ->orderBy('trafics.count_rise', 'DESC')
                ->orderBy('id', 'DESC')
                ->select('jobs.*')
                ->filter($jobs);
        });
    }

    public function companiesJobs($request)
    {
        $jobs = Job::query()
            ->where('customer_id', $request->customer_id)
            ->where('deleted_at', null)
            ->orderByDesc('updated_at')
            ->paginate($request->limit ?? 8);
            
        return $jobs;
    }
}
