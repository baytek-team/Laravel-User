@extends('Content::admin')

@section('content')

<div class="ui centered grid">
    <div class="sixteen wide tablet ten wide computer column">
        <div class="ui very padded segment">
            <h1 class="ui header">Register New Account</h1>
            <form class="ui form" role="form" method="POST" action="{{ url('/admin/register') }}">
                {{ csrf_field() }}

                <div class="field{{ $errors->has('first_name') ? ' error' : '' }}">
                    <label for="first_name" class="screen-reader-text">First Name</label>
                    <input id="first_name" type="text" class="form-control" name="first_name" placeholder="First Name" value="{{ old('first_name') }}" required autofocus>
                </div>
                <div class="field{{ $errors->has('last_name') ? ' error' : '' }}">
                    <label for="last_name" class="screen-reader-text">Last Name</label>
                    <input id="last_name" type="text" class="form-control" name="last_name" placeholder="Last Name" value="{{ old('last_name') }}" required autofocus>
                </div>

                <div class="field{{ $errors->has('email') ? ' error' : '' }}">
                    <label for="email" class="screen-reader-text">E-Mail Address</label>
                    <input id="email" type="email" class="form-control" name="email" placeholder="E-Mail Address" value="{{ old('email') }}" required>
                </div>

                <div class="field">
                    <button type="submit" class="ui primary button">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="ui hidden divider"></div>

@endsection
