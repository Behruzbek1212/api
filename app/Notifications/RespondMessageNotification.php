<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
class RespondMessageNotification extends Notification  implements ShouldBroadcast
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
        return ['database','broadcast'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'notification',
            'job' => $this->job,
            'from' => $this->from
        ];
    }

    public function toBroadcast($notifiable){
        $notification = [
            "data" => [
                'type' => 'notification',
                'job' => $this->job,
                'from' => $this->from
            ]
        ];

        return new BroadcastMessage([
            'notification' => $notification
        ]);
    }
}
