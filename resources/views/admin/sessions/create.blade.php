@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.sessions'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <div class="section">Experiencias</div>
        <i class="right chevron icon divider"></i>
        <div class="section">{{$experience->title}}</div>
        <i class="right chevron icon divider"></i>
        <div class="section">Sesion</div>
        <i class="right chevron icon divider"></i>
        <div class="section">Nuevo</div>
    </div>
@endSection

@section('body')
    <div class="container">
        <apithy-experiences-menu :experience="{{$experience}}" section-active="sessions"></apithy-experiences-menu>
        <hr width="2">
        <apithy-sessions-form
                mode="create"
                :session="{{$session}}"
                :sessions_type_list="{{json_encode($type_list)}}"
                :experience="{{$experience}}"
                :author-list="{{json_encode($authors)}}"
                :partner-list="{{json_encode($partners)}}"
                :abilities-list="{{json_encode($abilities)}}"
                :tags-list="{{json_encode($tags)}}">
        </apithy-sessions-form>
    </div>
    {{--
    <div class="sixteen wide tablet fourteen wide computer column">
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
        <div class="ui segments no-shadows no-borders">
            @include('partials.flash')
            <apithy-sessions-info-form :session="{{$session}}" mode="create" :auth_user="{{Auth::user()}}"
                                       inline-template v-cloak>
                <sui-form enctype="multipart/form-data" method="POST"
                          action="{{ route('sessions.store',['experience'=>$experience->system_id]) }}">
                    {{ csrf_field() }}
                    @include('admin.sessions.form')
                </sui-form>
            </apithy-sessions-info-form>
        </div>
    </div>
    --}}
@endsection