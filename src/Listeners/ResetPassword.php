<?php

namespace Baytek\Laravel\Users\Listeners;

use DB;
use App\Mail\SystemEmail;

use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class ResetPassword
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
        //First remove any existing password resets for this email
        DB::table('password_resets')
            ->where('email', $event->user->email)
            ->delete();

        //Now insert a new password reset record
        DB::table('password_resets')->insert([
            'email' => $event->user->email,
            'token' => bcrypt($event->parameters['token']),
            'created_at' => Carbon::now()->toDateTimeString(),
        ]);
    }
}
