<?php

namespace App\Services;

use App\Filters\JobFilter;
use App\Filters\TransactionHistoryFilter;
use App\Models\Job;
use App\Models\Trafic;
use App\Repository\JobRepository;
use App\Repository\TransactionHistoryRepository;
use App\Traits\HasScopes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class TransactionHistoryServices
{
    use HasScopes;
    public $repository;

    public function __construct(TransactionHistoryRepository $data)
    {
        $this->repository = $data;
    }

    public static function getInstance(): TransactionHistoryServices
    {
        return new static(TransactionHistoryRepository::getInctance());
    }

    public function list($request)
    {
        $transaction =  new TransactionHistoryFilter($request);
        return $this->repository->list(function (Builder $builder) use ($transaction) {
            return $builder
                ->with(['user', 'trafic'])
                ->filter($transaction);
        });
    }
}
