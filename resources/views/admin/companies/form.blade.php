<sui-form-field>
    <label class="label">{{ trans('messages.country') }}</label>
    <select value="484" name="country_id"
            class="{{ $errors->has('country_id') ? 'is-danger' : '' }} ui search dropdown twelve wide large screen">
        @foreach($countries as $country)
            <option value="{{ $country->id }}" {{ $country->id == old('country_id', isset($company->country) ? $company->country->id : null) ? 'selected' : '' }}>{{ $country->name }}</option>
        @endforeach
    </select>
    @if ($errors->has('country_id'))
        <span class="help is-danger">{{ $errors->first('country_id') }}</span>
    @endif
</sui-form-field>

<sui-form-field>
    <label class="label">{{ trans('messages.name') }}</label>
    <div class="ui left icon input">
        <input class="input {{ $errors->has('name') ? 'is-danger' : '' }}" type="text" name="name"
               value="{{ old('name', $company->name) }}" placeholder="{{ trans('messages.name') }}">
        <i class="building icon"></i>
        @if ($errors->has('code'))
            <span class="help is-danger">{{ $errors->first('name') }}</span>
        @endif
    </div>
</sui-form-field>

<sui-form-field>
    <label class="label">{{ trans('messages.short_name') }}</label>
    <div class="ui left icon input">
        <input class="input {{ $errors->has('short_name') ? 'is-danger' : '' }}" type="text" name="short_name"
               value="{{ old('short_name', $company->short_name) }}" placeholder="{{ trans('messages.short_name') }}">
        <i class="building icon"></i>
        @if ($errors->has('short_name'))
            <span class="help is-danger">{{ $errors->first('short_name') }}</span>
        @endif
    </div>
</sui-form-field>

<sui-form-field class="column is-full">
    <label class="label">{{ trans('messages.legal_name') }}</label>
    <div class="ui left icon input">
        <input class="input {{ $errors->has('legal_name') ? 'is-danger' : '' }}" type="text" name="legal_name"
               value="{{ old('legal_name', $company->legal_name) }}" placeholder="{{ trans('messages.legal_name') }}">
        <i class="newspaper icon"></i>
        @if ($errors->has('legal_name'))
            <span class="help is-danger">{{ $errors->first('legal_name') }}</span>
        @endif
    </div>
</sui-form-field>

<sui-form-field>
    <label class="label">{{ trans('messages.account_name') }}</label>
    <div class="ui left icon input">
        <input class="input {{ $errors->has('account_name') ? 'is-danger' : '' }}" type="text" name="account_name"
               value="{{ old('account_name', $company->account_name) }}" placeholder="{{ trans('messages.account_name') }}">
        <i class="internet explorer icon"></i>
        @if ($errors->has('code'))
            <span class="help is-danger">{{ $errors->first('code') }}</span>
        @endif
    </div>
</sui-form-field>


<sui-form-field {{ $errors->has('site_url') ? 'error' : '' }}>
    <label class="label">{{ trans('messages.site') }}</label>
    <div class="ui left icon input">
        <input class="input" type="url" name="site_url"
               value="{{ old('site_url', $company->site_url) }}" placeholder="{{ trans('messages.site') }}">
        <i class="internet explorer icon"></i>
    </div>
</sui-form-field>

<sui-form-field>
    <label class="label">{{ trans('messages.contact_email') }}</label>
    <div class="ui left icon input">
        <input class="input {{ $errors->has('code') ? 'is-danger' : '' }}" type="text" name="contact_email"
               value="{{ old('contact_email', $company->contact_email) }}" placeholder="{{ trans('messages.contact_email') }}">
        <i class="envelope icon"></i> @if ($errors->has('code'))
            <span class="help is-danger">{{ $errors->first('code') }}</span>
        @endif
    </div>
</sui-form-field>

<sui-form-field>
    <label class="label">{{ trans('messages.contact_phone') }}</label>
    <div class="ui left icon input">
        <input class="input {{ $errors->has('code') ? 'is-danger' : '' }}" type="text" name="contact_phone"
               value="{{ old('contact_phone', $company->contact_phone) }}" placeholder="{{ trans('messages.contact_phone') }}">
        <i class="phone icon"></i> @if ($errors->has('code'))
            <span class="help is-danger">{{ $errors->first('code') }}</span>
        @endif
    </div>
</sui-form-field>

<sui-form-field>
    <button type="submit" class="ui button primary">{{ trans('messages.save') }}</button>
    @include('partials.form.cancel-button', ['url' => route("companies.index")])
</sui-form-field>