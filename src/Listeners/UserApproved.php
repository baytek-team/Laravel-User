<?php

namespace Baytek\Laravel\Users\Listeners;

use DB;
use App\Mail\SystemEmail;

use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

/**
 * An event listener intended for use where there is account moderation.
 * Same as ResetPassword except extends password reset expiry to all more time
 * for users to login for the first time.
 * 
 * @see   Baytek\Laravel\Users\Listeners\ResetPassword
 */
class UserApproved
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
        // Adding 7 days right now. TODO: make it a config setting.
        DB::table('password_resets')->insert([
            'email' => $event->user->email,
            'token' => bcrypt($event->parameters['token']),
            'created_at' => Carbon::now()->addDays(7)->toDateTimeString(),
        ]);
    }
}
