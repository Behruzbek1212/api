<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
class RespondMessageNotification extends Notification  implements ShouldBroadcast
{
    protected  $from;
    protected  $job;
    protected $resume;
    protected $chat;
    protected ?string $message;
    protected $role;
    public function __construct(array $data)
    {   
        $this->from = $data['from'];
        $this->job = $data['job'];
        $this->resume = $data['resume'] ?? [];
        $this->chat = $data['chat'];
        $this->message = $data['message'] ?? null;
        $this->role = $data['role'] ?? null;
    }

    public function via($notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable): array
    {
        
        return [
            'type' => 'respond',
            'role' => $this->role,
            'from' => $this->arrFrom($this->from) ?? null,
            'job' => [
                'id' => $this->job['id'],
                'title' => $this->job['title'],
                'slug' => $this->job['slug'],
            ],
            'resume' => $this->resume ? [
                'id' => $this->resume['id'] ?? null,
                'position' => $this->resume['data']['position'] ?? null,
            ] : [] ?? null,
            'chat' => [
                'id' =>    $this->chat['id'] ?? null,
                'status' =>    $this->chat['status'] ?? null,
            ] ?? null,
            'message' => $this->message ?? null,
        ];
    }
   

    public function toBroadcast($notifiable)
    {
        $notification = [
            
                'type' => 'respond',
                'role' => $this->role,
                'from' => $this->arrFrom($this->from) ?? null,
                'job' => [
                    'id' => $this->job['id'],
                    'title' => $this->job['title'],
                    'slug' => $this->job['slug'],
                ],
                'resume' => $this->resume ? [
                    'id' => $this->resume['id'] ?? null,
                    'position' => $this->resume['data']['position'] ?? null,
                ] : [] ?? null,
                'chat' => [
                    'id' =>    $this->chat['id'] ?? null,
                    'status' =>    $this->chat['status'] ?? null,
                ] ?? null,
                'message' => $this->message ?? null,
        ];

        return new BroadcastMessage([
            'notification' => $notification
        ]);
    }

    public function arrFrom($data)
    {
        $userRole = $data['role'];
        if($userRole == 'candidate'){
            $from = [
                'id' => $this->from['id'],
                'phone' => $this->from['phone'],
                'role' => $this->from['role'],
                'candidate' => [
                    'id' => $this->from['candidate']['id'] ?? null,
                    'name' => $this->from['candidate']['name'] ?? null,
                    'surname' => $this->from['candidate']['surname'] ?? null,
                    'avatar' => $this->from['candidate']['avatar'] ?? null,
                    'birthday' => $this->from['candidate']['birthday'] ?? null,
                ] ?? null,
                
            ];

            return $from;
        } elseif($userRole == 'customer')
        {
            $from = [
                'id' => $this->from['id'],
                'phone' => $this->from['phone'],
                'role' => $this->from['role'],
                'customer' => [
                    'id' => $this->from['customer']['id'] ?? null,
                    'name' => $this->from['customer']['name'] ?? null,
                    'avatar' => $this->from['customer']['avatar'] ?? null,
                ] ?? null,
                ];

            return $from;    
        }

        return [];
    }
 
}
