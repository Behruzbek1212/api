<?php

namespace App\Services\Payment;

use App\Models\Transaction;
use App\Models\User;

class Listener
{
    /**
     * Before payment handler
     *
     * @param User $model
     * @return void
     */
    protected function before_pay(User $model): void
    {
        //
    }

    /**
     * While paying handler
     *
     * @param User $model
     * @param Transaction $transaction
     * @return void
     */
    protected function paying(User $model, Transaction $transaction): void
    {
        //
    }

    /**
     * After payment handler
     *
     * @param Transaction $transaction
     * @return void
     */
    protected function after_pay(Transaction $transaction): void
    {
        $model = User::query()->find($transaction['transactionable_id']);
        $driver = $transaction['payment_system'];

        $balance = $model->customer->balance;
        $amount = explode(',', $transaction['amount'])[0];

        if ($driver === PaymentSystems::PAYNET)
            $amount /= 100; // Convert `tiyin` to `sum`

        $model->customer()->update([
            'balance' => $balance + $amount
        ]);
    }

    /**
     * Cancel payment handler
     *
     * @param Transaction $transaction
     * @return void
     */
    protected function cancel_pay(Transaction $transaction): void
    {
        $model = User::query()->find($transaction['transactionable_id']);
        $driver = $transaction['payment_system'];

        $balance = $model->customer->balance;
        $amount = explode(',', $transaction['amount'])[0];

        if ($driver === PaymentSystems::PAYNET)
            $amount /= 100; // Convert `tiyin` to `sum`

        $model->customer()->update([
            'balance' => $balance - $amount
        ]);
    }
}
