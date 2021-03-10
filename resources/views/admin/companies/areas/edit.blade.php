@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.companies'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <a href="{{route('companies.index')}}" class="section">Empresas</a>
        <i class="right chevron icon divider"></i>
        <div class="section">{{$company_area->company->name}}</div>
        <i class="right chevron icon divider"></i>
        <div class="active section">{{$company_area->name}}</div>
        <i class="right chevron icon divider"></i>
        <div class="active section">Editar</div>
    </div>
@endsection

@section('body')
    @if(Auth::user()->isSuper()) )<apithy-company-menu :company-data="{{$company}}"></apithy-company-menu>@endif

    <apithy-areas-view :auth-user="{{$auth_user}}"
                       :company="{{$company}}"
                       :area-data="{{$company_area}}"
                       :areas="{{json_encode($areas)}}"
                       :startEditing="true">
    </apithy-areas-view>

    <hr width="3">

    <apithy-positions-list :company-data="{{$company}}" :area-data="{{$company_area}}" :auth-user="{{$auth_user}}"></apithy-positions-list>

    {{--
    <div class="sixteen wide mobile sixteen wide tablet sixteen wide computer column">
        <div class="ui segments no-borders no-shadows">
            <div class="ui segment">
                @include('partials.flash')
            </div>
            <div class="ui segment">

                <sui-form size="standard" method="post" action="{{ route('areas.update', [$company, $company_area]) }}">
                    {{ method_field('PUT') }}
                    {{ csrf_field() }}
                    @include('admin.companies.areas.form')
                </sui-form>

                <apithy-areaposition-form
                        url="{{ route("areas.update",[$company, $company_area]) }}"
                        method="PUT"
                        csrf="{{csrf_token()}}"
                        :parent="{{$areas}}"
                        current-area="{{$company_area->id}}"
                        current-company="{{$company->id}}"
                        :current-data="{{$company_area}}"
                        cancel-url="{{ route("areas.index",[$company]) }}">
                </apithy-areaposition-form>
            </div>
        </div>
    </div>
    --}}
@endsection