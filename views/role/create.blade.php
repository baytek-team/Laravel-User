@extends('user::role.template')

@section('content')
<div id="registration" class="et_pb_column ui container">
    <form class="ui form" action="{{route('role.store')}}" method="POST">
        {{ csrf_field() }}

        @include('user::role.form')
        <div class="ui hidden divider"></div>
        <div class="ui hidden divider"></div>

        <div class="ui error message"></div>
        <div class="field actions">
            <a class="ui button" href="{{ route('role.index') }}">Cancel</a>
            <button type="submit" class="ui right floated primary button">
                Create Role
            </button>
        </div>

    </form>
</div>
@endsection
