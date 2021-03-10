<div class="ui segments no-borders no-shadows">
    <div class="ui segment">
        <h5 class="ui header">
            <i class="user icon"></i>{{ trans('messages.job_titles') }}
        </h5>
    </div>
    <div class="ui segment">
        @if ($positions->count() > 0)
            <sui-table class="ui very basic table tablet stackable striped">
                <sui-table-header>
                    <sui-table-row>
                        <sui-table-header-cell>{{ trans('messages.job_area') }}</sui-table-header-cell>
                        <sui-table-header-cell>{{ trans('messages.short_name') }}</sui-table-header-cell>
                        <sui-table-header-cell>{{ trans('messages.created_at') }}</sui-table-header-cell>
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
                                    @can('view', [Apithy\CompanyPosition::class, $company_area])
                                        <a class="ui black"
                                           href="{{ route("positions.show",[$company_area->company,$company_area,$position]) }}">
                                            <div class="ui button primary">
                                                <i class="eye icon"></i>
                                                {{ trans('messages.show') }}
                                            </div>
                                        </a>
                                    @endcan
                                    @can('update', [Apithy\CompanyPosition::class, $company_area])
                                        <a class="ui black"
                                           href="{{ route("positions.edit",[$company_area->company,$company_area,$position]) }}">
                                            <div class="ui button primary">
                                                <i class="edit icon"></i>
                                                {{ trans('messages.edit') }}
                                            </div>
                                        </a>
                                    @endcan
                                    @can('delete', [Apithy\CompanyPosition::class, $company_area])
                                        @include('partials.form.delete-button', [
                                            'action' => route("positions.destroy",[$company_area->company,$company_area,$position]),
                                            'id'=>$position->id
                                        ])
                                    @endcan
                                </div>
                            </sui-table-cell>
                    </sui-table-row>
                @endforeach
            </sui-table-body>
        </sui-table>
        @else
            <div class="notification">
                {{ trans('messages.no_results') }}
            </div>
        @endif
    </div>
</div>