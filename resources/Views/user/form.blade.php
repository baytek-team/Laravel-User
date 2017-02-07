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
<div class="one fields">
    <div class="sixteen wide field{{ $errors->has('password') ? ' error' : '' }}">
        <label for="password">Password</label>
        <input type="text" id="password" name="password" placeholder="Password" value="{{ old('password', $user->password) }}">
    </div>
</div>



{{--
<div class="field">
    <label for="password">Password</label>
    {!! Menu::form(
        ['Send Password Reset Link' => [
            'action' => 'Admin\UserController@sendUserPasswordResetLink',
            'method' => 'POST',
            'class' => 'ui button',
            'prepend' => '<i class="mail icon"></i>',
            'confirm' => 'Are you sure you want to send a reset password email: '.$user->first_name.' '.$user->last_name.'?',
        ]],
        $user)
    !!}
</div>
--}}


