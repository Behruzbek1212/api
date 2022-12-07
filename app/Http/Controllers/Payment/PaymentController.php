<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Payment;
use App\Services\Payment\PaymentException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use JsonException;
use PayzeIO\LaravelPayze\Exceptions\UnsupportedCurrencyException;
use Throwable;

class PaymentController extends Controller
{
    /**
     * @return RedirectResponse
     */
    public function index()
    {
        return redirect('https://jobo.uz');
    }

    /**
     * @param string $pay_system
     *
     * @return Response|null
     * @throws PaymentException
     */
    public function handle(string $pay_system): ?Response
    {
        return (new Payment)
            ->driver($pay_system)
            ->handle();
    }

    /**
     * @param string $pay_system
     * @param int $id
     * @param float|int $amount
     * @param int|string $currency
     *
     * @return View
     * @throws PaymentException|Throwable|UnsupportedCurrencyException|JsonException
     */
    public function redirect(string $pay_system, int $id, float|int $amount, int|string $currency = 860): View
    {
        $model = User::query()->find($id);

        if (! $model)
            throw new PaymentException('Model not found.');

        return (new Payment)
            ->driver($pay_system)
            ->redirect($model, $amount, $currency);
    }
}
