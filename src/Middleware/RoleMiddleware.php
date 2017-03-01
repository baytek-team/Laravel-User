<?php
namespace Baytek\Laravel\Users\Middleware;

use Baytek\Laravel\Users\Roles\Administrator;
use Baytek\Laravel\Users\Roles\Root;

use Illuminate\Foundation\Application;

use Auth;
use Closure;

class RoleMiddleware
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Send the user back to their default redirection link
     * @return mixed
     */
    private function redirect() {
        // $link = Menu::link(Auth::user()->redirectTo);

        return abort(403);

        // return redirect($link)
        //     ->with(['errors', 'You don\'t have rights to do that.'], 'global');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role, $permission = null)
    {
        if(Auth::guest()) {
            return redirect(url(config('auth.login')));
        }

        if($request->user()->hasRole(Root::ROLE)) {
            return $next($request);
        }

        if(! $request->user()->hasRole(ucfirst($role))) {
            if($request->user()->can($permission)) {
               return $next($request);
            }

            return $this->redirect();
        }
        else {
            if(is_null($permission)) {
                return $next($request);
            }
        }

        if(! $request->user()->can($permission)) {
            return $this->redirect();
        }

        return $next($request);
    }
}

