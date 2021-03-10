@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.companies'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <a href="{{route('companies.index')}}" class="section">Empresas</a>
        <i class="right chevron icon divider"></i>
        <div class="section">{{ $company_area->company->name }}</div>
        <i class="right chevron icon divider"></i>
        <div class="section">Areas</div>
        <i class="right chevron icon divider"></i>
        <div class="section">{{ $company_area->name }}</div>
        <i class="right chevron icon divider"></i>
        <div class="active section">Puestos</div>
    </div>
@endSection

@section('body')
    <div class="sixteen wide mobile sixteen wide tablet sixteen wide computer column">
        <div class="ui segments no-shadows no-borders">
            <div class="ui segment">
                @include('admin.companies.positions.filter')
            </div>
            <div class="ui segment">
                @if ($positions->count() > 0)
                <sui-table class="ui very basic table tablet stackable striped">
                    <sui-table-header>
                        <sui-table-row>
                            <sui-table-header-cell>{!! v_sort('area_id', trans('messages.job_area')) !!}</sui-table-header-cell>
                            <sui-table-header-cell>{!! v_sort('short_name') !!}</sui-table-header-cell>
                            <sui-table-header-cell>{!! v_sort('created_at') !!}</sui-table-header-cell>
                            <sui-table-header-cell></sui-table-header-cell>
                        </sui-table-row>
                    </sui-table-header>
                    <sui-table-body>
                        @foreach($positions as $position)
                            <sui-table-row>
                                <sui-table-cell>{{ $position->name }}</sui-table-cell>
                                <sui-table-cell>{{ $position->short_name }}</sui-table-cell>
                                <sui-table-cell>{{ $position->created_at }}</sui-table-cell>
                                <sui-table-cell class="is-narrow">
                                    <div class="ui">
                                        @can('view', [\Apithy\CompanyPosition::class, $company_area])
                                        <a class="ui black"
                                           href="{{ route("positions.show",[$company_area->company,$company_area,$position]) }}">
                                            <div class="ui button primary">
                                                <i class="eye icon"></i>
                                                {{ trans('messages.show') }}
                                            </div>
                                        </a>
                                        @endcan
                                        @can('update', [\Apithy\CompanyPosition::class, $company_area])
                                        <a class="ui black"
                                           href="{{ route("positions.edit",[$company_area->company,$company_area,$position]) }}">
                                            <div class="ui button primary">
                                                <i class="edit icon"></i>
                                                {{ trans('messages.edit') }}
                                            </div>
                                        </a>
                                        @endcan
                                        @can('delete', [\Apithy\CompanyPosition::class, $company_area])
                                            @include('partials.form.delete-button', ['action' => route("positions.destroy",[$company_area->company,$company_area,$position]), 'id'=>$position->id])
                                        @endcan
                                    </div>
                                </sui-table-cell>
                            </sui-table-row>
                        @endforeach
                    </sui-table-body>
                </sui-table>
                {{-- $positions->links() --}}
                @else
                <div class="notification">
                    {{ trans('messages.no_results') }}
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
