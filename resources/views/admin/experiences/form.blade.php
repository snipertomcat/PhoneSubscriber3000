<div class="ui segment">
    <sui-form-field>
        <label class="label">{{ trans('messages.experience_type') }}</label>
        <select name="type" v-model="experience_type"
                class="ui search dropdown twelve wide large screen">
            <option value="journey" {{ $experience->type== old('type',"journey") ? 'selected' : '' }}>Viaje</option>
            <option value="adventure" {{ $experience->type == old('type',"adventure") ? 'selected' : '' }}>Aventura</option>
        </select>
    </sui-form-field>
    <sui-form-field>
        <label class="label">{{ trans('messages.name') }}</label>
        <div class="ui left icon input">
            <input class="input" type="text" name="title"
                   value="{{ old('title',$experience->title) }}" placeholder="{{ trans('messages.name') }}">
            <i class="building icon"></i>
        </div>
    </sui-form-field>

    <sui-form-field>
        <label class="label">{{ trans('messages.summary') }}</label>
        <div class="ui left icon input">
            <textarea class="summernote" rows="2" class="input" type="text" name="summary"
                      value="" placeholder="{{ trans('messages.summary') }}">{{ old('summary',$experience->summary) }}
            </textarea>
        </div>
    </sui-form-field>

    <sui-form-field>
        <label class="label">{{ trans('messages.description') }}</label>
        <div class="ui left icon input">
            <textarea class="input summernote" type="text" name="description"
                      value="" placeholder="{{ trans('messages.description') }}">{{ old('description',$experience->description) }}
            </textarea>
        </div>
    </sui-form-field>

    <div class="field">
        <label class="label">Portada</label>
        <div class="card-image">
            <apithy-image-upload inputOfFile="cover" data="data"
                                 image="{{$experience->full_path_cover}}" input_name="cover" url_server='{{ route('cover.update',[$experience]) }}'></apithy-image-upload>
        </div>
    </div>

    <sui-form-field v-if="showAdventures">
        <label class="label">{{ trans('messages.adventures') }}</label>
        <select multiple="" name="adventures[]"
                class="ui search dropdown twelve wide large screen">
            <option value="">Aventuras para este Journey</option>
            @foreach($adventures as $adventure)
                <option value="{{ $adventure->id }}" {{ $adventure->id == old('adventure_id')
                    ? 'selected'
                    : ($experience->adventures->contains($adventure->id ))?"selected":"" }}>
                    {{ $adventure->title }}
                </option>
            @endforeach
        </select>
    </sui-form-field>
</div>

<div class="ui segment">
    <h4 class="ui dividing header">Objetivos</h4>
    <sui-form-field>
        <label class="label">{{ trans('messages.company_objetives') }}</label>
        <div class="ui left icon input">
            <textarea rows="2" class="input summernote" type="text" name="company_objectives"
                      placeholder="{{ trans('messages.company_objetives') }}">{{ old('company_objectives',$experience->company_objectives) }}
            </textarea>
        </div>
    </sui-form-field>

    <sui-form-field>
        <label class="label">{{ trans('messages.area_objetives') }}</label>
        <div class="ui left icon input">
            <textarea class="input summernote" type="text" name="area_objectives"
                       placeholder="{{ trans('messages.area_objetives') }}">{{ old('area_objectives',$experience->area_objectives) }}
            </textarea>
        </div>
    </sui-form-field>

    <sui-form-field>
        <label class="label">{{ trans('messages.abilities') }}</label>
        <input type="text" v-for="ability in abilities_computed" v-show="false" v-model="ability" name="abilities[]">
        <v-tags-input multiselect
                      v-model="abilities"
                      label="title"
                      track-by="id"
                      :options="abilities_list"
                      :multiple="true"
                      :taggable="true">
        </v-tags-input>
    </sui-form-field>

    <sui-form-field>
        <label class="label">{{ trans('messages.tags') }}</label>
        <input type="text" v-for="t in tags_computed" v-show="false" v-model="t" name="tags[]">
        <v-tags-input multiselect
                      v-model="tags"
                      label="title"
                      track-by="id"
                      :options="tag_list"
                      :multiple="true"
                      :taggable="true"
                      @tag="addTag">
        </v-tags-input>
    </sui-form-field>
</div>

<div class="ui segment">
    <h4 class="ui dividing header">Caracteristicas</h4>
    <sui-form-field>
        <label class="label">{{ trans('messages.price') }}</label>
        <div class="ui right labeled input">
            <label for="amount" class="ui label">$</label>
            <input class="input" type="text" name="price_default"
                   value="{{ old('price_default',$experience->price_default) }}" placeholder="{{ trans('messages.price') }}">
            <div class="ui basic label">.00</div>
        </div>
    </sui-form-field>

    <sui-form-field>
        <label class="label">{{ trans('messages.duration_limit_default') }}</label>
        <div class="ui left icon input">
            <input class="input" type="text" name="duration_limit_default"
                   value="{{ old('duration_limit_default',$experience->duration_limit_default) }}" placeholder="{{ trans('messages.duration_limit_default') }}">
            <i class="time icon"></i>
        </div>
    </sui-form-field>

    <sui-form-field>
        <label class="label">{{ trans('messages.level_default') }}</label>
        <div class="ui left icon input">
            <input class="input" type="text" name="level_default"
                   value="{{ old('level_default',$experience->level_default) }}" placeholder="{{ trans('messages.level_default') }}">
            <i class="time icon"></i>
        </div>
    </sui-form-field>

    <sui-form-field>
        <label class="label">{{ trans('messages.points') }}</label>
        <div class="ui left icon input">
            <input class="input" type="text" name="points_default"
                   value="{{ old('points_default',$experience->points_default) }}" placeholder="{{ trans('messages.points') }}">
            <i class="certificate icon"></i>
        </div>
    </sui-form-field>

    <sui-form-field>
        <label for="" class="label">Horas de contenido</label>
        <div class="ui left input">
            <input type="number"
                   class="input"
                   min="0"
                   name="features[hours_content]"
                   value="{{ old('features[hours_content]',$experience->features['hours_content']) }}">
        </div>
    </sui-form-field>
    <sui-form-field>
        <label for="" class="label">Numero de videos</label>
        <div class="ui left input">
            <input type="number" class="input" min="0" name="features[num_videos]"
                   value="{{ old('features[num_videos]',$experience->features['num_videos']) }}">
        </div>
    </sui-form-field>
    <sui-form-field>
        <label for="" class="label">Numero de actividades</label>
        <div class="ui left input">
            <input type="number" class="input" min="0" name="features[num_activities]"
                   value="{{ old('features[num_videos]',$experience->features['num_activities']) }}">
        </div>
    </sui-form-field>
    <sui-form-field>
        <label for="" class="label">Descargas de contenido</label>
        <div class="ui left input">
            <input type="number" class="input" min="0" name="features[num_content_downloads]"
                   value="{{ old('features[num_videos]',$experience->features['num_content_downloads']) }}">
        </div>
    </sui-form-field>
</div>

<div class="ui segment">
    <h4 class="ui dividing header">Autoria</h4>
    <sui-form-field>
        <label class="label">{{ trans('messages.author') }}</label>
        <input type="text" v-if="author !== null" v-show="false" v-model="author.id" name="author">
        {{--
        <select name="author"
                class="ui search dropdown twelve wide large screen">
            <option value="-1">Selecciona un Author</option>
            @foreach($authors as $author)
                <option value="{{ $author->id }}" {{ $author->id == old('author') ? 'selected' : ($experience->author == $author->id ) ?"selected":"" }}>{{ $author->name }}</option>
            @endforeach
        </select>
        --}}
        <v-tags-input v-model="author"
                      placeholder="Author"
                      label="name"
                      track-by="id"
                      :options="author_list"
                      :option-height="104"
                      :preselectFirst="true"
                      :show-labels="false">
            <template slot="singleLabel" slot-scope="props">
                <div v-if="author !== null">
                    <img class="option__image" :src="author.full_path_avatar" alt="" width="50px">
                    <span class="option__desc">
                        <span class="option__title">@{{ author.full_name }}</span>
                    </span>
                </div>
            </template>
            <template slot="option" slot-scope="props">
                <img class="option__image" :src="props.option.full_path_avatar" alt="" width="50px">
                <span class="option__desc">
                    <span class="option__title">@{{ props.option.full_name }}</span>
                </span>
            </template>
        </v-tags-input>
    </sui-form-field>
    <sui-form-field>
        <label class="label">{{ trans('messages.instructors') }}</label>
        <input type="text"
               v-for="instructor in instructors_computed"
               v-show="false"
               v-model="instructor"
               name="instructors[]">
        {{--
        <select id="instructors"
                multiple=""
                name="instructors[]"
                class="ui search dropdown twelve wide large screen">
            <option value="">Selecciona un Instructor</option>
            @foreach($partners as $partner )
                <option value="{{ $partner->id }}" {{ isset($experience->instructors) && (in_array($partner->id, $experience->instructors)) ? 'selected' :"" }}>{{ $partner->name }}</option>
            @endforeach
        </select>
        --}}
        <v-tags-input v-model="instructor"
                      placeholder="Instructor"
                      label="name"
                      track-by="id"
                      :options="partner_list"
                      :option-height="104"
                      :multiple="true"
                      :show-labels="false">
            <template slot="singleLabel" slot-scope="props">
                <span class="option__desc">
                    <span class="option__title">@{{ instructor.full_name }}</span>
                </span>
            </template>
            <template slot="option" slot-scope="props">
                <img class="option__image" :src="props.option.full_path_avatar" alt="" width="50px">
                <span class="option__desc">
                    <span class="option__title">@{{ props.option.full_name }}</span>
                </span>
            </template>
        </v-tags-input>
    </sui-form-field>
    <sui-form-field>
        <label class="label">{{ trans('messages.partner') }}</label>
        <input type="text" v-if="partner !== null" v-show="false" v-model="partner.id" name="partner">
        {{--
        <select name="partner"
                class="ui search dropdown twelve wide large screen">
            <option value="">Selecciona un Partner</option>
            @foreach($partners as $partners )
                <option value="{{ $partners ->id }}" {{ $partners->id == old('partner') ? 'selected' :"" }}>{{ $partners ->name }}</option>
            @endforeach
        </select>
        --}}
        <v-tags-input v-model="partner"
                      placeholder="Patrocinador"
                      label="name"
                      track-by="id"
                      :options="partner_list"
                      :option-height="104"
                      :preselectFirst="true"
                      :show-labels="false">
            <template slot="singleLabel" slot-scope="props">
                <div v-if="partner !== null">
                    <img class="option__image" :src="partner.full_path_avatar" alt="" width="50px">
                    <span class="option__desc">
                        <span class="option__title">@{{ partner.full_name }}</span>
                    </span>
                </div>
            </template>
            <template slot="option" slot-scope="props">
                <img class="option__image" :src="props.option.full_path_avatar" alt="" width="50px">
                <span class="option__desc">
                    <span class="option__title">@{{ props.option.full_name }}</span>
                </span>
            </template>
        </v-tags-input>
    </sui-form-field>
</div>


<div class="ui segment">
    <h4 class="ui dividing header">Opciones de Publicacion</h4>
    <div class="inline fields">
        <label for="fruit">Visibilidad:</label>
        <div class="field">
            <div class="">
                <input :value="0" v-model="visibility" type="radio"  name="visibility" tabindex="0" class="hidden">
                <label>Privado</label>
            </div>
        </div>

        <div class="field">
            <div class="">
                <input  :value="1" v-model="visibility" type="radio" name="visibility" tabindex="0" class="hidden">
                <label>Publico</label>
            </div>
        </div>
    </div>

    @if (Auth::user()->isAdmin())
        <sui-form-field v-bind:class="{ display_none: visibility}">
            <apithy-entity-asignator :entity="{{ $experience }}" :companies_data="{{ $companies }}" :users_data="{{$users}}"></apithy-entity-asignator>
        </sui-form-field>
    @elseif(Auth::user()->isSuper())
        <sui-form-field v-bind:class="{ display_none: visibility}">
            <label class="label">{{ trans('messages.companies') }}</label>
            <v-tags-input v-model="companies"
                          placeholder="Visible para:"
                          :options="companies_list"
                          :multiple="true"
                          label="name"
                          track-by="id">
            </v-tags-input>
            <input type="text"
                   v-for="company in companies_computed"
                   v-model="company"
                   v-show="false"
                   name="companies[]">
        </sui-form-field>
    @endif

    <sui-form-fields class="ui two fields">
        <sui-form-field>
            <div class="ui field {{ $errors->has('surname') ? 'error' : '' }}">
                <label class="label">{{ trans('messages.available_from') }}</label>
                <datepicker class="icon" value="{{old("available_from",$experience->available_from)}}" format="yyyy-MM-dd" name="available_from">
                    <i class="calendar icon"></i>
                </datepicker>
            </div>
        </sui-form-field>

        <sui-form-field>
            <div class="ui field {{ $errors->has('surname') ? 'error' : '' }}">
                <label class="label">{{ trans('messages.available_to') }}</label>
                <datepicker class="icon" value="{{old("available_to",$experience->available_to)}}" format="yyyy-MM-dd" name="available_to">
                    <i class="calendar icon"></i>
                </datepicker>
            </div>
        </sui-form-field>
    </sui-form-fields>

    <div class="inline field">
        <label for="status">Estado:</label>
        <div class="ui toggle checkbox">
            <input type="checkbox" {{ old("status",$experience->status) ? "checked":""  }} name="status" tabindex="0" class="hidden" >
            <label>Activo</label>
        </div>
    </div>

</div>

@if($experience->status == \Apithy\Experience::STATUS_DRAFT)
    <input type="hidden" name="experience_draft_id" value="{{$experience->id}}" tabindex="0" class="hidden" >
@endif

<sui-form-field>
    <button type="submit" class="ui button primary">{{ trans('messages.save') }}</button>
    @include('partials.form.cancel-button', ['url' => '/companies'])
</sui-form-field>