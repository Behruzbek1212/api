<?php

namespace App\Services\Payment\Paynet;

use App\Services\Payment\ResponseException;

class Request
{
    public const ARGUMENTS_PerformTransaction  = 'PerformTransactionArguments';
    public const ARGUMENTS_CheckTransaction    = 'CheckTransactionArguments';
    public const ARGUMENTS_GetStatement        = 'GetStatementArguments';
    public const ARGUMENTS_CancelTransaction   = 'CancelTransactionArguments';
    public const ARGUMENTS_GetInformation      = 'GetInformationArguments';

    public const METHOD_PerformTransaction     = 'PerformTransaction';
    public const METHOD_CheckTransaction       = 'CheckTransaction';
    public const METHOD_GetStatement           = 'GetStatement';
    public const METHOD_CancelTransaction      = 'CancelTransaction';
    public const METHOD_GetInformation         = 'GetInformation';

    public mixed $method;
    public array $params = [];
    public Response $response;

    protected ?string $_key = null;

    /**
     * Paynet - Request constructor
     *
     * @param Response $response
     * @return Request
     *
     * @throws ResponseException
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
        $params = $this->getRequestParams();
        $this->auth($params);

        foreach ($params as $key => $value) {
            match ($key) {
                self::ARGUMENTS_PerformTransaction =>
                    $this->paramsPerformTransaction($params[self::ARGUMENTS_PerformTransaction]),

                self::ARGUMENTS_CheckTransaction =>
                    $this->paramsCheckTransaction($params[self::ARGUMENTS_CheckTransaction]),

                self::ARGUMENTS_GetStatement =>
                    $this->paramsStatement($params[self::ARGUMENTS_GetStatement]),

                self::ARGUMENTS_CancelTransaction =>
                    $this->paramsCancel($params[self::ARGUMENTS_CancelTransaction]),

                self::ARGUMENTS_GetInformation =>
                    $this->paramsInformation($params[self::ARGUMENTS_GetInformation]),

                default =>
                    $this->response->response($this, 'Error in request', Response::ERROR_METHOD_NOT_FOUND)
            };
        }

        return $this;
    }

    /**
     * Load paynet account
     *
     * @param array $params
     * @return void
     */
    protected function auth(array $params): void
    {
        $_params = array_values($params)[0];

        $this->params['account'] = [
            'login' => $_params['username'],
            'password' => $_params['password'],
        ];

        $this->params['serviceId'] = $_params['serviceId'];
    }

    /**
     * Get request parameters
     *
     * @return array
     *
     * @throws ResponseException
     */
    protected function getRequestParams(): array
    {
        $request_body  = file_get_contents('php://input');
        $clean_xml = str_ireplace(['soapenv:', 'soap:', 'xmlns:', 'xsi:', 'ns1:'], '', $request_body);
        $xml = simplexml_load_string($clean_xml);
        $body = null;

        if ($xml)
            $body = $xml->Body;
        else
            $this->response->response(
                $this,
                'Error in request',
                Response::ERROR_INVALID_JSON_RPC_OBJECT
            );

        return json_decode(json_encode($body), 1);
    }

    /**
     * Paynet - PerformTransactionResponse
     *
     * @param array $params
     * @return void
     */
    protected function paramsPerformTransaction(array $params): void
    {
        $_parameters = $params['parameters'];

        if (isset($_parameters['paramValue'])):
            $this->_key = $_parameters['paramValue'];
        else:
            foreach ($_parameters as $_parameter):
                if ($_parameter['paramKey'] == 'key')
                    $this->_key = $_parameter['paramValue'];
            endforeach;
        endif;

        $response = [
            'method' => self::METHOD_PerformTransaction,
            'amount' => $params['amount'],
            'transactionId' => $params['transactionId'],
            'transactionTime' => $params['transactionTime'],
            'key' => $this->_key,
            'params' => $_parameters
        ];

        $this->params = array_merge($this->params, $response);
    }

    /**
     * Paynet - CheckTransactionResponse
     *
     * @param array $params
     * @return void
     */
    protected function paramsCheckTransaction(array $params): void
    {
        $response = [
            'method' => self::METHOD_CheckTransaction,
            'transactionId' => $params['transactionId'],
            'transactionTime' => $params['transactionTime'],
        ];

        $this->params = array_merge($this->params, $response);
    }

    /**
     * Paynet - StatementResponse
     *
     * @param array $params
     * @return void
     */
    protected function paramsStatement(array $params): void
    {
        $response = [
            'method' => self::METHOD_GetStatement,
            'dateFrom' => $params['dateFrom'],
            'dateTo' => $params['dateTo']
        ];

        $this->params = array_merge($this->params, $response);
    }

    /**
     * Paynet - CancelResponse
     *
     * @param array $params
     * @return void
     */
    protected function paramsCancel(array $params): void
    {
        $response = [
            'method' => self::METHOD_CancelTransaction,
            'transactionId' => $params['transactionId'],
            'transactionTime' => $params['transactionTime']
        ];

        $this->params = array_merge($this->params, $response);
    }

    /**
     * Paynet - InformationResponse
     *
     * @param array $params
     * @return void
     */
    protected function paramsInformation(array $params): void
    {
        $_parameters = $params['parameters'];

        if (isset($_parameters['paramValue'])):
            $this->_key = $_parameters['paramValue'];
        else:
            foreach ($_parameters as $_parameter):
                if ($_parameter['paramKey'] == 'key')
                    $this->_key = $_parameter['paramValue'];
            endforeach;
        endif;

        $response = [
            'method' => self::METHOD_GetInformation,
            'key' => $this->_key
        ];

        $this->params = array_merge($this->params, $response);
    }
}
