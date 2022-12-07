<?php

namespace App\Services\Payment;

use Exception;

class PaymentException extends Exception
{
    public function __construct(string $message = "")
    {
        parent::__construct(message: $message);
    }
}
