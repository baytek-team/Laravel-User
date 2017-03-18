<?php

namespace Baytek\Laravel\Users\Listeners;

use App\Mail\SystemEmail;

use Baytek\Laravel\Users\Events\SendPasswordResetLink;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class SendEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle($event)
    {
        if($event->user instanceof Collection) {
            $users = $event->user;

            $users->each(function ($user) use ($event) {
                $event->user = $user;
                Mail::to($event->user)->send(new SystemEmail($event));
            });
        }
        else {
            Mail::to($event->user)->send(new SystemEmail($event));
        }
    }
}
