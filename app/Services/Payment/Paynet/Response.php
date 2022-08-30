<?php

namespace App\Services\Payment\Paynet;

class Response
{
    public const ERROR_INTERNAL_SYSTEM         = -32400;
    public const ERROR_INSUFFICIENT_PRIVILEGE  = -32504;
    public const ERROR_INVALID_JSON_RPC_OBJECT = -32600;
    public const ERROR_METHOD_NOT_FOUND        = -32601;
    public const ERROR_INVALID_AMOUNT          = -31001;
    public const ERROR_TRANSACTION_NOT_FOUND   = -31003;
    public const ERROR_INVALID_ACCOUNT         = -31050;
    public const ERROR_COULD_NOT_CANCEL        = -31007;
    public const ERROR_COULD_NOT_PERFORM       = -31008;
    public const SUCCESS                       = 0;

    protected Request $request;
    protected mixed $body;
    protected int $code;
}
