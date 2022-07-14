<?php

namespace App\Notifications;

use App\Channel\PhoneChannel;
use App\Services\MobileService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification as AppNotification;

class Notification extends AppNotification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via(mixed $notifiable): array
    {
        return [PhoneChannel::class, 'mail'];
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @return array<string, string|integer|null>
     *
     * @see \App\Channel\PhoneChannel
     */
    public function toPhone(mixed $notifiable): array
    {
        $phone = 998900371461;
        $message = "Ok";

        return (new MobileService)
            ->send($phone, $message);
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            //
        ];
    }
}
