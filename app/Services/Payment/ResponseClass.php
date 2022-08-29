<?php

namespace App\Services\Payment;

abstract class ResponseClass
{
    abstract public function send(): array;
}
