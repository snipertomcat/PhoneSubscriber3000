@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.companies'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <a href="{{route('companies.index')}}" class="section">Empresas</a>
        <i class="right chevron icon divider"></i>
        <div class="section">{{ $company->name }}</div>
        <i class="right chevron icon divider"></i>
        <div class="section">Areas</div>
        <i class="right chevron icon divider"></i>
        <div class="active section">{{ $company_area->name }}</div>
    </div>
@endSection

@section('body')
    @if(Auth::user()->isSuper()) )<apithy-company-menu :company-data="{{$company}}"></apithy-company-menu>@endif

    <apithy-areas-view :auth-user="{{$auth_user}}"
                       :company="{{$company}}"
                       :area-data="{{$company_area}}"
                       :areas="{{json_encode($areas)}}">
    </apithy-areas-view>

    <hr width="3">

    <apithy-positions-list :company-data="{{$company}}" :area-data="{{$company_area}}" :auth-user="{{$auth_user}}"></apithy-positions-list>

    {{--
    <div class="sixteen wide mobile sixteen wide tablet sixteen wide computer column">
        <div class="ui segments no-borders no-shadows">
            @include('partials.flash')
            <div class="ui segment">
                @can('update', $company_area)
                    <a class="ui button primary icon"
                       href="{{ route("areas.edit",[$company,$company_area]) }}">
                        {{ trans('messages.edit') }}
                        <i class="edit icon"></i>
                    </a>
                @endcan

                @can('delete', $company_area)
                    @include(
                        'partials.form.delete-button',
                        ['action' => route("areas.destroy",[$company,$company_area]),
                        'id'=>$company_area->id]
                    )
                @endcan

                @can('fetch',$company_area)
                    <a class="ui button greenli"
                       href="{{ route("positions.index",[$company,$company_area]) }}">
                        Positions
                    </a>
                @endcan

                @can('create',$company_area)
                <a class="ui black"
                   href="{{ route("positions.create",[$company,$company_area]) }}">
                    <div class="ui button yellowli">
                        <i class="plus icon"></i>
                        {{ trans('messages.add_job_title') }}
                    </div>
                </a>
                @endcan
            </div>
            <div class="ui segment">
                <sui-table class="definition">
                    <sui-table-body>
                        <sui-table-row>
                            <sui-table-cell class="two wide colum"><strong>{{ trans('messages.name') }}</strong>
                            </sui-table-cell>
                            <sui-table-cell>{{ $company_area->name }}</sui-table-cell>
                        </sui-table-row>
                        <sui-table-row>
                            <sui-table-cell><strong>{{ trans('messages.short_name') }}</strong></sui-table-cell>
                            <sui-table-cell>{{ $company_area->short_name }}</sui-table-cell>
                        </sui-table-row>
                        <sui-table-row>
                            <sui-table-cell><strong>{{ trans('messages.description') }}</strong></sui-table-cell>
                            <sui-table-cell>{{ $company_area->description }}</sui-table-cell>
                        </sui-table-row>
                    </sui-table-body>
                </sui-table>
                @include('admin.companies.positions.quick_list')
            </div>
        </div>
    </div>
    --}}
@endsection