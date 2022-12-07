<?php

namespace App\Listeners;

use App\Events\Registered;
use App\Services\MobileService;
use Nette\Utils\Random;

class SendPhoneVerification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param Registered $event
     * @return void
     */
    public function handle(Registered $event): void
    {
        if (!$event->user->existPhone()) {
            return;
        }

        $verification = Random::generate(5, '0-9');

        (new MobileService)
            ->send($event->user->phone, __('mobile.send.verification_code', ['code' => $verification]));
    }
}
