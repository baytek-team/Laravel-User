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

@section('content')
<div id="registration" class="et_pb_column ui container">
    <form class="ui form" action="{{route('user.update', $user)}}" method="POST">
        {{ csrf_field() }}
        {{ method_field('PUT') }}

        <div class="one fields">
            <div class="sixteen wide field{{ $errors->has('name') ? ' error' : '' }}">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Name" value="{{ old('name', $user->name) }}">
            </div>
        </div>
        <div class="one fields">
            <div class="sixteen wide field{{ $errors->has('email') ? ' error' : '' }}">
                <label for="email">Email Address</label>
                <input type="text" id="email" name="email" placeholder="Email Address" value="{{ old('email', $user->email) }}">
            </div>

        </div>
        <div class="one fields">
            <div class="sixteen wide field{{ $errors->has('password') ? ' error' : '' }}">
                <label for="password">Password</label>
                <input type="text" id="password" name="password" placeholder="Password" value="{{ old('password', $user->password) }}">
            </div>
        </div>

        <div class="ui hidden divider"></div>
        <div class="ui hidden divider"></div>

        <div class="ui error message"></div>
        <div class="field actions">
            <a class="ui button" href="{{ route('user.index') }}">Cancel</a>
            <button type="submit" class="ui right floated primary button">
                Update User Information
            </button>
        </div>

    </form>
</div>

@endsection