@extends('contents::admin')

@section('page.head.header')
    <h1 class="ui header">
        <i class="table icon"></i>
        <div class="content">
            Permission Matrix
            <div class="sub header">
                <q>It means fasten your seat belt Dorothy, 'cause Kansas is going bye-bye.</q><br>
                <cite>Cypher</cite>
            </div>
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

@section('outer-content')

<div class="ui top attached tabular menu">
    <a class="item active" data-tab="role-permissions">Role Permissions</a>
    <a class="item" data-tab="user-roles">User Roles</a>
    {{-- <a class="item" data-tab="user-permissions">User Permissions</a> --}}
</div>
<div class="ui bottom attached tab padded segment active" data-tab="role-permissions" style="border-width:1px;">
    <form id="role-permissions" action="{{ route('user.role.save_role_permissions') }}" method="POST">
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
                @foreach ($permissions as $permission)
                    <tr>
                        <td class="four wide">{{ $permission->name }}</td>
                        @foreach ($roles as $role)
                            <td>
                                <div class="ui toggle checkbox">
                                    <input type="checkbox" name="{{ $role->name }}[{{ $permission->name }}]" @if($role->hasPermissionTo($permission))checked="checked"@endif />
                                </div>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="ui hidden divider"></div>
        <div class="ui secondary menu">
            <div class="right item">
                <button type="submit" class="ui primary icon labeled button"><i class="save icon"></i> Save Role Permissions</button>
            </div>
        </div>
    </form>
</div>

<div class="ui bottom attached tab padded segment" data-tab="user-roles" style="border-width:1px;">
    <form id="user-roles" action="{{ route('user.role.save_user_roles') }}" method="POST">
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
                @foreach ($users as $user)
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
                @endforeach
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

{{-- <div class="ui bottom attached tab padded segment" data-tab="user-permissions">
    <form id="user-permissions" action="{{ route('user.role.save_user_permissions') }}" method="POST">
        {{ csrf_field() }}
        <table class="ui selectable celled very basic table">
            <thead>
                <tr>
                    <th></th>
                    @foreach ($permissions as $permission)
                        <th class="rotate"><div>{{ $permission->name }}</div></th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td class="four wide">{{ $user->name }}</td>
                        @foreach ($permissions as $permission)
                            <td>
                                <div class="ui toggle checkbox">
                                    <input type="checkbox" name="{{ $permission->name }}[{{ $user->id }}]" @if($user->hasPermissionTo($permission))checked="checked"@endif />
                                </div>
                            </td>
                        @endforeach
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

<style>
th.rotate {
    /* Something you can count on */
    height: 180px;
    white-space: nowrap;
}

th.rotate > div {
    transform:
    /* Magic Numbers */
    translate(25px, 51px)
    /* 45 is really 360 - 45 */
    rotate(290deg);
    width: 20px;
}
</style> --}}

@endsection
