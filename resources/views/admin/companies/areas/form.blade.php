<input type="text" name="company_id" hidden value="{{ $company->id }}">

<sui-form-field>
    <label for="name">Pertenece a:</label>
    <div class="ui left icon selectable">
        <select name="parent_id">
            <option value="-1">Ninguno</option>
            @foreach($areas as $index=>$area)
                <option value="{{$area->id}}" {{($area->id == $company_area->parent_id) ? 'selected' : '' }}>{{$area->name}}</option>
            @endforeach
        </select>
    </div>
</sui-form-field>

<sui-form-field>
    <label for="name">{{ trans('messages.name') }}</label>
    <div class="ui left icon input">
        <input class="input {{ $errors->has('name') ? 'is-danger' : '' }}" type="text" name="name"
               value="{{ old('name', $company_area->name) }}" placeholder="{{ trans('messages.name') }}">
        <i class="sitemap icon"></i>
        @if ($errors->has('name'))
            <span class="help is-danger">{{ $errors->first('name') }}</span>
        @endif
    </div>
</sui-form-field>

<sui-form-field>
    <label for="short_name">{{ trans('messages.short_name') }}</label>
    <div class="ui left icon input">
        <input type="text" class="input {{ $errors->has('short_name') ? 'is-danger' : '' }}" name="short_name"
               value="{{ old('short_name', $company_area->short_name) }}" placeholder="{{ trans('messages.short_name') }}">
        <i class="sitemap icon"></i>
        @if($errors->has('short]_name'))
            <span class="help is-danger">{{ $errors->first('short_name') }}</span>
        @endif
    </div>
</sui-form-field>

<sui-form-field>
    <label for="description">{{ trans('messages.description') }}</label>
    <div class="ui left icon input">
        <textarea type="text" class="input {{ $errors->has('description') ? 'is-danger' : '' }}" name="description"
                  placeholder="{{ trans('messages.description') }}" maxlength="250">
            {{ old('description', $company_area->description) }}
        </textarea>
        @if($errors->has('description'))
            <span class="help is-danger">{{ $errors->first('description') }}</span>
        @endif
    </div>
</sui-form-field>

<sui-form-field>
    <button type="submit" class="ui button primary">{{ trans('messages.save') }}</button>
    @include('partials.form.cancel-button', ['url' => ($company_area) ? route("areas.show", [$company,$company_area]) : route("areas.index", [$company]) ])
</sui-form-field>