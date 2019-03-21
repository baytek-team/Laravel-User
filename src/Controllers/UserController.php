<?php

namespace Baytek\Laravel\Users\Controllers;

use Auth;

use Baytek\Laravel\Users\Middleware\RootProtection;
use Baytek\Laravel\Users\Requests\UserRequest;
use Baytek\Laravel\Users\User;
use Baytek\Laravel\Users\UserMeta;

use Illuminate\Http\Request;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function __construct()
    {
        // parent::__construct();
        $this->middleware(RootProtection::class)->except('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view', User::class);

        return view('user::user.index', [
            'users' => User::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', User::class);

        return view('user::user.create', [
            'user' => (new User()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $this->authorize('create', User::class);

        $user = new User($request->all());
        $user->save();

        return redirect(route('user.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
         $this->authorize('view', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return view('user::user.edit', [
            'user' => $user,
            'roles' => Role::all(),
            'users' => User::all(),
            'permissions' => Permission::all(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function roles(User $user)
    {
        return view('user::user.roles', [
            'user' => $user,
            'roles' => Role::all(),
            'permissions' => Permission::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        if(!empty($request->input('password'))) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->update($request->all());

        return redirect(route('user.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect(route('user.index'));
    }

    /**
     * Send a password reset link to a user
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sendPasswordResetLink(User $user)
    {
        // Trigger the user was created this will send an email to the user
        event(new \Baytek\Laravel\Users\Events\SendPasswordResetLink($user));

        // Go to the edit user page in the admin
        return redirect(route('user.edit', $user));
    }
}
