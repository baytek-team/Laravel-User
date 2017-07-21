@extends('content::admin')

<!-- Main Content -->
@section('content')

<div class="ui centered grid">
    <div class="sixteen wide tablet ten wide computer column">
        <div class="ui very padded segment">
            <h1 class="ui header">Reset Password</h1>
            <form class="ui form" role="form" method="POST" action="{{ url('/admin/password/email') }}">
                {{ csrf_field() }}

                <div class="field{{ $errors->has('email') ? ' error' : '' }}">
                    <label for="email" class="screen-reader-text">E-Mail Address</label>
                    <input id="email" type="email" class="form-control" name="email" placeholder="E-Mail Address" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="field">
                    <button type="submit" class="ui primary button">Send Password Reset Link</button>
                </div>
            </form>
            @if (session('status'))
            <div class="ui error message">""
                {{ session('status') }}
            </div>
            @endif
        </div>
    </div>
</div>
<div class="ui hidden divider"></div>

@endsection
