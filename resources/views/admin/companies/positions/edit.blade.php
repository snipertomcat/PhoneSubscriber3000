@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.companies'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <a href="{{route('companies.index')}}" class="section">Empresas</a>
        <i class="right chevron icon divider"></i>
        <div class="section">Areas</div>
        <i class="right chevron icon divider"></i>
        <div class="section">{{$position->area->name}}</div>
        <i class="right chevron icon divider"></i>
        <div class="section">{{$position->name}}</div>
        <i class="right chevron icon divider"></i>
        <div class="active section">Editar</div>
    </div>
@endSection

@section('body')
    @if(Auth::user()->isSuper()) )<apithy-company-menu :company-data="{{$company}}"></apithy-company-menu>@endif
    <br><br>

    <apithy-positions-view :auth-user="{{$auth_user}}"
                           :company="{{$company}}"
                           :position-data="{{$position}}"
                           :positions="{{json_encode($positions)}}"
                           :start-editing="true">
    </apithy-positions-view>
    {{--
    <div class="sixteen wide mobile sixteen wide tablet sixteen wide computer column">
        <div class="ui segments no-borders no-shadows">
            <div class="ui segment">
                <h5 class="ui header">
                    <i class="user icon"></i>{{ trans('messages.job_title') }}
                </h5>
                @include('partials.flash')
            </div>
            <div class="ui segment">

                <sui-form size="standard" method="POST" action="{{ route("positions.update",[$company,$company_area,$position]) }}">
                    {{ csrf_field() }}
                    @include('admin.companies.positions.form')
                </sui-form>

                <apithy-areaposition-form
                        url="{{ route("positions.update",[$company,$company_area,$position]) }}"
                        method="POST"
                        csrf="{{csrf_token()}}"
                        :data-list="{{$areas}}"
                        :parent="{{$positions}}"
                        current-area="{{$company_area->id}}"
                        current-company="{{$company->id}}"
                        :current-data="{{$position}}"
                        cancel-url="{{ route("positions.index",[$company,$company_area]) }}">
                </apithy-areaposition-form>
            </div>
        </div>
    </div>
    --}}
@endsection