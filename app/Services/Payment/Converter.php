<?php

namespace App\Services\Payment;

use App\Models\Transaction;
use App\Models\User;
use JsonException;
use PayzeIO\LaravelPayze\Exceptions\UnsupportedCurrencyException;

class Converter extends Listener
{
    /**
     * Get user identity number
     *
     * @param User $model
     * @return int|null
     */
    public static function convertModelToKey(User $model): int|null
    {
        return @$model->id;
    }

    /**
     * Convert identity number to model
     *
     * @param string|int $key
     * @return User|null
     */
    public static function convertKeyToModel(string|int $key): User|null
    {
        return User::query()->find($key);
    }

    /**
     * Check if amount is proper
     *
     * @param int|float $amount
     * @return bool
     */
    public static function isProperAmount(int|float $amount): bool
    {
        return $amount >= 500;
    }

    /**
     * Convert currency code to string
     *
     * @param int $currency
     *
     * @return string
     * @throws JsonException|UnsupportedCurrencyException
     */
    public static function currencyCodeToString(int $currency): string
    {
        if (! in_array($currency, Transaction::SUPPORTED)) {
            throw new JsonException('Currency code is not supported');
        }

        return match ($currency) {
            Transaction::CURRENCY_CODE_UZS => 'UZS',
            Transaction::CURRENCY_CODE_RUB => 'RUB',
            Transaction::CURRENCY_CODE_USD => 'USD',
            default => throw new UnsupportedCurrencyException($currency)
        };
    }

    /**
     * Payment hooks
     *
     * @param string $type
     * @param User|null $model
     * @param Transaction|int|null $transaction
     * @return void
     *
     * @throws PaymentException
     */
    public static function payListener(string $type, User $model = null, Transaction|int $transaction = null): void
    {
        match ($type) {
            'before-pay' => (new self)->before_pay($model),
            'paying' => (new self)->paying($model, $transaction),
            'after-pay' => (new self)->after_pay($transaction),
            'cancel-pay' => (new self)->cancel_pay($transaction),
            default => throw new PaymentException('Payment type is not defined'),
        };
    }
}
