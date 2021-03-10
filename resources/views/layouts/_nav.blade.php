<div class="navslide navwrap">
    <div class="ui menu icon borderless grid" data-color="inverted white">
        <a class="item labeled openbtn">
            <i class="bars big icon"></i>
        </a>
        <div class="item">
            @yield('section-title')
        </div>
        <!--
        <a class="item labeled expandit" onclick="toggleFullScreen(document.body)">
            <i class="ion-arrow-expand big icon"></i>
        </a>
        -->
        <div class="right menu colhidden">
            <div class="ui dropdown item">
                <img class="ui mini circular image" src="{{ $auth->user()->avatarUrl() }}" alt="label-image"/>
                <div class="menu">
                    <a class="item labeled icon" href="{{url('/profile')}}">
                        <i class="user circle icon"></i>
                        Perfil
                    </a>
                    <a class="item labeled icon" href="{{'/profile/edit'}}">
                        <i class="cogs circle icon"></i>
                        Configuración
                    </a>
                    <div class="ui divider"></div>
                    <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: none;">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </form>

                    @impersonating
                    <a href="{{ route('impersonate.leave') }}">Regresar a mi cuenta</a>
                    @endImpersonating
                    <a class="item labeled icon" onclick="event.preventDefault();document.getElementById('logout-form').submit()">
                        <i class="sign out circle icon"></i>
                        {{ trans('messages.logout') }}

                    </a>
                </div>
            </div>
        </div>
    </div>
</div>