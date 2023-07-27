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

      // $user_id = _auth()->user()->id;
        // $total_amount =  Transaction::where('transactionable_id', _auth()->user()->id)->sum('amount') ?? 0;
        // User::where('id', $user_id)
        //     ->update([
        //         'balance' => $total_amount
        //     ]);
}
