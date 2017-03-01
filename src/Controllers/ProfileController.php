<?php

namespace Baytek\Laravel\Users\Controllers;

use Auth;

use Baytek\Laravel\Users\User;

use Illuminate\Http\Request;

class ProfileController extends Controller
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
