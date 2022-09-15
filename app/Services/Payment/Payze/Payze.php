<?php

namespace App\Services\Payment\Payze;

use App\Models\User;
use App\Services\Payment;
use JsonException;
use PayzeIO\LaravelPayze\Exceptions\UnsupportedCurrencyException;
use PayzeIO\LaravelPayze\Requests\JustPay;
use Throwable;

class Payze
{
    /**
     * Payment redirect method.
     *
     * @param User $model
     * @param int|float $amount
     * @param int|string $currency
     * @param string $url
     *
     * @return array
     * @throws JsonException|UnsupportedCurrencyException|Throwable
     */
    public function getRedirectParams(User $model, int|float $amount, int|string $currency, string $url): array
    {
        if (is_int($currency)) {
            $currency = Payment::currencyCodeToString($currency);
        }

        $raw = JustPay::request($amount)
            ->raw()
            ->for($model)
            ->currency($currency)
            ->process();

        return [
            'url' => $raw['transactionUrl'],
            'transactionId' => $raw['transactionId']
        ];
    }
}
