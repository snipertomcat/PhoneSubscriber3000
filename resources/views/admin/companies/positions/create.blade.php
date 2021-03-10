@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.companies'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <a href="{{route('companies.index')}}" class="section">Empresas</a>
        <i class="right chevron icon divider"></i>
        <div class="section">Areas</div>
        <i class="right chevron icon divider"></i>
        <div class="section">Posiciones</div>
        <i class="right chevron icon divider"></i>
        <div class="active section">Nuevo</div>
    </div>
@endSection

@section('body')
    @if(Auth::user()->isSuper()) )<apithy-company-menu :company-data="{{$company}}"></apithy-company-menu>@endif

    <apithy-positions-create :company="{{$company}}" :area="{{$company_area}}" :positions="{{$positions}}" :auth-user="{{$auth_user}}"></apithy-positions-create>
    {{--
    <div class="sixteen wide mobile sixteen wide tablet sixteen wide computer column">
        <div class="ui segments no-shadows no-borders">
            <div class="ui segment">
                @include('partials.flash')
            </div>
            <div class="ui segment">
                <sui-form size="standard" method="POST" action="{{ route("positions.store",[$company,$company_area]) }}">
                    {{ csrf_field() }}
                    @include('admin.companies.positions.form')
                </sui-form>
                <apithy-areaposition-form
                        url="{{ route("positions.store",[$company,$company_area]) }}"
                        method="POST"
                        csrf="{{csrf_token()}}"
                        :data-list="{{$areas}}"
                        :parent="{{$positions}}"
                        current-area="{{$company_area->id}}"
                        current-company="{{$company->id}}"
                        cancel-url="{{ route("positions.index",[$company,$company_area]) }}">
                </apithy-areaposition-form>
            </div>
        </div>
    </div>
    --}}
@endsection