<?php

namespace App\Services\Payment;

abstract class ResponseClass
{
    /**
     * Return response body
     *
     * @return mixed
     */
    abstract public function send(): mixed;

    /**
     * Return headers
     *
     * @return array
     */
    abstract public function headers(): array;
}
