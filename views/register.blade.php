@extends('content::admin')

@section('content')

<div class="ui centered grid">
    <div class="sixteen wide tablet ten wide computer column">
        <div class="ui very padded segment">
            <h1 class="ui header">Register New Account</h1>
            <form class="ui form" role="form" method="POST" action="{{ url('/admin/register') }}">
                {{ csrf_field() }}

                <div class="field{{ $errors->has('name') ? ' error' : '' }}">
                    <label for="name" class="screen-reader-text">Name</label>
                    <input id="name" type="text" class="form-control" name="name" placeholder="Name" value="{{ old('name') }}" required autofocus>
                </div>

                <div class="field{{ $errors->has('email') ? ' error' : '' }}">
                    <label for="email" class="screen-reader-text">E-Mail Address</label>
                    <input id="email" type="email" class="form-control" name="email" placeholder="E-Mail Address" value="{{ old('email') }}" required>
                </div>

                <div class="field{{ $errors->has('password') ? ' error' : '' }}">
                    <label for="password" class="screen-reader-text">Password</label>
                    <input id="password" type="password" class="form-control" name="password" placeholder="Password" value="{{ old('password') }}" required autofocus>
                </div>

                <div class="field{{ $errors->has('password_confirmation') ? ' error' : '' }}">
                    <label for="password_confirmation" class="screen-reader-text">Confirmation</label>
                    <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" placeholder="password_confirmation" value="{{ old('password_confirmation') }}" required autofocus>
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
