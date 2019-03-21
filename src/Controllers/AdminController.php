<?php

namespace Baytek\Laravel\Users\Controllers;

use App;
use Auth;
use Hash;

use Baytek\Laravel\Users\Controllers\Controller;
use Baytek\Laravel\Users\Events\MemberCreatedEvent;
use Baytek\Laravel\Users\Events\SendPasswordResetLink;
use Baytek\Laravel\Users\Events\UserEvent;
use Baytek\Laravel\Users\Member;
use Baytek\Laravel\Users\Middleware\RootProtection;
use Baytek\Laravel\Users\Requests\MemberRequest;
use Baytek\Laravel\Users\Roles\Administrator;
use Baytek\Laravel\Users\Roles\Member as MemberRole;
use Baytek\Laravel\Users\Roles\Root;
use Baytek\Laravel\Users\Scopes\ApprovedMemberScope;
use Baytek\Laravel\Users\User;
use Baytek\Laravel\Users\UserMeta;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(RootProtection::class)->except('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $this->authorize('view', Member::class);
        $this->authorize('View Member');

        // Get the search criteria
        $search  = (!is_null($request->search))? "%{$request->search}%" : '';

        // Get the members based on the search criteria
        $members = (!empty($search))
            ? Member::role(MemberRole::ROLE)
                    ->where('name', 'like', [$search])
                    ->orWhere('email', 'like', [$search])
                    ->approved()
                    ->orderBy('name', 'asc')
                    ->paginate()
            : Member::role(MemberRole::ROLE)
                    ->approved()
                    ->orderBy('name', 'asc')
                    ->paginate();

        return view('members::member.index', [
            'members' => $members,
            'filter' => 'active'
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function administrators()
    {
        // $this->authorize('view', Member::class);
        $this->authorize('View Member');

        return view('members::member.administrators', [
            'members' => User::role([Root::ROLE, Administrator::ROLE])
                ->orderBy('name', 'asc')
                ->paginate(),
            'filter' => 'active'
        ]);
    }

    /**
     * Show the form for creating a new member
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('Create Member');

        return view('members::member.create', [
            'user' => new Member,
            'roles' => Role::all(),
            'users' => User::all(),
            'permissions' => Permission::all(),
        ]);
    }

    /**
     * Store a new member
     */
    public function store(MemberRequest $request)
    {
        //Save the user
        $member = new Member($request->all());
        $member->name = $request->meta['first_name'] . ' ' . $request->meta['last_name'];
        $member->password = bcrypt(Hash::make(str_random(8)));
        $member->onBit(Member::APPROVED);
        $member->save();

        //Save the user meta
        foreach($request->meta as $key => $value) {
            $metaRecord = (new UserMeta([
                'language' => \App::getLocale(),
                'key' => $key,
                'value' => $value
            ]));
            $member->meta()->save($metaRecord);
            $metaRecord->save();
        }

        //Add the Member role
        $memberRole = Role::findByName('Member');
        // $user = User::find($member);
        $member->assignRole($memberRole);

        // Trigger the user was created this will send an email to the user
        // event(new RegistrationConfirmation($user));

        // Update the cache
        event(new UserEvent($member));

        // Advertise the new member to anyone who might be interested
        event(new MemberCreatedEvent($member, $request->all()));

        flash('Member Created');

        return redirect(route(
            Auth::user()->can('View Member') ?
                'members.index' :
                'admin.index'
            )
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Member $member)
    {
        $this->authorize('update', $member);

        return view('members::member.edit', [
            'user' => $member,
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
        return view('users::user.roles', [
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
    public function update(MemberRequest $request, Member $member)
    {
        //$user = User::find($member->id)->first();
        $user = $member;

        $this->authorize('update', $user);

        $request->merge(['name' => $request->meta['first_name'] . ' ' . $request->meta['last_name']]);
        $user->update($request->all());

        foreach($request->meta as $key => $value) {
            if($meta = $user->getMetaRecord($key)) {
                $meta->value = $value;
                $meta->update();
            }
            else {
                $metaRecord = (new UserMeta([
                    'language' => \App::getLocale(),
                    'key' => $key,
                    'value' => $value
                ]));
                $user->meta()->save($metaRecord);
                $metaRecord->save();
            }
        }

        // Update the cache
        event(new UserEvent($user));

        flash('Member Updated');

        return redirect(route(
            Auth::user()->can('View Member') ?
                'members.index' :
                'admin.index'
            )
        );
    }

    /**
     * Send a password reset link to a user
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sendPasswordResetLink($id)
    {
        $user = User::find($id);

        // Trigger the user was created this will send an email to the user
        event(new SendPasswordResetLink($user));

        flash('Password reset email sent!');

        // Go to the edit user page in the admin
        return redirect(route('members.edit', $user));
    }

    /**
     * Show the index of all deleted members
     *
     * @return \Illuminate\Http\Response
     */
    public function deleted(Request $request)
    {
        // $this->authorize('view', Member::class);
        $this->authorize('View Member');

        // Get the search criteria
        $search  = (!is_null($request->search))? "%{$request->search}%" : '';

        // Get the members based on the search criteria
        $members = (!empty($search))
            ? Member::withoutGlobalScope(ApprovedMemberScope::class)
                    ->where('name', 'like', [$search])
                    ->orWhere('email', 'like', [$search])
                    ->declined()
                    ->orderBy('name', 'asc')
                    ->paginate()
            : Member::withoutGlobalScope(ApprovedMemberScope::class)
                    ->role(MemberRole::ROLE)
                    ->declined()
                    ->orderBy('name', 'asc')
                    ->paginate();

        return view('members::member.index', [
            'members' => $members,
            'filter' => 'deleted'
        ]);
    }

    /**
     * Show the index of all pending members
     *
     * @return \Illuminate\Http\Response
     */
    public function pending(Request $request)
    {
        // $this->authorize('view', Member::class);
        $this->authorize('View Member');

        // Get the search criteria
        $search  = (!is_null($request->search))? "%{$request->search}%" : '';

        // Get the members based on the search criteria
        $members = (!empty($search))
            ? Member::withoutGlobalScope(ApprovedMemberScope::class)
                    ->where('name', 'like', [$search])
                    ->orWhere('email', 'like', [$search])
                    ->pending()
                    ->orderBy('name', 'asc')
                    ->paginate()
            : Member::withoutGlobalScope(ApprovedMemberScope::class)
                    ->role(MemberRole::ROLE)
                    ->pending()
                    ->orderBy('name', 'asc')
                    ->paginate();

        return view('members::member.index', [
            'members' => $members,
            'filter' => 'pending'
        ]);
    }

    /**
     * Set the status of the member to approved
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request, $id)
    {
        $member = Member::withoutGlobalScopes()->find($id);

        $this->authorize('update', $member);

        $member->offBit(Member::DELETED);
        $member->onBit(Member::APPROVED)->update();

        // Update the cache
        event(new UserEvent($member));

        flash('Member Approved');

        return redirect()->back();
    }

    /**
     * Set the status of the member to declined
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function decline(Request $request, $id)
    {
        $member = Member::find($id);

        $this->authorize('update', $member);

        $member->offBit(Member::APPROVED);
        $member->onBit(Member::DELETED)->update();

        flash('Member Deleted');

        return redirect()->back();
    }

}
