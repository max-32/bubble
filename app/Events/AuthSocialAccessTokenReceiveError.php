<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AuthSocialAccessTokenReceiveError
{
    use InteractsWithSockets, SerializesModels;

    public $auth20Class;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($auth20Class)
    {
        $this->auth20Class = $auth20Class;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
