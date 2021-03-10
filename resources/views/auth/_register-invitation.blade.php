<div>
    <div @if($errors->has('invitation_code')) error @endif>
        <input placeholder="{{ trans('messages.invitation_code') }}" type="text" icon="certificate"
                   name="invitation_code" :value="code">
    </div>
    <div @if($errors->has('email')) error @endif>
        <input placeholder="{{ trans('messages.email') }}" type="text" icon="envelope" name="email"
                   :value="email">
    </div>
</div>

<div>
    <div @if($errors->has('name')) error @endif>
        <input placeholder="{{ trans('messages.name') }}" type="text" icon="code" name="name"
                   value="{{ old('name') }}">
    </div>
    <div @if($errors->has('surname')) error @endif>
        <input placeholder="{{ trans('messages.surname') }}" type="text" icon="code" name="surname"
                   value="{{ old('surname') }}">
    </div>
</div>
<div>
    <div @if($errors->has('password') && old('register_type') == 'invitation') error @endif>
        <input placeholder="{{ trans('messages.password') }}" type="password" icon="lock"
                   name="password">
    </div>
    <div @if($errors->has('password_confirmation')) error @endif>
        <input placeholder="{{ trans('messages.password_confirmation') }}" type="password" icon="lock"
                   name="password_confirmation">
    </div>
</div>