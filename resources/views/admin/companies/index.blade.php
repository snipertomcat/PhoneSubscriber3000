@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.companies'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <div class="active section">Empresas</div>
    </div>
@endSection

@section('body')
    <hr width="1">
    <apithy-companies-filter :countries="{{ json_encode($countries) }}"></apithy-companies-filter>
    <hr width="1">
    <apithy-companies-list :auth-user="{{ $auth_user }}" :shows-created="true" ref="companies_list"></apithy-companies-list>
    {{--
    <div class="sixteen wide mobile sixteen wide tablet sixteen wide computer column">
        <div class="ui segments no-borders no-shadows">
            <div class="ui segment">
                @include('partials.flash')
            </div>
            <div class="ui segment">
                @include('admin.companies.filter')
            </div>
            <div class="ui segment">
                @if ($companies->count() > 0)
                    <sui-table class="very basic">
                        <sui-table-header>
                            <tr>
                                <sui-table-header-cell>{!! v_sort('name') !!}</sui-table-header-cell>
                                <sui-table-header-cell>{!! v_sort('short_name') !!}</sui-table-header-cell>
                                <sui-table-header-cell>{!! v_sort('country_id', trans('messages.country')) !!}</sui-table-header-cell>
                                <sui-table-header-cell>{!! v_sort('Email',trans('messages.contact_email')) !!}</sui-table-header-cell>
                                <sui-table-header-cell></sui-table-header-cell>
                            </tr>
                        </sui-table-header>
                        <sui-table-body>
                            @foreach($companies as $company)
                                <sui-table-row>
                                    <sui-table-cell>{{ $company->name }}</sui-table-cell>
                                    <sui-table-cell>{{ $company->short_name }}</sui-table-cell>
                                    <sui-table-cell>{{ $company->country->name }}</sui-table-cell>
                                    <sui-table-cell>{{ $company->contact_email }}</sui-table-cell>
                                    <sui-table-cell class="is-narrow">
                                        <div class="ui">
                                            @can('view', $company)
                                                <a class="ui black"
                                                   href="{{ route("companies.show", [$company]) }}">
                                                    <div class="ui button primary">
                                                        <i class="eye icon"></i>
                                                        {{ trans('messages.show') }}
                                                    </div>
                                                </a>
                                            @endcan
                                            @can('update', $company)
                                                <a class="ui black"
                                                   href="{{ route("companies.edit", [$company]) }}">
                                                    <div class="ui button primary">
                                                        <i class="edit icon"></i>
                                                        {{ trans('messages.edit') }}
                                                    </div>
                                                </a>
                                            @endcan
                                        </div>
                                    </sui-table-cell>
                                </sui-table-row>
                            @endforeach
                        </sui-table-body>
                    </sui-table>
                    {{ $companies->appends(request()->except(['page']))->links() }}
            </div>
        </div>
    </div>
    @else
        <div class="notification">
            {{ trans('messages.no_results') }}
        </div>
    @endif
--}}
@endsection