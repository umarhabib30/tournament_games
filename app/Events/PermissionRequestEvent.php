<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PermissionRequestEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $username;

    public function __construct($message, $username)
    {
        $this->message = $message;
        $this->username = $username;
    }

    public function broadcastOn()
    {
        return new Channel('permission');
    }

    public function broadcastAs()
    {
        return 'my-event';
    }
}
