@section('page.head.menu')
    @if($user->id)
        @button(___('Send Password Reset Link'), [
            'method' => 'post',
            'location' => 'member.password.email',
            'type' => 'route',
            'class' => 'ui primary action button',
            'prepend' => '<i class="mail icon"></i>',
            'model' => $user,
            //'confirm' => ___('Are you sure you want to send a reset password email?')
        ])
    @endif
@endsection

<div class="two fields">
    <div class="floaty field{{ $errors->has('meta.first_name') ? ' error' : '' }}">
        <label for="first_name">{{ ___('First Name') }}</label>
        <input type="text" id="first_name" name="meta[first_name]" placeholder="{{ ___('First Name') }}" value="{{ old('meta.first_name', $user->metadata('first_name')) }}">
    </div>
    <div class="floaty field{{ $errors->has('meta.last_name') ? ' error' : '' }}">
        <label for="last_name">{{ ___('Last Name') }}</label>
        <input type="text" id="last_name" name="meta[last_name]" placeholder="{{ ___('Last Name') }}" value="{{ old('meta.last_name', $user->metadata('last_name')) }}">
    </div>
</div>

<div class="two floaty fields">
    <div class="floaty field{{ $errors->has('email') ? ' error' : '' }}">
        <label for="email">{{ ___('Email Address') }}</label>
        <input type="text" id="email" name="email" @if($user->id) disabled @endif placeholder="{{ ___('Email Address') }}" value="{{ old('email', $user->email) }}">
    </div>
    <div class="floaty field{{ $errors->has('meta.title') ? ' error' : '' }}">
        <label for="title">{{ ___('Title') }}</label>
        <input type="text" id="title" name="meta[title]" placeholder="{{ ___('Title') }}" value="{{ old('meta.title', $user->metadata('title')) }}">
    </div>
</div>

<div class="two floaty fields">
    <div class="floaty field{{ $errors->has('meta.home_phone') ? ' error' : '' }}">
        <label for="home_phone">{{ ___('Home Phone') }}</label>
        <input type="text" id="home_phone" name="meta[home_phone]" placeholder="{{ ___('Phone') }}" value="{{ old('meta.home_phone', $user->metadata('home_phone')) }}">
    </div>
    <div class="floaty field{{ $errors->has('meta.work_phone') ? ' error' : '' }}">
        <label for="work_phone">{{ ___('Work Phone') }}</label>
        <input type="text" id="work_phone" name="meta[work_phone]" placeholder="{{ ___('Phone') }}" value="{{ old('meta.work_phone', $user->metadata('work_phone')) }}">
    </div>
</div>

<div class="two floaty fields">
    <div class="floaty field{{ $errors->has('meta.company') ? ' error' : '' }}">
        <label for="company">{{ ___('Company') }}</label>
        <input type="text" id="company" name="meta[company]" placeholder="{{ ___('Phone') }}" value="{{ old('meta.company', $user->metadata('company')) }}">
    </div>
</div>
