<?php

namespace Baytek\Laravel\Users\Controllers;

use Hash;
use Validator;

use Baytek\Laravel\Users\Member;
use Baytek\Laravel\Users\Controllers\Controller;

use Baytek\Laravel\Users\User;
use Baytek\Laravel\Users\UserMeta;
use Baytek\Laravel\Users\Events\SendPasswordResetLink;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


use Illuminate\Http\Request;

class WebController extends Controller
{
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|email|max:255|exists:users,email',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendResetLinkEmail(Request $request)
    {
        //Validate the request
        $validator = $this->validator($request->all());

        if($validator->fails())
        {
            return back()
               ->withErrors($validator)
               ->withInput();
        }

        //Find the user
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Trigger the password reset, this will send an email to the user
            event(new SendPasswordResetLink($user));
        }

        return back()->with('status', 'A password reset email has been sent.');
    }
}
