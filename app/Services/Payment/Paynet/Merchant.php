<?php

namespace App\Services\Payment\Paynet;

use App\Services\Payment\ResponseException;
use JetBrains\PhpStorm\ArrayShape;

class Merchant
{
    #[ArrayShape(Paynet::ARRAY_SHAPE)]
    public array $config;
    public Request $request;
    public Response $response;

    /**
     * Paynet Merchant constructor
     *
     * @param array $config
     * @param Request $request
     * @param Response $response
     * @return Merchant
     */
    public function __construct(array $config, Request $request, Response $response)
    {
        $this->config = $config;
        $this->request = $request;
        $this->response = $response;

        return $this;
    }

    /**
     * Paynet - Authenticate
     *
     * @return bool
     *
     * @throws ResponseException
     */
    public function Authorize(): bool
    {
        $account = $this->request->params['account'];

        if (
            $this->config['login'] != $account['login'] ||
            $this->config['password'] != $account['password']
        ) {
            $this->response->response(
                $this->request,
                'Insufficient privilege to perform this method.',
                Response::ERROR_INSUFFICIENT_PRIVILEGE
            );
        }

        return true;
    }
}
