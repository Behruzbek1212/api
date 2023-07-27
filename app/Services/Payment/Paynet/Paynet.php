<?php

namespace App\Services\Payment\Paynet;

use App\Models\Transaction;
use App\Models\User;
use App\Services\DataFormat;
use App\Services\Payment;
use App\Services\Payment\PaymentClass;
use App\Services\Payment\PaymentException;
use App\Services\Payment\PaymentSystems;
use App\Services\Payment\ResponseException;
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
    protected Request $request;
    protected Merchant $merchant;
    protected Response $response;

    /**
     * Click - Payment driver constructor
     *
     * @return Paynet
     *
     * @throws ResponseException
     */
    public function __construct()
    {
        $this->config = config('payment.paynet');
        $this->response = new Response;
        $this->request = new Request($this->response);
        $this->response->_request($this->request);
        $this->merchant = new Merchant($this->config, $this->request, $this->response);

        return $this;
    }

    /**
     * Run payment driver
     *
     * @return void
     *
     * @throws ResponseException|PaymentException
     */
    public function run(): void
    {
        $this->merchant->Authorize();
        $method = $this->request->params['method'];

        $body = match ($method) {
            Request::METHOD_PerformTransaction =>
            Response::makeResponse($this->PerformTransaction()),

            Request::METHOD_CancelTransaction =>
            Response::makeResponse($this->CancelTransaction()),

            Request::METHOD_CheckTransaction =>
            Response::makeResponse($this->CheckTransaction()),

            Request::METHOD_GetInformation =>
            Response::makeResponse($this->GetInformation()),

            Request::METHOD_GetStatement =>
            $this->GetStatement(),

            default =>
            $this->response->response($this->request, 'Method not found.', Response::ERROR_METHOD_NOT_FOUND)
        };

        $this->response->response($this->request, $body, Response::SUCCESS);
    }

    /**
     * Paynet - PerformTransactionMethod
     *
     * @throws PaymentException
     */
    private function PerformTransaction(): string
    {
        if ($this->getTransactionBySystemTransactionId())
            return "<ns2:PerformTransactionResult xmlns:ns2=\"http://uws.provider.com/\">" .
                "<errorMsg>transaction found</errorMsg>" .
                "<status>201</status>" .
                "<timeStamp>" . DataFormat::toDateTimeWithTimeZone(now()) . "</timeStamp>" .
                "<providerTrnId>" . $this->request->params['transactionId'] . "</providerTrnId>" .
                "</ns2:PerformTransactionResult>";

        $model = Payment::convertKeyToModel($this->request->params['key']);

        if (is_null($model))
            return  "<ns2:PerformTransactionResult xmlns:ns2=\"http://uws.provider.com/\">" .
                "<errorMsg>Model not found</errorMsg>" .
                "<status>302</status>" .
                "<timeStamp>" . DataFormat::toDateTimeWithTimeZone(now()) . "</timeStamp>" .
                "<providerTrnId>0</providerTrnId>" .
                "</ns2:PerformTransactionResult>";

        $create_time = DataFormat::timestamp(true);
        $detail = [
            'create_time'           => $create_time,
            'perform_time'          => null,
            'cancel_time'           => null,
            'system_time_datetime'  => DataFormat::timestamp2datetime($this->request->params['transactionTime']),
            'params'                => $this->request->params['params'],
            'serviceId'             => $this->request->params['serviceId'],
        ];

        $transaction = Transaction::create([
            'payment_system'        => PaymentSystems::PAYNET,
            'system_transaction_id' => $this->request->params['transactionId'],
            'amount'                => 1 * $this->request->params['amount'],
            'currency_code'         => Transaction::CURRENCY_CODE_UZS,
            'state'                 => Transaction::STATE_CREATED,
            'updated_time'          => $create_time,
            'comment'               => ($this->request->params['error_note'] ?? ''),
            'detail'                => $detail,
            'transactionable_type'  => get_class($model),
            'transactionable_id'    => $model->id
        ]);

        Payment::payListener('after-pay', $model, $transaction);


        $user_id = _auth()->user()->id;
        $total_amount =  Transaction::where('transactionable_id', _auth()->user()->id)->sum('amount') ?? 0;
        User::where('id', $user_id) 
            ->update([
                'balance' => $total_amount
            ]);

        return  "<ns2:PerformTransactionResult xmlns:ns2=\"http://uws.provider.com/\">" .
            "<errorMsg>Success</errorMsg>" .
            "<status>0</status>" .
            "<timeStamp>" . DataFormat::toDateTimeWithTimeZone(now()) . "</timeStamp>" .
            "<providerTrnId>" . $this->request->params['transactionId'] . "</providerTrnId>" .
            "</ns2:PerformTransactionResult>";
    }

    /**
     * Paynet - CancelTransactionMethod
     *
     * @throws PaymentException
     */
    protected function CancelTransaction(): string
    {
        $transaction = $this->getTransactionBySystemTransactionId();

        if (is_null($transaction) || $transaction->state == Transaction::STATE_CANCELLED) {
            return  "<ns2:CancelTransactionResult xmlns:ns2=\"http://uws.provider.com/\">" .
                "<errorMsg>bekor qilingan</errorMsg>" .
                "<status>202</status>" .
                "<timeStamp>" . DataFormat::toDateTimeWithTimeZone(now()) . "</timeStamp>" .
                "<transactionState>2</transactionState>" .
                "</ns2:CancelTransactionResult>";
        }


        $transaction->state = Transaction::STATE_CANCELLED;
        $transaction->update();
        Payment::payListener('cancel-pay', null, $transaction);
        return  "<ns2:CancelTransactionResult xmlns:ns2=\"http://uws.provider.com/\">" .
            "<errorMsg>Success</errorMsg>" .
            "<status>0</status>" .
            "<timeStamp>" . DataFormat::toDateTimeWithTimeZone(now()) . "</timeStamp>" .
            "<transactionState>2</transactionState>" .
            "</ns2:CancelTransactionResult>";
    }

    /**
     * Paynet - CheckTransactionMethod
     *
     * @return string
     */
    protected function CheckTransaction(): string
    {
        $transaction = $this->getTransactionBySystemTransactionId();
        $transactionState = ($transaction->state == Transaction::STATE_CANCELLED) ? 2 : 1;

        return "<ns2:CheckTransactionResult xmlns:ns2=\"http://uws.provider.com/\">" .
            "<errorMsg>Success</errorMsg>" .
            "<status>0</status>" .
            "<timeStamp>" . DataFormat::toDateTimeWithTimeZone(now()) . "</timeStamp>" .
            "<providerTrnId>" . $this->request->params['transactionId'] . "</providerTrnId>" .
            "<transactionState>" . $transactionState . "</transactionState>" .
            "<transactionStateErrorStatus>0</transactionStateErrorStatus>" .
            "<transactionStateErrorMsg>Success</transactionStateErrorMsg>" .
            "</ns2:CheckTransactionResult>";
    }

    /**
     * Paynet - GetInformationMethod
     *
     * @return string
     */
    protected function GetInformation(): string
    {
        $model = Payment::convertKeyToModel($this->request->params['key']);

        if ($model) :
            return  "<ns2:GetInformationResult xmlns:ns2=\"http://uws.provider.com/\">" .
                "<errorMsg>Success</errorMsg>" .
                "<status>0</status>" .
                "<timeStamp>" . DataFormat::toDateTimeWithTimeZone(now()) . "</timeStamp>" .
                "<parameters>" .
                "<paramKey>userInfo</paramKey>" .
                "<paramValue>" . $model->customer->name . "</paramValue>" .
                "</parameters>" .
                "</ns2:GetInformationResult>";
        else :
            return  "<ns2:GetInformationResult xmlns:ns2=\"http://uws.provider.com/\">" .
                "<errorMsg>Not Found</errorMsg>" .
                "<status>302</status>" .
                "<timeStamp>" . DataFormat::toDateTimeWithTimeZone(now()) . "</timeStamp>" .
                "</ns2:GetInformationResult>";
        endif;
    }

    /**
     * Paynet - GetStatementMethod
     *
     * @return string
     */
    protected function GetStatement(): string
    {
        $transactions = Transaction::where('payment_system', PaymentSystems::PAYNET)
            ->where('state', '<>', Transaction::STATE_CANCELLED)
            ->where('created_at', '<=', DataFormat::toDateTime($this->request->params['dateTo']))
            ->where('created_at', '>=', DataFormat::toDateTime($this->request->params['dateFrom']))
            ->get();
        $statements = '';

        foreach ($transactions as $transaction) :
            $statements = $statements .
                "<statements>" .
                "<amount>" . $transaction->amount . "</amount>" .
                "<providerTrnId>" . $transaction->id . "</providerTrnId>" .
                "<transactionId>" . $transaction->system_transaction_id . "</transactionId>" .
                "<transactionTime>" . DataFormat::toDateTimeWithTimeZone($transaction->created_at) . "</transactionTime>" .
                "</statements>";
        endforeach;

        return  "<SOAP-ENV:Envelope xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:ns1=\"http://uws.provider.com/\">" .
            "<SOAP-ENV:Body>" .
            "<ns1:GetStatementResult>" .
            "<errorMsg>Success</errorMsg>" .
            "<status>0</status>" .
            "<timeStamp>" . DataFormat::toDateTimeWithTimeZone(now()) . "</timeStamp>" .
            $statements .
            "</ns1:GetStatementResult>" .
            "</SOAP-ENV:Body>" .
            "</SOAP-ENV:Envelope>";
    }

    protected function getTransactionBySystemTransactionId(): ?Transaction
    {
        return Transaction::query()
            ->where('system_transaction_id', $this->request->params['transactionId'])
            ->first();
    }
}
