@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.courses'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <div class="active section">Experiencias</div>
    </div>
@endSection

@section('body')
    <apithy-experiences-carousel url="{{ route('experiences.index') }}"
                                 :user="{{ $user }}"
                                 :abilities_data="{{ $abilities }}"
                                 :authors_data="{{ $authors }}"
                                 :types_data="{{ json_encode($types) }}"
                                 :experiences_data="{{$experiences->toJson()}}"
                                 :init_load="false"
                                 title_carrousel="Te puede interesar"
                                 from="experiences">
    </apithy-experiences-carousel>
    {{--
    <div class="sixteen wide mobile sixteen wide tablet sixteen wide computer column">
        <div class="ui segments no-borders no-shadows">
            <div class="ui segment">
                @include('partials.flash')
            </div>
            <div class="ui segment">
                @include('admin.experiences.filter')
            </div>
            <div class="ui segment">

                @if ($experiences->count() > 0)
                    <div class="ui five stackable cards">
                        @foreach($experiences as $course)
                            <div class="ui card">
                                <div class="ui centered fluid rounded image">
                                    <img src="{{ $course->full_path_cover }}">
                                </div>
                                <div class="content">
                                    <div class="header">{{ $course->title }}</div>
                                    <div class="description">
                                        {!! $course->summary !!}
                                    </div>
                                </div>
                                <div class="extra content">
                                    <div class="meta">
                                        @foreach($course->abilities as $ability)
                                            <span class="ui label">{{ $ability->title }}</span>
                                        @endforeach
                                    </div>

                                </div>
                                <div class="extra content">
                                  <span class="right floated">
                                    {{ date("Y-m-d",strtotime($course->created_at)) }}
                                  </span>
                                  <span>
                                    <i class="user icon"></i>
                                        {{ $course->current_company_enrollments}}
                                  </span>
                                </div>
                                <div class="extra content">
                                    <div class="ui two buttons">
                                        <a class="ui basic green button"
                                           href="{{ route("experiences.show", [$course])}}">
                                            <i class="eye icon"></i>
                                            {{ trans('messages.details') }}
                                        </a>
                                        @can('update', $course)
                                        <a class="ui basic yellow button"
                                           href="{{ route("experiences.edit", [$course])}}">
                                            <i class="pencil icon"></i>
                                            {{ trans('messages.edit') }}
                                        </a>
                                        @endcan
                                    </div>
                                    <div class="ui two buttons">
                                        <a class="ui basic blue button"
                                           href="{{ route("experiences.update.status", ["experience"=>$course,"status"=>(int)!$course->status])}}">
                                            @if($course->status)
                                                <i class="toggle on icon"></i>
                                                {{ trans('messages.desactived') }}
                                            @else
                                                <i class="toggle off icon"></i>
                                                {{ trans('messages.actived') }}
                                            @endif
                                        </a>
                                    </div>
                                    <div class="ui two buttons">
                                        @can('delete', $course)
                                            @include('partials.form.delete-button', ['id'=>$course->id,'action' => route('experiences.destroy', [$course])])
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
            </div>
        </div>
        {{ $experiences->links() }}
    </div>
    @else
        <div class="notification">
            {{ trans('messages.no_results') }}
        </div>
    @endif
    --}}
@endsection