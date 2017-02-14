<?php

namespace Baytek\Laravel\Users\Controllers;

use Auth;

use Baytek\Laravel\Users\User;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;

// use Spatie\Permission\Models\Permission;
// use Spatie\Permission\Models\Role;

class ProfileController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('User::user.profile', [
        	'user' => User::find(1),
        ]);
    }

}
