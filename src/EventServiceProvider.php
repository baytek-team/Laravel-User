<?php

namespace Baytek\Laravel\Users;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as Provider;

class EventServiceProvider extends Provider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Baytek\Laravel\Users\Events\SendPasswordResetLink' => [
            'Baytek\Laravel\Users\Listeners\ResetPassword',
            'Baytek\Laravel\Users\Listeners\SendEmail',
        ],
        'Baytek\Laravel\Users\Events\MemberCreatedEvent' => [
            'Baytek\Laravel\Users\Listeners\ResetPassword',
            'Baytek\Laravel\Users\Listeners\SendEmail',
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        Listeners\UserNotificationSubscriber::class,
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
