@extends('contents::admin')

@section('page.head.header')
    <h1 class="ui header">
        <i class="user icon"></i>
        <div class="content">
            User Management
            <div class="sub header">
                Manage the users of the claims application.

                <div class="warning message">

                </div>
            </div>
        </div>
    </h1>
@endsection


@section('outer-content')
    <div class="ui icon warning message">
        <i class="fire extinguisher icon"></i>
        <div class="content">
            <div class="header">
                This is dangerous
            </div>
            <p>
                Be aware that changing this information may severely damage the system. <br>
                <ul>
                    <li>Changing the users password may confuse the user</li>
                    <li>Changing email addresses can have huge ramifications</li>
                    <li>You are root, thus you have great power</li>
                </ul>
                <strong>THINK BEFORE ACTING. YOU ARE RESPONSIBLE FOR ALL DAMAGES!</strong>
            </p>
        </div>
    </div>
@endsection

@section('content')
    <form class="ui form" action="{{route('user.update', $user)}}" method="POST">
        {{ csrf_field() }}
        {{ method_field('PUT') }}

        @include('user::user.form')
        <div class="ui hidden divider"></div>
        <div class="ui hidden divider"></div>

        <div class="ui error message"></div>
        <div class="field actions">
            <a class="ui button" href="{{ route('user.index') }}">Cancel</a>
            <button type="submit" class="ui right floated primary button">
                Update User
            </button>
        </div>
    </form>
@endsection