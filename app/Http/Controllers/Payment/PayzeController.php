<?php

namespace App\Http\Controllers\Payment;

use App\Models\User;
use App\Services\Exchange;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use PayzeIO\LaravelPayze\Controllers\PayzeController as BasePayzeController;
use PayzeIO\LaravelPayze\Models\PayzeTransaction;

class PayzeController extends BasePayzeController
{
    /**
     * Success Response
     *
     * Do any transaction related operations and return a response
     * If nothing is returned, default response will be used
     *
     * @param PayzeTransaction $transaction
     * @param Request $request
     *
     * @return mixed
     * @throws GuzzleException
     */
    protected function successResponse(PayzeTransaction $transaction, Request $request)
    {
        /*
         * Do any transaction related operations and return a response
         * If nothing is returned, default response will be used
         */

        $amount = intval($transaction->amount);

        $amount = match ($transaction->currency) {
            'UZS' => $amount,
            'USD' => Exchange::USDToUZS($amount)
        };

        User::query()->find($transaction->model_id)
            ->customer()
            ->increment('balance', $amount);

        return response()->json([
            'status' => true,
            'message' => 'Transaction successfully processed'
        ]);
    }

    /**
     * Fail Response
     *
     * Do any transaction related operations and return a response
     * If nothing is returned, default response will be used
     *
     * @param PayzeTransaction $transaction
     * @param Request $request
     *
     * @return mixed
     */
    protected function failResponse(PayzeTransaction $transaction, Request $request)
    {
        /*
         * Do any transaction related operations and return a response
         * If nothing is returned, default response will be used
         */
    }
}
