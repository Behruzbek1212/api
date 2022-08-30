<?php

namespace App\Services\Payment\Click;

use App\Services\Payment\ResponseClass;
use App\Services\Payment\ResponseException;

class Response extends ResponseClass
{
    public const SUCCESS                       = 0;
    public const ERROR_SIGN_CHECK              = -1;
    public const ERROR_INVALID_AMOUNT          = -2;
    public const ERROR_ACTION_NOT_FOUND        = -3;
    public const ERROR_ALREADY_PAID            = -4;
    public const ERROR_ORDER_NOT_FOUND         = -5;
    public const ERROR_TRANSACTION_NOT_FOUND   = -6;
    public const ERROR_UPDATE_ORDER            = -7;
    public const ERROR_REQUEST_FROM            = -8;
    public const ERROR_TRANSACTION_CANCELLED   = -9;

    public array $result                = [];

    /**
     * @param string|int|null $status
     * @param array|null $params
     *
     * @throws ResponseException
     */
    public function setResult(string|int $status = null, array $params = null)
    {
        $this->result['error'] = $status;

        $this->result['error_note'] = match ($status) {
            self::SUCCESS => 'success',
            self::ERROR_SIGN_CHECK => 'signature_verification_error',
            self::ERROR_INVALID_AMOUNT => 'invalid_payment_amount',
            self::ERROR_ACTION_NOT_FOUND => 'The_requested_action_is_not_found',
            self::ERROR_ALREADY_PAID => 'the_transaction_was_previously_confirmed',
            self::ERROR_ORDER_NOT_FOUND => 'do_not_find_a_user-order',
            self::ERROR_TRANSACTION_NOT_FOUND => 'the_transaction_is_not_found',
            self::ERROR_UPDATE_ORDER => 'an_error_occurred_while_changing_user_data',
            self::ERROR_REQUEST_FROM => 'the_error_in_the_request_from_CLICK',
            self::ERROR_TRANSACTION_CANCELLED => 'the_transaction_was_previously_canceled',
            default => 'vendor_not_found',
        };

        if (is_array($params)) {
            foreach ($params as $key => $value) {
                $this->result[$key] = $value;
            }
        }

        throw new ResponseException($this);
    }

    /**
     * Display the result
     *
     * @return array
     */
    public function send(): array
    {
        // $secret_key = config('payment.click')['secret_key'];
        // $digest = sha1(time() . $secret_key);

        return $this->result;
    }
}
