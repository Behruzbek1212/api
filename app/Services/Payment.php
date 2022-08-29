<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use App\Services\Payment\Click\Click;
use App\Services\Payment\Converter as PaymentConverter;
use App\Services\Payment\PaymentException;
use App\Services\Payment\Payze\Payze;
use App\Services\Payment\Paynet\Paynet;
use App\Services\Payment\ResponseException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

/**
 * Payment service
 * Motivated: PayUz
 *
 * @see https://github.com/shaxzodbek-uzb/pay-uz
 */
class Payment extends PaymentConverter
{
    /**
     * Payment driver instance
     *
     * @var Payze|Paynet|Click|null
     */
    protected Payze|Paynet|Click|null $paymentDriver = null;

    /**
     * Payment service constructor
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Select payment driver
     *
     * @param string|null $driver
     * @return Payment
     *
     * @throws PaymentException
     */
    public function driver(string $driver = null): Payment
    {
        $this->paymentDriver = match ($driver) {
            'paynet' => new Paynet,
            'click' => new Click,
            'payze' => new Payze,
            default => throw new PaymentException('Unknown driver'),
        };

        return $this;
    }

    /**
     * Handling payment driver
     *
     * @return JsonResponse|null
     *
     * @throws PaymentException
     */
    public function handle(): JsonResponse|null
    {
        $this->validateDriver();

        try {
            $this->paymentDriver->run();
        } catch (ResponseException $e) {
            return response()->json($e->response());
        }

        return null;
    }

    /**
     * Redirect to payment system
     *
     * @param User $model
     * @param float|int $amount
     * @param int $currency
     * @param string $url
     * @return View
     *
     * @throws PaymentException
     */
    public function redirect(
        User $model,
        float|int $amount,
        int $currency = Transaction::CURRENCY_CODE_UZS,
        string $url = Transaction::RETURN_URL
    ): View {
        $this->validateDriver();
        $driver = $this->paymentDriver;

        $params = $driver->getRedirectParams($model, $amount, $currency, $url);
        return view('payment.redirect', compact('params'));
    }

    /**
     * Validate payment driver
     *
     * @return void
     *
     * @throws PaymentException
     */
    public function validateDriver(): void
    {
        if (is_null($this->paymentDriver))
            throw new PaymentException('Payment driver not selected');
    }

    /**
     * Validate payment model
     *
     * @param User|null $model
     * @param int|float|null $amount
     * @param int|null $currency
     * @return void
     *
     * @throws PaymentException
     */
    public function validateModel(?User $model, int|float|null $amount, ?int $currency): void
    {
        if (is_null($model))
            throw new PaymentException('Model can\'t be null');

        if (is_null($amount) || ! Payment::isProperAmount($amount))
            throw new PaymentException('Amount can\'t be null or >500');

        if (is_null($currency))
            throw new PaymentException('Currency code can\'t be null');
    }

    /**
     * Change driver description status
     *
     * @param bool $hasDescription
     * @return Payment
     *
     * @throws PaymentException
     */
    public function setDescription(bool $hasDescription): Payment
    {
        $this->validateDriver();
        $this->paymentDriver->setDescription($hasDescription);

        return $this;
    }
}
