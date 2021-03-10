<b-field @if($errors->has('user')) type="is-danger" message="{{ $errors->first('user') }}" @endif>
    <b-input placeholder="{{ trans('messages.user') }}" type="text" icon="user" name="user" value="{{ old('user') }}"></b-input>
</b-field>

<b-field @if($errors->has('password') && old('register_type') == 'lms') type="is-danger" message="{{ $errors->first('password') }}" @endif>
    <b-input placeholder="{{ trans('messages.password') }}" type="password" icon="lock" name="password"></b-input>
</b-field>
