<?php

namespace App\Channel;

use Illuminate\Notifications\Notification;

class PhoneChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification|mixed $notification
     * @return mixed
     */
    public function send(mixed $notifiable, Notification $notification): mixed
    {
        return $notification->toPhone($notifiable);
    }
}
