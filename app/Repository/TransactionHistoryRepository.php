<?php

namespace App\Repository;

use App\Models\Job;
use App\Models\TransactionHistory;
use Closure;

class TransactionHistoryRepository
{
    public static function getInctance(): TransactionHistoryRepository
    {
        return new static();
    }

    public function list(Closure $closure)
    {
        return $closure(TransactionHistory::query())->paginate(request()->get('limit', 10));
    }
}
