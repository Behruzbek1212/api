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
     * @param  \App\Events\Registered $event
     * @return void
     */
    public function handle(Registered $event)
    {
        if (!$event->user->existPhone()) {
            return;
        }

        $verification = Random::generate(5, '0-9');
        $message = "Jobo.uz | Код подтверждение: " . $verification;
        $playmobile = new MobileService();
        $playmobile->send($event->user->phone, $message);
    }
}
