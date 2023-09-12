<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendMessage
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $message;
    public $customer;
    public $candidate;
    public $resume;
    public $chat;
    public $role;
    public $job;
    /**
     * Create a new event instance.
     */
    public function __construct($message, $customer, $candidate, $resume, $chat, $role, $job)
    {
        $this->message =$message;
        $this->customer = $customer;
        $this->candidate =$candidate;
        $this->resume = $resume;
        $this->chat = $chat;
        $this->role = $role;
        $this->job = $job;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    // public function broadcastOn(): array
    // {
    //     return [
    //         new PrivateChannel('channel-name'),
    //     ];
    // }
}
