<?php
$total_amount =  \App\Models\Transaction::where('transactionable_id', $transaction->transactionable_id)->where('state', 2)->sum('amount') ?? 0;
        \App\Models\User::where('id', $transaction->transactionable_id)
            ->update([
                'balance' => $total_amount
            ]);