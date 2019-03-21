@extends('members::member.template')

@section('content')
    <form class="ui form" action="{{route('members.store')}}" method="POST">
        {{ csrf_field() }}

        @include('members::member.form')
        <div class="ui hidden divider"></div>
        <div class="ui hidden divider"></div>

        <div class="ui error message"></div>
        <div class="field actions">
            <a class="ui button" href="{{ route('members.index') }}">{{ ___('Cancel') }}</a>
            <button type="submit" class="ui right floated primary button">
                {{ ___('Create') }}
            </button>
        </div>
    </form>
@endsection