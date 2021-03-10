@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.experiences'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <div class="section">Experiencias</div>
        <i class="right chevron icon divider"></i>
        <div class="active section">{{$experience->title}}</div>
    </div>
@endSection

@section('body')
    <apithy-experiences-show-form :experience="{{$experience}}" inline-template>
        <div id="experience-details" class="sixteen wide tablet ten wide computer column centered">
            <div id="experience-admin-view" class="ten wide column module">
                <a class="ui yellow labeled icon button"
                   href="{{ route("experiences.edit", ["experience"=>$experience])}}">
                    <i class="pencil icon"></i>
                    {{ trans('messages.edit') }}
                </a>
                @can('delete', $experience)
                    @include('partials.form.delete-button', ['id'=>$experience->id,'action' => route('experiences.destroy', [$experience])])
                @endcan
                <a class="ui blue labeled icon button"
                   href="#"
                   @click="showModal('experience-enrollment')"
                >
                    <i class="users icon"></i>
                    {{ trans('messages.enrollment') }}
                </a>
                @if($user->isAdmin())
                <a class="ui blue labeled icon button"
                   href="#"
                   @click="showModal('apithy-experience-assignation-list')"
                >
                    <i class="tasks icon"></i>
                    Asignaciones
                </a>
                @endif
                <h1 class="ui header">
                    <div class="header">{{ $experience->title}}</div>
                </h1>
                <div class="extra content">
                    <div class="meta">
                        @foreach($experience->abilities as $ability)
                            <span class="ui label">{{ $ability->title}}</span>
                        @endforeach
                    </div>

                    <div class="description">
                        {!! $experience->summary !!}
                    </div>
                </div>

                <div class="ui segments">
                    @if($experience->video_intro)
                        <div class="ui segment">
                            <div class="ui centered fluid">
                                <iframe src="{{ $experience->video_intro }}" width="100%" height="560px" frameBorder="0"></iframe>
                            </div>
                        </div>
                    @else
                        <div class="ui segment">
                            <div class="ui centered fluid rounded image">
                                <img src="https://picsum.photos/640/480">
                            </div>
                        </div>
                    @endif
                    <div class="ui segment">
                        <div class="description">
                            {!! $experience->description !!}
                        </div>
                    </div>
                </div>

                <div>
                    <label class="label">{{ trans('messages.tags') }}</label>
                    <div class="tags">
                        <span class="tag" v-for="tag in tags">@{{ tag.title }}</span>
                    </div>
                </div>

                <h3 class="ui header">
                    Sesiones
                </h3>

                @if(count($experience->sessions))
                    <div class="row">
                        <div class="sixteen wide column">
                            <div class="ui">
                                <div class="ui">
                                    <div class="ui feed timeline" v-sortable="{ onEnd: sortableEnd }">
                                        @foreach($experience->sessions as $session)
                                            <div class="event" id="session_{{$session->id}}">
                                                <div class="label">
                                                    <img src="{{$session->full_path_cover}}" alt="label-image"/>
                                                </div>
                                                <div class="content ui segment"
                                                     @click="showModalItem({{$session->id}})">
                                                    <div class="summary">
                                                        {{ $session->title }}
                                                        <div class="date">
                                                            {!! $session->summary !!}
                                                        </div>
                                                    </div>
                                                    <modal
                                                            height="90%"
                                                            width="80%"
                                                            :click-to-close="false"
                                                            name="experience-item-{{$session->id}}"
                                                    >
                                                        <div id="experience-item-{{$session->id}}"
                                                             class="iframe-experience-wrapper ui segments">
                                                            <div class="iframe-experience-header ui segment">
                                                                <img src="{{ url('images/apithy_small.png') }}"
                                                                     alt="{{ $applicationName }}" width="30">
                                                                <span class="iframe-experience-header-title">
                                                                {{$session->title}}
                                                                 </span>
                                                                <button class="ui red icon button right floated close-modal"
                                                                        @click="closeModalItem({{$session->id}})"><i
                                                                            class="ui cancel icon"></i>
                                                                </button>
                                                            </div>
                                                            <div class="iframe-experience-content ui segment">
                                                                <iframe class="iframe-experience-item"
                                                                        src="{{ $session->resource_url }}" width="95%"
                                                                        height="100%" frameBorder="0"
                                                                        allowfullscreen="allowfullscreen"
                                                                >

                                                                </iframe>
                                                            </div>
                                                        </div>
                                                    </modal>
                                                    <div class="ui compact menu right floated">
                                                    <span class="green item" @click="showModalItem({{$session->id}})">
                                                        <i class="eye icon"></i>
                                                        Ver
                                                    </span>
                                                        <a href="{{ route("sessions.edit", [$experience,$session])}}"
                                                           class="yellow item">
                                                            <i class="pencil icon"></i>
                                                            Editar
                                                        </a>
                                                        <a class="red item"
                                                           href="{{ route("sessions.update.status", ["experience"=>$experience,"session"=>$session,"status"=>(int)!$session->status])}}">
                                                            @if($session->status)
                                                                <i class="toggle on icon"></i>
                                                                {{ trans('messages.desactived') }}
                                                            @else
                                                                <i class="toggle off icon"></i>
                                                                {{ trans('messages.actived') }}
                                                            @endif
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <h3>Aun no tienes Sesiones</h3>
                @endif
            </div>
            <modal
                    height="auto"
                    width="95%"
                    :click-to-close="false"
                    name="experience-enrollment">
                <apithy-experience-enrollment-list :experience="{{ $experience }} "
                                                   :enrollments_data="{{ $enrollments }}">
                </apithy-experience-enrollment-list>
            </modal>
            <modal class="apithy-modal"
                   height="70%"
                   width="50%"
                   :click-to-close="false"
                   name="apithy-experience-assignation-list">
                <apithy-experience-assignation-list></apithy-experience-assignation-list>
            </modal>
            <modal class="apithy-modal"
                   height="70%"
                   width="50%"
                   :click-to-close="false"
                   name="apithy-experience-assignation-details">
                <apithy-experience-assignation-details :id_assignation="assignation_id"></apithy-experience-assignation-details>
            </modal>
            <modal class="apithy-modal"
                   height="auto"
                   width="50%"
                   :click-to-close="false"
                   name="apithy-experience-assignation-form">
                <apithy-experience-assignation-form :experience="{{$experience}}" :companies="{{$companies}}"
                                                    :users="{{$users}}">
                </apithy-experience-assignation-form>
            </modal>
        </div>
    </apithy-experiences-show-form>
@endsection