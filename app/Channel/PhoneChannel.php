<?php

namespace App\Channel;

use Illuminate\Notifications\Notification;

class PhoneChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param mixed|Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        return $notification->toPhone($notifiable);
    }
}
