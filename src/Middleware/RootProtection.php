<?php

namespace Baytek\Laravel\Users\Middleware;

use Closure;

use Baytek\Laravel\Users\User;
use Baytek\Laravel\Users\Roles\Administrator;
use Baytek\Laravel\Users\Roles\Root;

use Illuminate\Http\Request;

class RootProtection
{
    /**
     * Handle an incoming request. Make sure we respect our Roots!
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // If you are trying to do something to the root user get out of here!!!
        if(isset($request->user) && $request->user->id == 1) {

            // If you aren't the root user, none shall pass!!!
            if($request->user()->id != 1) {
                return abort(403);
            }

            // If you're trying to delete the user - shame on you, naughty boy...or girl
            if($request->method() == 'DELETE') {
                return abort(403);
            }
        }

        // If you are trying to modify a user with a root role tread carefully. I am watching you...get your hand off of that!
        if(isset($request->user) && $request->user->hasRole(Root::ROLE)) {

            // If the current logged in user does not have the Root role, fuggedaboutit!!!
            if($request->user()->hasRole(Root::ROLE) == false) {
                return abort(403);
            }
        }

        return $next($request);
    }
}
