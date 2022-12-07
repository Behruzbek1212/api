<?php

namespace App\Services;

class DataFormat
{
    /**
     * Convert coins to sum
     *
     * @param int|string $coins
     * @return float|int
     */
    public static function toSum(int|string $coins): float|int
    {
        return $coins / 100;
    }

    /**
     * Convert sum to coins
     *
     * @param float $amount
     * @return int
     */
    public static function toCoin(float $amount): int
    {
        return round($amount * 100);
    }

    /**
     * Get current timestamp in seconds or milliseconds
     *
     * @param bool $milliseconds
     * @return int
     */
    public static function timestamp(bool $milliseconds = false): int
    {
        return $milliseconds ?
            round(microtime(true) * 1000) : // Return milliseconds
            time(); // Return seconds
    }

    /**
     * Converts timestamp value from milliseconds to seconds
     *
     * @param string $timestamp
     * @return float
     */
    public static function timestamp2seconds(string $timestamp): float
    {
        // is it already as seconds
        if (strlen($timestamp) == 10) {
            return $timestamp;
        }

        return floor($timestamp / 1000);
    }

    /**
     * Converts timestamp value from seconds to milliseconds
     *
     * @param int $timestamp
     * @return int
     */
    public static function timestamp2milliseconds(int $timestamp): int
    {
        // is it already as milliseconds
        if (strlen((string)$timestamp) == 13) {
            return $timestamp;
        }

        return $timestamp * 1000;
    }

    /**
     * Converts timestamp to date time string
     *
     * @param string $timestamp
     * @return string
     */
    public static function timestamp2datetime(string $timestamp): string
    {
        // if as milliseconds, convert to seconds
        if (strlen($timestamp) == 13) {
            $timestamp = self::timestamp2seconds($timestamp);
        }

        // convert to datetime string
        return date('Y-m-d H:i:s', strtotime($timestamp));
    }

    /**
     * Converts date time string to timestamp value
     *
     * @param string $datetime
     * @return int|string
     */
    public static function datetime2timestamp(string $datetime): int|string
    {
        return $datetime ?
            strtotime($datetime) :
            $datetime;
    }

    /**
     * @param string $time
     * @return bool|string
     */
    public static function toDateTime(string $time): bool|string
    {
        return date('Y-m-d H:i:s', strtotime($time));
    }

    /**
     * @param string $time
     * @return string
     */
    public static function toDateTimeWithTimeZone(string $time): string
    {
        return date('Y-m-d\TH:i:s', strtotime($time)) . '+05:00';
    }
}
