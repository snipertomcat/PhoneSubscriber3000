<div class="ui segment">
    <h5 class="ui header">
        <i class="users icon"></i>Usuarios
    </h5>
    <sui-table class="ui very basic table tablet stackable striped">
        <sui-table-header>
            <sui-table-row>
                <sui-table-header-cell>{!! v_sort('id') !!}</sui-table-header-cell>
                <sui-table-header-cell>{!! v_sort('name') !!}</sui-table-header-cell>
                <sui-table-header-cell>{!! v_sort('email') !!}</sui-table-header-cell>
                <sui-table-header-cell>{!! v_sort('company', trans('messages.company')) !!}</sui-table-header-cell>
                <sui-table-header-cell>{!! v_sort('activated', trans('messages.status')) !!}</sui-table-header-cell>
                <sui-table-header-cell></sui-table-header-cell>
            </sui-table-row>
        </sui-table-header>
        <sui-table-body>
            @foreach($users as $user)
                <sui-table-row>
                    <sui-table-cell>{{ $user->id }}</sui-table-cell>
                    <sui-table-cell>{{ $user->access }}</sui-table-cell>
                    <sui-table-cell>{{ $user->email }}</sui-table-cell>
                    <sui-table-cell>{{ $user->hasCompany() ? $user->company[0]->name : 'Indefinido' }}</sui-table-cell>
                    <sui-table-cell class="is-narrow">
                            <span class="ui horizontal {{ $user->activated ? 'green' : 'red' }} label">
                                {{$user->activated ? 'Activo' : 'Inactivo'}}
                            </span>
                    </sui-table-cell>
                    {{--<sui-table-cell class="is-narrow">
                        <a class="button is-small">
                            <span class="icon is-small">
                                <i class="fa fa-eye"></i>
                            </span>
                            <span>{{ trans('messages.show') }}</span>
                        </a>
                    </sui-table-cell>--}}
                    <sui-table-cell class="is-narrow">
                        @can('delete', $user)
                            @include('partials.form.delete-button', ['id'=>$user->id,'action' => route('users.destroy', [$user->id])])
                        @endcan
                    </sui-table-cell>
                </sui-table-row>
            @endforeach
        </sui-table-body>
    </sui-table>
    {{ $users->links() }}
</div>