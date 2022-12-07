<?php

namespace App\Services\Payment\Click;

use App\Models\Transaction;
use App\Models\User;
use App\Services\DataFormat;
use App\Services\Payment;
use App\Services\Payment\PaymentClass;
use App\Services\Payment\PaymentException;
use App\Services\Payment\PaymentSystems;
use App\Services\Payment\ResponseException;
use JetBrains\PhpStorm\ArrayShape;

class Click extends PaymentClass
{
    public const ARRAY_SHAPE = [
        'service_id' => 'integer|string',
        'secret_key' => 'integer|string',
        'merchant_id' => 'integer|string',
        'merchant_user_id' => 'integer|string',
    ];

    public const REQUEST_PREPARE = '0';
    public const REQUEST_COMPLETE = '1';

    #[ArrayShape(self::ARRAY_SHAPE)]
    protected array $config;
    protected array $request;
    protected Merchant $merchant;
    protected Response $response;

    /**
     * Click - Payment driver constructor
     *
     * @return Click
     */
    public function __construct()
    {
        $this->config = config('payment.click');
        $this->request = request()->all();
        $this->response = new Response;
        $this->merchant = new Merchant($this->response);

        return $this;
    }

    /**
     * Run payment driver
     *
     * @return void
     * @throws ResponseException|PaymentException
     */
    public function run(): void
    {
        $required_fields =[
            'click_trans_id', 'service_id',
            'click_paydoc_id', 'merchant_trans_id',
            'amount', 'action', 'error', 'error_note',
            'sign_time', 'sign_string'
        ];

        if (! $this->check_required_fields($required_fields))
            $this->response->setResult(Response::ERROR_REQUEST_FROM);

        $this->merchant->validateRequest($this->request);

        match ($this->request['action']) {
            self::REQUEST_PREPARE   => $this->Prepare(),
            self::REQUEST_COMPLETE  => $this->Complete(),
            default => $this->response->setResult(Response::ERROR_ACTION_NOT_FOUND)
        };
    }

    /**
     * Payment method - Prepare
     *
     * @return void
     *
     * @throws ResponseException|PaymentException
     */
    protected function Prepare(): void
    {
        $request = $this->request;
        $model = Payment::convertKeyToModel($request['merchant_trans_id']);

        $additional_params = [
            'merchant_prepare_id'   => null,
            'click_trans_id'        => null,
            'merchant_trans_id'     => null
        ];

        if (! $model)
            $this->response->setResult(Response::ERROR_ORDER_NOT_FOUND);

        if (! Payment::isProperAmount($request['amount']))
            $this->response->setResult(Response::ERROR_ORDER_NOT_FOUND);

        Payment::payListener('before-pay', $model, $request['amount']);

        $detail = [
            'create_time'           => DataFormat::timestamp(true),
            'system_time_datetime'  => DataFormat::timestamp2datetime($request['sign_time'])
        ];

        $transaction = Transaction::query()->create([
            'payment_system'        => PaymentSystems::CLICK,
            'currency_code'         => Transaction::CURRENCY_CODE_UZS,
            'state'                 => Transaction::STATE_CREATED,
            'system_transaction_id' => $request['click_trans_id'],
            'comment'               => $request['error_note'],
            'amount'                => 1 * $request['amount'], // Convert string to integer
            'updated_time'          => $detail['create_time'],
            'detail'                => $detail,
            'transactionable_type'  => get_class($model),
            'transactionable_id'    => Payment::convertModelToKey($model)
        ]);

        $additional_params['click_trans_id'] = $request['click_trans_id'];
        $additional_params['merchant_trans_id'] = $request['merchant_trans_id'];
        $additional_params['merchant_prepare_id'] = $transaction['id'];

        Payment::payListener('paying', $model, $transaction);
        $this->response->setResult(Response::SUCCESS, $additional_params);
    }

    /**
     * Payment method - Complete
     *
     * @return void
     *
     * @throws ResponseException|PaymentException
     */
    protected function Complete(): void
    {
        $request = $this->request;
        /** @var Transaction $transaction */
        $transaction = Transaction::query()->find($request['merchant_prepare_id']);

        $additional_params = [
            'click_trans_id' => $request['click_trans_id'],
            'merchant_trans_id' => $request['merchant_trans_id'],
            'merchant_confirm_id' => null
        ];

        if (! $transaction)
            $this->response->setResult(Response::ERROR_TRANSACTION_NOT_FOUND);

        if ($request['error'] == -1) {
            $additional_params['error_note'] = $request['error_note'];
            $this->response->setResult(Response::ERROR_ALREADY_PAID);
        }

        if ($request['error'] == -5017) {
            $additional_params['error_note'] = $request['error_note'];
            $transaction->state = Transaction::STATE_CANCELLED;
            $transaction->update();
            $this->response->setResult(Response::ERROR_TRANSACTION_CANCELLED);
        }

        if ($transaction->state == Transaction::STATE_CANCELLED)
            $this->response->setResult(Response::ERROR_TRANSACTION_CANCELLED);

        if ($transaction->state != Transaction::STATE_CREATED)
            $this->response->setResult(Response::ERROR_ALREADY_PAID);

        if ($transaction->amount != $request['amount']) {
            $this->response->setResult(Response::ERROR_INVALID_AMOUNT);
        }

        $transaction->state = Transaction::STATE_COMPLETED;
        $transaction->update();

        $additional_params['merchant_confirm_id'] = $transaction->id;
        Payment::payListener('after-pay', null, $transaction);

        $this->response->setResult(Response::SUCCESS, $additional_params);
    }

    /**
     * Check required fields
     *
     * @param array $fields
     * @return bool
     */
    private function check_required_fields(array &$fields): bool
    {
        $request = $this->request;

        if ($request['action'] == self::REQUEST_COMPLETE)
            $fields[] = 'merchant_prepare_id';

        foreach ($fields as $field) {
            if (! array_key_exists($field, $request))
                return false;
        }

        return true;
    }

    /**
     * Payment redirect method.
     *
     * @param User $model
     * @param int|float $amount
     * @param int $currency
     * @param string $url
     * @return array
     */
    public function getRedirectParams(User $model, int|float $amount, int $currency, string $url): array
    {
        $time = date('Y-m-d H:i:s', time());
        $sign = md5($time . $this->config['secret_key'] .
            $this->config['service_id'] . $amount);

        return [
            'merchant_id' => $this->config['merchant_id'],
            'merchant_user_id' => $this->config['merchant_user_id'],
            'service_id' => $this->config['service_id'],
            'transaction_param' => Payment::convertModelToKey($model),
            'return_url' => $url,
            'amount' => $amount,
            'SIGN_TIME' => $time,
            'SIGN_STRING' => $sign,
            'url'       => 'https://my.click.uz/services/pay'
        ];
    }
}
