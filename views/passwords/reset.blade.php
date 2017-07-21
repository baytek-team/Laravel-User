@extends('content::admin')

@section('content')

<div class="ui centered grid">
    <div class="sixteen wide tablet ten wide computer column">
        <div class="ui very padded segment">
            <h1 class="ui header">Set Password</h1>
            <form class="ui form" role="form" method="POST" action="{{ url('/admin/password/reset') }}">
                {{ csrf_field() }}
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="field{{ $errors->has('email') ? ' error' : '' }}">
                    <label for="email" class="screen-reader-text">E-Mail Address</label>
                    <input id="email" type="email" class="form-control" name="email" placeholder="E-Mail Address" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="field{{ $errors->has('password') ? ' error' : '' }}">
                    <label for="password" class="screen-reader-text">Password</label>
                    <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
                </div>
                <div class="field{{ $errors->has('password_confirmation') ? ' error' : '' }}">
                    <label for="password-confirm" class="screen-reader-text">Confirm Password</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required>
                </div>

                <div class="field actions">
                    <button type="submit" class="ui primary button">Set Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="ui hidden divider"></div>

@endsection
