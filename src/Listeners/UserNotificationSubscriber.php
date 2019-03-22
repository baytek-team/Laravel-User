<?php

namespace Baytek\Laravel\Users\Listeners;

use Baytek\Laravel\Users\Events\UserEvent;
use Baytek\Laravel\Users\Member;

use Cache;

class UserNotificationSubscriber
{
    /**
     * Handle user login events.
     */
    public function handle($event)
    {
        // $event->user
    }

    /**
     * Cache the content id value pairs for quicker lookups
     * @param  Baytek\Laravel\Content\Events\UserEvent  $event Content event class
     * @return void
     */
    public function cache($event)
    {
        $dates = Member::role('Member')->select(['id', 'updated_at'])->get();

        $timestamps = collect([]);

        $dates->each(function(&$item) use (&$timestamps) {
            $timestamps->put($item->id, (string)$item->updated_at);
        });

        $json = $timestamps->toJson();

        Cache::forever('user.cache.hash', md5($json));
        Cache::forever('user.cache.json', $json);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            UserEvent::class,
            static::class.'@handle'
        );

        $events->listen(
            UserEvent::class,
            static::class.'@cache'
        );
    }
}
