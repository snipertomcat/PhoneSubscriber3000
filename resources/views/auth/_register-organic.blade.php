<sui-form-field @if($errors->has('email')) error @endif>
    <sui-input placeholder="{{ trans('messages.email') }}" type="text" icon="envelope" name="email"
               :value="email"></sui-input>
</sui-form-field>

<sui-form-fields>
    <sui-form-field @if($errors->has('name')) error @endif>
        <sui-input placeholder="{{ trans('messages.name') }}" type="text" icon="code" name="name"
                   value="{{ old('name') }}"></sui-input>
    </sui-form-field>
    <sui-form-field @if($errors->has('surname')) error @endif>
        <sui-input placeholder="{{ trans('messages.surname') }}" type="text" icon="code" name="surname"
                   value="{{ old('surname') }}"></sui-input>
    </sui-form-field>
</sui-form-fields>

<sui-form-fields>
    <sui-form-field @if($errors->has('password') && old('register_type') == 'invitation') error @endif>
        <sui-input placeholder="{{ trans('messages.password') }}" type="password" icon="lock"
                   name="password"></sui-input>
    </sui-form-field>
    <sui-form-field @if($errors->has('password_confirmation')) error @endif>
        <sui-input placeholder="{{ trans('messages.password_confirmation') }}" type="password" icon="lock"
                   name="password_confirmation"></sui-input>
    </sui-form-field>
</sui-form-fields>