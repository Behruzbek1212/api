<?php

namespace App\Services\Payment;

abstract class PaymentClass
{
    /**
     * Run payment driver
     *
     * @return void
     */
    abstract public function run(): void;

    /**
     * Description status
     *
     * @var bool
     */
    public bool $hasDescription = false;

    /**
     * Set the description status
     *
     * @param bool $status
     * @return $this
     */
    public function setDescription(bool $status = true): static
    {
        $this->hasDescription = $status;

        return $this;
    }
}
