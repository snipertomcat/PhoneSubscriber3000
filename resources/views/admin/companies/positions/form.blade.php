<sui-form-field>
    <sui-form-field>
        <label for="name">Responde a:</label>
        <div class="ui left icon selectable">
            <select name="parent_id">
                <option value="-1">Ninguno</option>
                @foreach($positions as $index=>$pos)
                    <option value="{{$pos->id}}" {{($pos->id == $position->parent_id) ? 'selected' : '' }}>{{$pos->name}}</option>
                @endforeach
            </select>
        </div>
    </sui-form-field>

    <label for="company_area">{{ trans('messages.job_area') }}</label>
    <div class="ui left icon input">
        <select name="area_id" id="company_area">
        @foreach($areas as $area)
            <option value="{{$area->id}}" {{ $company_area->id == $area->id ? 'selected' : '' }}>{{$area->name}}</option>
        @endforeach
        </select>
        @if ($errors->has('name'))
            <span class="help is-danger">{{ $errors->first('name') }}</span>
        @endif
    </div>
</sui-form-field>

@foreach($position->campos as $attr)
    <sui-form-field>
        <label for="name">{{ trans('messages.'.$attr) }}</label>
        <div class="ui left icon input">
            @if($attr !== 'description')
            <input class="input {{ $errors->has($attr) ? 'is-danger' : '' }}" type="text" name="{{ $attr }}"
                   value="{{ old('name', $position->$attr) }}" placeholder="{{ trans('messages.'.$attr) }}">
            @else
            <textarea type="text" class="input {{ $errors->has('description') ? 'is-danger' : '' }}" name="{{ $attr }}"
                      placeholder="{{ trans('messages.'.$attr) }}" maxlength="250">
                {{ old('name', trim($position->$attr)) }}
            </textarea>
            @endif
            @if($attr == 'name')
            <i class="user icon"></i>
            @endif
            @if ($errors->has($attr))
                <span class="help is-danger">{{ $errors->first($attr) }}</span>
            @endif
        </div>
    </sui-form-field>
@endforeach

<sui-form-field>
    <button type="submit" class="ui button primary">{{ trans('messages.save') }}</button>
    @include(
        'partials.form.cancel-button',
        ['url' => route("positions.show",[$company,$company_area,$position])])
</sui-form-field>
