<apithy-tabs inline-template>
    <div>
        <div class="hero is-primary is-bold">
            <div class="hero-body" style="padding: 24px 0;">
                <div class="container">
                    <div class="columns">
                        <div class="column has-text-right">
                            <h1 class="title">{{ $auth->user()->full_name }}</h1>
                            <h2 class="subtitle">{{ $auth->user()->email }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hero-foot">
                <div class="container">
                    <div class="tabs is-boxed">
                        <ul>
                            <li class="{{ url_is(url('dashboard'), true) ? 'is-active' : '' }}">
                                <a href="{{ url('dashboard') }}">
                                    <span class="icon is-small"><i class="fa fa-tachometer"></i></span>
                                    <span>{{ trans('messages.dashboard') }}</span>
                                </a>
                            </li>
                            {{--
                            @can('showWindowSuper', $auth->user())
                            <li class="{{ url_is(url('roles'), true) ? 'is-active' : '' }}" @click="roles = true">
                                <a href="{{ url('roles') }}">
                                    <span class="icon is-small"><i class="fa fa-user-circle-o"></i></span>
                                    <span>{{ trans('messages.roles') }}</span>
                                </a>
                            </li>
                            @endcan
                            --}}
                            @can('showWindowSuper', $auth->user())
                            <li class="{{ url_is(url('users'), true) ? 'is-active' : '' }}">
                                <a href="{{ url('users') }}">
                                    <span class="icon is-small"><i class="fa fa-users"></i></span>
                                    <span>{{ trans('messages.users') }}</span>
                                </a>
                            </li>
                            @endcan
                            @can('showWindowSuper', $auth->user())
                            <li class="{{ url_is(url('apps'), true) ? 'is-active' : '' }}">
                                <a href="{{ url('apps') }}">
                                    <span class="icon is-small"><i class="fa fa-rocket"></i></span>
                                    <span>{{ trans('messages.apps') }}</span>
                                </a>
                            </li>
                            @endcan
                            {{--
                            @can('showWindowSuper', $auth->user())
                            <li class="">
                                <a href="">
                                    <span class="icon is-small"><i class="fa fa-lock"></i></span>
                                    <span>{{ trans('messages.permissions') }}</span>
                                </a>
                            </li>
                            @endcan
                            @can('showWindowSuper', $auth->user())
                            <li class="">
                                <a href="">
                                    <span class="icon is-small"><i class="fa fa-bar-chart"></i></span>
                                    <span>{{ trans('messages.analytics') }}</span>
                                </a>
                            </li>
                            @endcan
                            @can('showWindowSuper', $auth->user())
                            <li class="">
                                <a href="">
                                    <span class="icon is-small"><i class="fa fa-cogs"></i></span>
                                    <span>{{ trans('messages.global_setting') }}</span>
                                </a>
                            </li>
                            @endcan
                            --}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <nav class="nav has-shadow">
            <div class="container">
                <div class="nav-left">
                    @if(url_is(url('dashboard'), true))
                    <a class="nav-item is-tab {{ url_is(url('/profile'), true) ? 'is-active' : '' }}" href="{{ url('/profile') }}">
                        {{ trans('messages.profile') }}
                    </a>
                    <a class="nav-item is-tab {{ url_is(url('/security')) ? 'is-active' : '' }}" href="{{ url('/security') }}">
                        {{ trans('messages.security') }}
                    </a>
                    @endif
                    @if(url_is(url('users'), true))
                    <a class="nav-item is-tab {{ url_is(url('users')) ? 'is-active' : '' }}" href="{{ url('users') }}">
                        {{ trans('messages.catalog') }}
                    </a>
                    <a class="nav-item is-tab {{ url_is(url('users/invitations')) ? 'is-active' : '' }}" href="{{ url('users/invitations') }}">
                        {{ trans('messages.invitations') }}
                    </a>
                    @endif
                </div>
            </div>
        </nav>
    </div>
</apithy-tabs>
