<sui-form-field>
    <label class="label">{{ trans('messages.type') }}</label>
    <select name="type"
            class="ui search dropdown twelve wide large screen">
        <option value="exploration" {{ $session->type== old('type',"exploration") ? 'selected' : '' }}>Exploracion</option>
        <option value="practice" {{ $session->type == old('type',"practice") ? 'selected' : '' }}>Ejercitacion</option>
        <option value="test" {{ $session->type == old('type',"test") ? 'selected' : '' }}>Evaluacion</option>

    </select>
</sui-form-field>
<sui-form-field>
    <label class="label">{{ trans('messages.name') }}</label>
    <div class="ui left icon input">
        <input class="input" type="text" name="title"
               value="{{ old('title',$session->title) }}" placeholder="{{ trans('messages.name') }}">
        <i class="building icon"></i>
    </div>
</sui-form-field>

<sui-form-field>
    <label class="label">{{ trans('messages.summary') }}</label>
    <div class="ui left icon input">
            <textarea class="summernote" rows="2" class="input" type="text" name="summary"
                      value="" placeholder="{{ trans('messages.summary') }}">{{ old('summary',$session->summary) }}
            </textarea>
    </div>
</sui-form-field>

<sui-form-field>
    <label class="label">{{ trans('messages.description') }}</label>
    <div class="ui left icon input">
            <textarea class="input summernote" type="text" name="description"
                      value="" placeholder="{{ trans('messages.description') }}">{{ old('description',$session->description) }}
            </textarea>
    </div>
</sui-form-field>

<div class="ui segment">
    <h4 class="ui dividing header">Recursos</h4>
    <div class="ui fluid field labeled input">
        <div class="ui label">
            http://
        </div>
        <input type="text" name="resource_url" placeholder="recursos.com" value="{{old("resource_url", $session->resource_url)}}">
    </div>
</div>


<div class="field">
    <label class="label">Portada</label>
    <div class="card-image">
        <apithy-image-upload inputOfFile="cover" data="data"
                             image="{{$session->full_path_cover}}" input_name="cover"
                             url_server='{{ route('sessions.cover.update',[$experience,$session]) }}'></apithy-image-upload>
    </div>
</div>

<div class="ui segment">
    <h4 class="ui dividing header">Autoria</h4>
    <sui-form-field>
        <label class="label">{{ trans('messages.author') }}</label>
        <select name="author"
                class="ui search dropdown twelve wide large screen">
            <option value="-1">Selecciona un Author</option>
            @foreach($authors as $author)
                <option value="{{ $author->id }}" {{ $author->id == old('author') ? 'selected' : ($session->author == $author->id ) ?"selected":"" }}>{{ $author->name }}</option>
            @endforeach
        </select>
    </sui-form-field>
    <sui-form-field>
        <label class="label">{{ trans('messages.partner') }}</label>
        <select name="partner"
                class="ui search dropdown twelve wide large screen">
            <option value="">Selecciona un Partner</option>
            @foreach($partners as $partners )
                <option value="{{ $partners ->id }}" {{ $partners->id == old('partner') ? 'selected' :"" }}>{{ $partners ->name }}</option>
            @endforeach
        </select>
    </sui-form-field>
</div>


<div class="ui segment">
    <h4 class="ui dividing header">Opciones de Publicacion</h4>
    <div class="inline fields">
        <label for="fruit">Visibilidad:</label>
        <div class="field">
            <div class="">
                <input :value="0" v-model="visibility" type="radio" name="visibility" tabindex="0" class="hidden">
                <label>Privado</label>
            </div>
        </div>

        <div class="field">
            <div class="">
                <input :value="1" v-model="visibility" type="radio" name="visibility" tabindex="0" class="hidden">
                <label>Publico</label>
            </div>
        </div>
    </div>

    <sui-form-field v-bind:class="{ display_none: visibility}">
        <apithy-entity-asignator :entity="{{ $session }}" :companies_data="{{ $companies }}"
                                 :users_data="{{$users}}"></apithy-entity-asignator>
    </sui-form-field>

    <sui-form-fields class="ui two fields">
        <sui-form-field>
            <div class="ui field {{ $errors->has('surname') ? 'error' : '' }}">
                <label class="label">{{ trans('messages.available_from') }}</label>
                <datepicker class="icon" value="{{old("available_from",$session->available_from)}}" format="yyyy-MM-dd"
                            name="available_from">
                    <i class="calendar icon"></i>
                </datepicker>
            </div>
        </sui-form-field>

        <sui-form-field>
            <div class="ui field {{ $errors->has('surname') ? 'error' : '' }}">
                <label class="label">{{ trans('messages.available_to') }}</label>
                <datepicker class="icon" value="{{old("available_to",$session->available_to)}}" format="yyyy-MM-dd"
                            name="available_to">
                    <i class="calendar icon"></i>
                </datepicker>
            </div>
        </sui-form-field>
    </sui-form-fields>

    <div class="inline field">
        <label for="status">Estado:</label>
        <div class="ui toggle checkbox">
            <input type="checkbox" {{ old("status",$session->status) ? "checked":""  }} name="status" tabindex="0"
                   class="hidden">
            <label>Activo</label>
        </div>
    </div>

</div>

@if($session->status == \Apithy\session::STATUS_DRAFT)
    <input type="hidden" name="session_draft_id" value="{{$session->id}}" tabindex="0" class="hidden">
@endif

<sui-form-field>
    <button type="submit" class="ui button primary">{{ trans('messages.save') }}</button>
    @include('partials.form.cancel-button', ['url' => '/companies'])
</sui-form-field>