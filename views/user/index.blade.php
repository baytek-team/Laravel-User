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
            <a href="{{ route('user.create') }}" class="ui icon labeled button"><i class="user icon"></i>Add User</a>
        </div>
    </div>
@endsection

@section('content')
{{-- <div class="ui secondary menu">
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
</div> --}}
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
                    <div class="ui compact text menu">
                        <a href="{{ route('user.edit', ['user' => $user]) }}" class="item">
                            <i class="pencil icon"></i>Edit
                        </a>
                        <a class="item" href="{{ route('user.roles', ['user' => $user]) }}">
                            <i class="user icon"></i> Manage Roles
                        </a>
                        {!! new Baytek\Laravel\Menu\Button('Delete', [
                            'class' => 'item action',
                            'location' => 'user.destroy',
                            'method' => 'delete',
                            'model' => $user,
                            'prepend' => '<i class="delete icon"></i>',
                            'type' => 'route',
                            'confirm' => 'Are you sure you want to delete this user?'
                        ]) !!}
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- pagination start -->
<div class="ui hidden divider"></div>
<!-- pagination end -->

@endsection
