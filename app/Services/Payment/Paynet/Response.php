<?php

namespace App\Services\Payment\Paynet;

use App\Services\Payment\ResponseClass;
use App\Services\Payment\ResponseException;

class Response extends ResponseClass
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

    /**
     * Paynet - Response constructor
     *
     * @return Response
     */
    public function __construct()
    {
        return $this;
    }

    /**
     * Set response body
     *
     * @param Request $request
     * @param mixed $body
     * @param int $code
     * @return void
     *
     * @throws ResponseException
     */
    public function response(Request $request, mixed $body, int $code): void
    {
        $this->request = $request;
        $this->body = $body;
        $this->code = $code;

        throw new ResponseException($this);
    }

    /**
     * Make response body
     *
     * @param string $body
     * @return string
     */
    public static function makeResponse(string $body): string
    {
        return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>".
                "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\">".
                    "<soapenv:Body>".
                        $body.
                    "</soapenv:Body>".
                "</soapenv:Envelope>";
    }

    /**
     * Define request model
     *
     * @param Request $request
     * @return void
     */
    public function _request(Request $request): void
    {
        $this->request = $request;
    }

    /**
     * Display the result
     *
     * @return mixed
     */
    public function send(): mixed
    {
        return $this->body;
    }

    /**
     * Return headers
     *
     * @return array
     */
    public function headers(): array
    {
        return [
            'Content-Type' => 'text/xml',
        ];
    }
}
