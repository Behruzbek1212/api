<?php

namespace App\Services\Payment;

use Exception;

class ResponseException extends Exception
{
    protected ResponseClass $response;

    /**
     * Exception constructor
     *
     * @param ResponseClass $response
     */
    public function __construct(ResponseClass $response)
    {
        $this->response = $response;
    }

    /**
     * Set the response to class
     *
     * @param ResponseClass $response
     * @return ResponseException
     */
    public function setResponse(ResponseClass $response): ResponseException
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Return the response
     *
     * @return array
     */
    public function response(): array
    {
        return $this->response->send();
    }
}
