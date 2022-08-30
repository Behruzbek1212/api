<?php

namespace App\Services\Payment\Paynet;

use App\Services\Payment\PaymentClass;
use JetBrains\PhpStorm\ArrayShape;

class Paynet extends PaymentClass
{
    public const ARRAY_SHAPE = [
        'login' => 'integer|string',
        'password' => 'integer|string',
        'service_id' => 'integer|string',
    ];

    #[ArrayShape(self::ARRAY_SHAPE)]
    protected array $config;
    protected array $request;
    protected Merchant $merchant;
    protected Response $response;

    /**
     * Click - Payment driver constructor
     *
     * @return Paynet
     */
    public function __construct()
    {
        $this->config = config('payment.paynet');
        $this->response = new Response;

        return $this;
    }

    /**
     * Run payment driver
     *
     * @return void
     */
    public function run(): void
    {
        //
    }
}
