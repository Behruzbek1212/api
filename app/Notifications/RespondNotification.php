<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class RespondNotification extends Notification
{
    protected array $user;
    protected array $customer;
    protected array $job;
    protected ?string $message;

    public function __construct(array $data)
    {
        $this->user = $data['user'];
        $this->customer = $data['customer'];
        $this->job = $data['job'];
        $this->message = $data['message'];
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'chat',
            'icon' => 'auth',
            'message' => $this->message,
            'job' => $this->job,
            'from' => $this->user,
            'to' => $this->customer
        ];
    }
}
