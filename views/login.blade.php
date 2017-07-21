@extends('content::admin')

@section('content')

<div class="ui centered grid">
    <div class="sixteen wide tablet ten wide computer column">
        <div class="ui very padded segment">
            <h1 class="ui header">Login</h1>
            <form class="ui form" role="form" method="POST" action="{{ url('/admin/login') }}">
                {{ csrf_field() }}

                <div class="field{{ $errors->has('email') ? ' error' : '' }}">
                    <label for="email" class="screen-reader-text">E-Mail Address</label>
                    <input id="email" type="email" class="form-control" name="email" placeholder="E-Mail Address" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="field{{ $errors->has('password') ? ' error' : '' }}">
                    <label for="password" class="screen-reader-text">Password</label>
                    <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
                </div>

                <div class="field">
                    <div class="ui checkbox">
                        <input type="checkbox" class="hidden" id="remember" name="remember">
                        <label for="remember">Remember Me</label>
                    </div>
                </div>

                <div class="field">
                    <button type="submit" class="ui primary button">Login</button>
                    <a href="{{ url('/admin/password/reset') }}">Forgot Your Password?</a>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="ui hidden divider"></div>

@endsection
