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
    <form class="ui form" action="{{route('user.store')}}" method="POST">
        {{ csrf_field() }}

        @include('user::user.form')
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
