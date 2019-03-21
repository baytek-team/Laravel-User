@section('page.head.menu')
    @if($user->id)
        @button(___('Send Password Reset Link'), [
            'method' => 'post',
            'location' => 'member.password.email',
            'type' => 'route',
            'class' => 'ui action button',
            'prepend' => '<i class="mail icon"></i>',
            'model' => $user,
            //'confirm' => ___('Are you sure you want to send a reset password email?')
        ])
    @endif
@endsection

<div class="one fields">
    <div class="sixteen wide field{{ $errors->has('name') ? ' error' : '' }}">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" placeholder="Name" value="{{ old('name', $user->name) }}">
    </div>
</div>
<div class="one fields">
    <div class="sixteen wide field{{ $errors->has('email') ? ' error' : '' }}">
        <label for="email">Email Address</label>
        <input type="text" id="email" name="email" placeholder="Email Address" value="{{ old('email', $user->email) }}">
    </div>

</div>
<div class="two fields">
    <div class="sixteen wide field{{ $errors->has('password') ? ' error' : '' }}">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Password">
    </div>

    <div class="sixteen wide field{{ $errors->has('password_confirmation') ? ' error' : '' }}">
        <label for="password_confirmation">Password Confirmation</label>
        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Password">
    </div>
</div>

