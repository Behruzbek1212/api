<?php

namespace App\Services;

use App\Constants\PaymentServiceConst;
use App\Exceptions\PaymentServiceException;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class Payment extends PaymentServiceConst
{
    public Builder $model;

    /**
     * PlayMobile service constructor
     *
     * @return Payment
     */
    public function __construct()
    {
        $this->model = DB::table('payments');

        return $this;
    }

    /**
     * Payment system provider constructor
     *
     * @param string $provider
     * @return void
     *
     * @throws PaymentServiceException
     */
    public function provider(string $provider): void
    {
        switch ($provider) {
            case self::PROVIDER_PAYNET:
            case self::PROVIDER_CLICK:
                break;

            default:
                throw new PaymentServiceException('Invalid provider specified');
        }
    }

    /**
     * Display payment url
     *
     * @return string
     */
    public function redirect(): string
    {
        return 'https://click.uz';
    }
}
