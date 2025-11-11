<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotifyUserEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id;
    public $message;
    public $success;

    public function __construct($user_id, $message, $success)
    {
        $this->user_id = $user_id;
        $this->message = $message;
        $this->success = $success;
    }

    public function broadcastOn()
    {
        return new Channel('notify-user');
    }

    public function broadcastAs()
    {
        return 'my-event';
    }
}
