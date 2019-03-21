@extends('members::member.template')

@section('content')
    <form class="ui form" action="{{route('members.update', $user)}}" method="POST">
        {{ csrf_field() }}
        {{ method_field('PUT') }}

        @include('members::member.form')
        <div class="ui hidden divider"></div>
        <div class="ui hidden divider"></div>

        <div class="ui error message"></div>
        <div class="field actions">
            <a class="ui secondary button" href="{{ route('members.index') }}">{{ ___('Cancel') }}</a>
            <button type="submit" class="ui right floated primary button">
                {{ ___('Update') }}
            </button>
        </div>
    </form>
@endsection