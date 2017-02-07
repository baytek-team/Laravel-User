@extends('Content::admin')
@section('content')
<div class="ui grid">
    <div class="two column row">

        <h1 class="ui header left floated column">
            <i class="user icon"></i>
            <div class="content">
                User Management
                <div class="sub header">Manage the users of the claims application.</div>
            </div>
        </h1>
        <div class="right floated column">
            <div class="ui secondary menu">
                <a class="ui basic primary button right item" href="{{ route('user.create') }}">
                    <i class="user icon"></i>Add User
                </a>
            </div>
        </div>
    </div>
</div>

<div class="ui hidden divider"></div>
<div class="ui hidden divider"></div>

<div class="ui secondary menu">
    <div class="item"><strong>Sort By:</strong></div>

    <div class="right menu">
        <form class="{{ count($errors) != 0 ? ' error' : '' }}" method="GET">
            <div class="ui left icon right action input">
                <input type="text" placeholder="Search users..." name="search" value="{{ collect(Request::instance()->query)->get('search') }}">
                <i class="users icon"></i>
                <button type="submit" class="ui primary button">Search</button>
            </div>
        </form>
    </div>
</div>
<table class="ui selectable table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th class="right aligned">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td class="collapsing right aligned">
                    <a class="ui basic primary button" href="{{ route('user.edit', ['user' => $user]) }}">
                        <i class="user icon"></i>
                        Edit User
                    </a>
                    <a class="ui basic button" href="{{ route('user.roles', ['user' => $user]) }}">
                        <i class="user icon"></i>
                        Manage Roles
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- pagination start -->
<div class="ui hidden divider"></div>
<!-- pagination end -->

@endsection