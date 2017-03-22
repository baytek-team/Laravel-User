<?php

namespace Baytek\Laravel\Users\Events;

use Baytek\Laravel\Users\User;

use Illuminate\Support\Str;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;

use App;

class SendPasswordResetLink
{
    use InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $key = config('app.key');

        if (\Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }

        $this->type = 'UserPasswordResetLink';
        $this->title = 'Reset Password for '.config('app.name');
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
        return new PrivateChannel('eohu');
    }
}
