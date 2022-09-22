<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Exchange
{
    protected const PAYLOAD = 'https://nbu.uz/en/exchange-rates/json/';

    /**
     * @param string $currency
     *
     * @return int
     * @throws GuzzleException
     */
    public static function GetExchangeRate(string $currency): int
    {
        $currency = strtoupper($currency);
        $data = json_decode((new Client())
            ->get(self::PAYLOAD, ['verify' => false])
            ->getBody()
            ->getContents(), true);

        $rate = array_filter($data, function ($value) use ($currency) {
            return $value['code'] == $currency;
        });
        $key = array_keys($rate)[0];

        return $rate[$key]['cb_price'] * 1;
    }

    /**
     * @param int $amount
     *
     * @return float|int
     * @throws GuzzleException
     */
    public static function UZSToUSD(int $amount): float|int
    {
        $price = self::GetExchangeRate('USD');

        return $amount / $price;
    }

    /**
     * @param int $amount
     *
     * @return float|int
     * @throws GuzzleException
     */
    public static function USDToUZS(int $amount): float|int
    {
        $price = self::GetExchangeRate('USD');

        return $amount * $price;
    }
}
