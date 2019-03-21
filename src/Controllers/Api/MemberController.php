<?php

namespace Baytek\Laravel\Users\Controllers\Api;

use Baytek\Laravel\Users\File;
use Baytek\Laravel\Users\Member;
use Baytek\Laravel\Users\Requests\MemberRequest;
use Baytek\Laravel\Users\Requests\PasswordRequest;
use Baytek\Laravel\Users\Controllers\Controller;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Baytek\Laravel\Users\Events\UserEvent;

use Baytek\Laravel\Content\Models\Content;
use Baytek\Laravel\Users\User;
use Baytek\Laravel\Users\UserMeta;

use Mailchimp;

class MemberController extends Controller
{
    /**
     * Fields to set as private when saving member metadata
     * @var array
     */
    protected $private = [];

	public function all()
    {
        return Member::with('meta')->role('member')->get();
    }

    public function letters()
    {
        $prefix = env('DB_PREFIX');

        $firstNames = $lastNames = [];

        $firstLetters = \DB::select("SELECT DISTINCT LEFT(meta.value, 1) as title
            FROM ${prefix}user_meta meta
            LEFT JOIN ${prefix}users users ON meta.user_id = users.id
            WHERE meta.key = 'first_name'
            AND users.status & ?!=0
            AND CHAR_LENGTH(meta.value) > 0
            ORDER BY title ASC", [Member::APPROVED]);

        $lastLetters = \DB::select("SELECT DISTINCT LEFT(meta.value, 1) as title
            FROM ${prefix}user_meta meta
            LEFT JOIN ${prefix}users users ON meta.user_id = users.id
            WHERE meta.key = 'last_name'
            AND users.status & ?!=0
            AND CHAR_LENGTH(meta.value) > 0
            ORDER BY title ASC", [Member::APPROVED]);

        return [
            'firstLetters' => $firstLetters,
            'lastLetters' => $lastLetters,
        ];
    }

    public function byFirstName($letter) {
        return Member::select('users.id','users.name','users.status','users.created_at','users.updated_at')
            ->role('member')
            ->whereMetadata('first_name', strtoupper($letter).'%', 'like')
            ->orderByMeta('first_name', 'asc')
            ->get();
    }

    public function byLastName($letter) {
        return Member::select('users.id','users.name','users.status','users.created_at','users.updated_at')
            ->role('member')
            ->whereMetadata('last_name', strtoupper($letter).'%', 'like')
            ->orderByMeta('last_name', 'asc')
            ->get();
    }

    public function member(Member $member)
    {
    	return $member->load('meta');
    }

    public function profile()
    {
        return Member::withMeta(true)->with('roles')->find(\Auth::user()->id)->makeVisible('email');
    }

    /**
     * Update a member
     */
    public function update(MemberRequest $request, Member $member)
    {
        //Get the member
        $member = User::find($member->id);

        //Update user name from meta first_name and last_name
        $member->update($request->all());

        //Update all the meta
        foreach($request->meta as $key => $value) {
            if ($meta = $member->getMetaRecord($key)) {
                $meta->value = $value;
                $meta->update();
            }
            else {
                $metaRecord = (new UserMeta([
                    'language' => \App::getLocale(),
                    'status' => in_array($key, $this->private) ? UserMeta::RESTRICTED : 0,
                    'key' => $key,
                    'value' => $value
                ]));
                $member->meta()->save($metaRecord);
                $metaRecord->save();
            }
        }

        $this->fireUpdateEvents($request, $member);

        return response()->json([
            'status' => 'success',
            'member' => $this->profile(),
            'message' => ___('Profile successfully updated.'),
        ]);
    }

    public function fireUpdateEvents($request, $member)
    {
        // Update the cache
        event(new UserEvent($member));
    }

    /**
     * Update a member's password
     */
    public function updatePassword(PasswordRequest $request, Member $member)
    {
        if (Auth::validate(['email' => Auth::user()->email, 'password' => $request->old_password])) {

            //Borrowed from Illuminate\Foundation\Auth\ResetsPasswords@resetPassword
            $member->forceFill([
                'password' => bcrypt($request->password),
                'remember_token' => Str::random(60),
            ])->save();

            return response()->json([
                'status' => 'success',
                'message' => ___('Password successfully updated.'),
            ]);
        }
        else {
            return response()->json([
                'status' => 'error',
                'errors' => [___('Old password is incorrect. Please try again.')],
            ]);
        }
    }
}
