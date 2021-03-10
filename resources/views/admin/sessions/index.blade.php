@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.sessions'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <div class="section">Experiencias</div>
        <i class="right chevron icon divider"></i>
        <div class="section">{{$experience->title}}</div>
        <i class="right chevron icon divider"></i>
        <div class="section active">Sesiones</div>

    </div>
@endSection

@section('body')
    <div class="container">
        <apithy-experiences-menu :experience="{{$experience}}"></apithy-experiences-menu>
        <br>
        <apithy-sessions-list :experience="{{$experience}}"></apithy-sessions-list>
    </div>
    {{--
    <div class="ui top attached tabular menu">
        <a href="{{ route('experiences.edit',[$experience]) }}" class="item" data-tab="Datos Generales">
            <h5 class="ui header">
                <i class="file alternate icon"></i>Datos Generales
            </h5>
        </a>
        <a href="{{ route('sessions.index',[$experience]) }}" class="item active" data-tab="Sesiones">
            <h5 class="ui header">
                <i class="tasks icon"></i>Sesiones
            </h5>
        </a>
        <a href="{{ url('/profile/security') }}" class="item" data-tab="security">
            <h5 class="ui header">
                <i class="wrench icon"></i>Configuraci&oacute;n
            </h5>

        </a>
    </div>
    <apithy-experiences-show-form :experience="{{$experience}}" inline-template>
        <div id="experience-detail" class="sixteen wide tablet fourteen wide computer column">
            <div class="ui segment no-borders no-shadows">
                @include('partials.flash')
            </div>
            <div class="ui segment no-shadows no-borders">
                @include('admin.sessions.filter')
            </div>
            @if(count($sessions))
                <div class="grid">
                        <div class="ui feed timeline" v-sortable="{ onEnd: sortableEnd }">
                            @foreach($sessions as $session)
                                <div class="event" id="session_{{$session->id}}">
                                    <div class="label">
                                        <img src="{{$session->full_path_cover}}" alt="label-image"/>
                                    </div>
                                    <div class="content ui segment" @click="showModalItem({{$session->id}})">
                                        <div class="title">
                                            {{ $session->title }}
                                        </div>
                                        <div class="summary">
                                            {!! $session->summary !!}
                                        </div>
                                        <modal
                                                height="90%"
                                                width="90%"
                                                :click-to-close="false"
                                                name="experience-item-{{$session->id}}">
                                            <div class="iframe-experience-wrapper ui segments">
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
                                                            src="{{ $session->resource_url }}" width="100%"
                                                            height="750px" frameBorder="0"></iframe>
                                                </div>
                                            </div>
                                        </modal>
                                        <div class="ui compact menu left floated">
                                            <span @click="showModalItem({{$session->id}})" class="green item">
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
            @else
                <h3>Aun no tienes Sesiones</h3>
            @endif
        </div>
    </apithy-experiences-show-form>
    --}}
@endsection