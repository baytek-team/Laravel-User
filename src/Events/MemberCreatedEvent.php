<?php

namespace Baytek\Laravel\Users\Events;

use Illuminate\Support\Str;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MemberCreatedEvent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $user;
    public $params;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $params)
    {
        $this->user = $user;
        $this->params = $params;

        $key = config('app.key');

        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }

        $this->type = 'UserWelcome';
        $this->title = ___('Account creation for ').___(config('app.name'));
        $this->user = $user;
        $this->parameters = [
            'token' => hash_hmac('sha256', Str::random(40), $key)
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('users');
    }
}
