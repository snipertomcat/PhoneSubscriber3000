@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.users'))

@section('body')
    <div class="sixteen wide mobile sixteen wide tablet sixteen wide computer column">
        <div class="ui segments">
            <div class="ui segment">
                <h5 class="ui header">
                    <i class="users icon"></i>Usuarios
                </h5>
            </div>
            <div class="ui segment">
                @include('users.filter')
            </div>
            <div class="ui segment">
                <sui-table class="ui table tablet stackable striped">
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
                            <span class="tag is-{{ $user->activated ? 'success' : 'danger' }}">
                                {{$user->activated ? 'activo' : 'inactivo'}}
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
                                        @include('partials.form.delete-button', ['action' => url('users', [$user->id])])
                                    @endcan
                                </sui-table-cell>
                            </sui-table-row>
                        @endforeach
                    </sui-table-body>
                    <sui-table-footer class="full-width">
                    <sui-table-row>
                        <sui-table-header-cell colspan="6">
                            <div class="ui right floated small primary labeled icon button">
                                <i class="user icon"></i> Add User
                            </div>
                            <div class="ui small button">
                                Approve
                            </div>
                            <div class="ui small disabled button">
                                Approve All
                            </div>
                        </sui-table-header-cell>
                    </sui-table-row>
                    </sui-table-footer>
                </sui-table>
            </div>
        </div>
        {{ $users->links() }}
    </div>
@endsection