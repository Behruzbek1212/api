<?php

namespace App\Events;

use App\Models\Candidate;
use App\Models\Job;
use App\Models\Resume;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TelegramSendNotification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $job;
    public $candidate;
    public $resume ;
    public $chat_id;
    public $message;
    public $customer;
    /**
     * Create a new event instance.
     */
    public function __construct( $job,  $candidate,  $resume, $chat_id, $message , $customer)
    {
        $this->job = $job;
        $this->candidate = $candidate;
        $this->resume = $resume ?? null;
        $this->chat_id = $chat_id;
        $this->message = $message;
        $this->customer = $customer;
    }

    // /**
    //  * Get the channels the event should broadcast on.
    //  *
    //  * @return array<int, \Illuminate\Broadcasting\Channel>
    //  */
    // public function broadcastOn(): array
    // {
    //     return [
    //         new PrivateChannel('channel-name'),
    //     ];
    // }
}
