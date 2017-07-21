@extends('user::permission.template')

@section('content')
<div id="registration" class="et_pb_column ui container">
    <form class="ui form" action="{{route('permission.store')}}" method="POST">
        {{ csrf_field() }}

        @include('user::permission.form')
        <div class="ui hidden divider"></div>
        <div class="ui hidden divider"></div>

        <div class="ui error message"></div>
        <div class="field actions">
            <a class="ui button" href="{{ route('permission.index') }}">Cancel</a>
            <button type="submit" class="ui right floated primary button">
                Create Permission
            </button>
        </div>

    </form>
</div>
@endsection
