@extends('content::admin')

@section('page.head.header')
    <h1 class="ui header">
        <i class="user icon"></i>
        <div class="content">
            User Management
            <div class="sub header">Manage the users of the claims application.</div>
        </div>
    </h1>
@endsection

@section('page.head.menu')
    <div class="ui secondary menu">
        <div class="right item">
            <a href="{{ route('permission.index') }}" class="ui icon labeled button"><i class="save icon"></i> Manage Permissions</a>
            &nbsp;
            <a href="{{ route('role.index') }}" class="ui icon labeled button"><i class="save icon"></i> Manage Roles</a>
        </div>
    </div>
@endsection

@section('content')
<div class="ui top attached tabular menu">
    <a class="item active" data-tab="user-roles">User Roles</a>
    <a class="item" data-tab="user-permissions">User Permissions</a>
</div>

<div class="ui bottom attached tab padded segment active" data-tab="user-roles">
    <form id="user-roles" action="{{ route('user.roles.save', $user) }}" method="POST">
        {{ csrf_field() }}
        <table class="ui selectable celled very basic table">
            <thead>
                <tr>
                    <th></th>
                    @foreach ($roles as $role)
                        <th>{{ $role->name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="four wide">{{ $user->name }}</td>
                    @foreach ($roles as $role)
                        <td>
                            <div class="ui toggle checkbox">
                                <input type="checkbox" name="{{ $role->name }}[{{ $user->id }}]" @if($user->hasRole($role))checked="checked"@endif />
                            </div>
                        </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
        <div class="ui hidden divider"></div>
        <div class="ui secondary menu">
            <div class="right item">
                <button type="submit" class="ui primary icon labeled button"><i class="save icon"></i> Save User Roles</button>
            </div>
        </div>
    </form>
</div>

<div class="ui bottom attached tab padded segment" data-tab="user-permissions">
    <form id="user-permissions" action="{{ route('user.permissions.save', $user) }}" method="POST">
        {{ csrf_field() }}
        <table class="ui selectable celled very basic table">
            <thead>
                <tr>
                    <th></th>
                    <th>{{ $user->name }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permissions as $permission)
                    <tr>
                        <td class="four wide">{{ $permission->name }}</td>
                        <td>
                            <div class="ui toggle checkbox">
                                <input type="checkbox" name="{{ $permission->name }}[{{ $user->id }}]" @if($user->hasPermissionTo($permission))checked="checked"@endif />
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="ui hidden divider"></div>
        <div class="ui secondary menu">
            <div class="right item">
                <button type="submit" class="ui primary icon labeled button"><i class="save icon"></i> Save Permissions</button>

            </div>
        </div>
    </form>
</div>


@endsection
