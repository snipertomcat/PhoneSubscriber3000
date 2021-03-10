@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.apps'))

@section('body')

    <div class="sixteen wide mobile sixteen wide tablet sixteen wide computer column">
        <div class="ui segments">
            <div class="ui segment">
                <h5 class="ui header">
                    <i class="rocket icon"></i>Aplicaciones
                </h5>
            </div>
            <div class="ui segment">
                @include('apps.filter')
            </div>
            <div class="ui segment">
                <sui-table class="ui very basic table tablet stackable striped">
                    <sui-table-header>
                        <sui-table-row>
                            <sui-table-header-cell>Icono</sui-table-header-cell>
                            <sui-table-header-cell>Name</sui-table-header-cell>
                            <sui-table-header-cell>Status</sui-table-header-cell>
                            <sui-table-header-cell>Url</sui-table-header-cell>
                            <sui-table-header-cell>Secret</sui-table-header-cell>
                            <sui-table-header-cell></sui-table-header-cell>
                            <sui-table-header-cell></sui-table-header-cell>
                            <sui-table-header-cell></sui-table-header-cell>
                        </sui-table-row>
                    </sui-table-header>
                    <sui-table-body>
                        @foreach($apps as $app)
                            <sui-table-row>
                                <sui-table-cell>Imagen</sui-table-cell>
                                <sui-table-cell>{{$app->name}}</sui-table-cell>
                                <sui-table-cell class="is-narrow">
                                    <span class="tag is-success">
                                        Activa
                                    </span>
                                </sui-table-cell>
                                <sui-table-cell>{{$app->redirect}}</sui-table-cell>
                                <sui-table-cell>{{$app->secret}}</sui-table-cell>
                                <sui-table-cell class="is-narrow">
                                    <a class="button is-small">
                                        <span class="icon is-small">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                        <span>{{ trans('messages.editt') }}</span>
                                    </a>
                                </sui-table-cell>
                                <sui-table-cell class="is-narrow">
                                    <a class="button is-small">
                                        <span class="icon is-small">
                                            <i class="fa fa-address-card"></i>
                                        </span>
                                        <span>{{ trans('messages.renew_credentials') }}</span>
                                    </a>
                                </sui-table-cell>
                                <sui-table-cell class="is-narrow">
                                    <a class="button is-small">
                                        <span class="icon is-small">
                                            <i class="fa fa-address-card"></i>
                                        </span>
                                        <span>{{ trans('messages.delete') }}</span>
                                    </a>
                                    <div class="ui teal buttons">
                                        <div class="ui button">Save</div>
                                        <div class="ui floating dropdown icon button">
                                            <i class="dropdown icon"></i>
                                            <div class="menu">
                                                <div class="item"><i class="edit icon"></i> Edit Post</div>
                                                <div class="item"><i class="delete icon"></i> Remove Post</div>
                                                <div class="item"><i class="hide icon"></i> Hide Post</div>
                                            </div>
                                        </div>
                                    </div>
                                </sui-table-cell>
                            </sui-table-row>
                        @endforeach
                    </sui-table-body>
                </sui-table>
            </div>
        </div>
    </div>

@endsection