<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class RespondMessageNotification extends Notification
{
    protected array $from;
    protected array $job;

    public function __construct(array $data)
    {
        $this->from = $data['candidate'];
        $this->job = $data['job'];
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'notification',
            'job' => $this->job,
            'from' => $this->from
        ];
    }
}
