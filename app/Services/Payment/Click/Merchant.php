<?php

namespace App\Services\Payment\Click;

use App\Services\Payment\ResponseException;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;

class Merchant
{
    #[ArrayShape(Click::ARRAY_SHAPE)]
    protected array $config;
    protected bool $result = false;
    protected Response $response;

    /**
     * Click merchant constructor
     *
     * @return Merchant
     */
    public function __construct(Response $response)
    {
        $this->config = config('payment.click');
        $this->response = $response;

        return $this;
    }

    /**
     * Validate given request parameters
     *
     * @param Request|array $request
     * @return void
     *
     * @throws ResponseException
     */
    public function validateRequest(Request|array $request): void
    {
        $this->result = match ($request['action']) {
            Click::REQUEST_PREPARE => $this->validatePrepare($request),
            Click::REQUEST_COMPLETE => $this->validateComplete($request),
            default => false
        };

        if ($request['service_id'] !== $this->config['service_id'] || ! $this->result)
            $this->response->setResult(Response::ERROR_SIGN_CHECK);
    }

    /**
     * Validate the `prepare` request
     *
     * @param Request|array $request
     * @return bool
     */
    protected function validatePrepare(Request|array $request): bool
    {
        $sign = md5($request['click_trans_id'] .
            $request['service_id'] . $this->config['secret_key'] .
            $request['merchant_trans_id'] . $request['amount'] .
            $request['action'] . $request['sign_time']);

        return $sign === $request['sign_string'];
    }

    /**
     * Validate the `complete` request
     *
     * @param Request|array $request
     * @return bool
     */
    protected function validateComplete(Request|array $request): bool
    {
        $sign = md5($request['click_trans_id'] .
            $request['service_id'] . $this->config['secret_key'] .
            $request['merchant_trans_id'] . $request['merchant_prepare_id'] .
            $request['amount'] . $request['action'] .
            $request['sign_time']);

        return $sign === $request['sign_string'];
    }
}
