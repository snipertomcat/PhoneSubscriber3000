@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', 'Dashboard')

@section('body')
    {{--
    <div class="container">
        @if(Auth::user()->isSuper() || Auth::user()->isAdmin())
            <div class="row mb-3">
                @if(Auth::user()->isSuper())
                    <apithy-chart type="bar"
                                  target="companies"
                                  :datasets="{{json_encode($data_analytics['stats'])}}"
                                  :width="300"
                                  :height="300">
                    </apithy-chart>
                @endif

                <apithy-chart type="pie"
                              target="users"
                              :datasets="{{json_encode($data_analytics['stats'])}}"
                              :width="300"
                              :height="300">
                </apithy-chart>

                <apithy-chart type="pie"
                              target="invitations"
                              :datasets="{{json_encode($data_analytics['stats'])}}"
                              :width="300"
                              :height="300">
                </apithy-chart>

                <apithy-chart type="pie"
                              target="experiences"
                              :datasets="{{json_encode($data_analytics['stats'])}}"
                              :width="300"
                              :height="300">
                </apithy-chart>
            </div>

            <div class="row mb-3">
                <hr width="2" class="d-block d-lg-none">
                <apithy-table :data-analytics="{{$data_analytics['last']['users']}}"
                              title="Últimos 5 usuarios"
                              link-view-all="/users"
                              :columns="[
                            {
                                field: 'email',
                                label: 'Email',
                            },
                            {
                                field: 'registration_method',
                                label: 'Metodo de registro',
                            },
                            {
                                field: 'created_at',
                                label: 'Creado el',
                            },
                          ]">
                </apithy-table>
            </div>

            <div class="row mb-3">
                <hr width="2" class="d-block d-lg-none">
                <apithy-table :data-analytics="{{$data_analytics['last']['experiences']}}"
                              title="Últimas 5 Experiencias"
                              link-view-all="/experiences"
                              :columns="[
                            {
                                field: 'title',
                                label: 'Titulo',
                                width: '270',
                            },
                            {
                                field: 'type',
                                label: 'Tipo',
                            },
                            {
                                field: 'status',
                                label: 'Estado',
                            },
                            {
                                field: 'created_at',
                                label: 'Creado el:',
                            },
                          ]">
                </apithy-table>
            </div>

            <div class="row mb-3">
                <apithy-table :data-analytics="{{$data_analytics['last']['invitations']}}"
                              title=" Últimas 5 invitaciones"
                              link-view-all="/users/invitations"
                              :columns="[
                            {
                                field: 'email',
                                label: 'Email',
                            },
                            {
                                field: 'status',
                                label: 'Estatus',
                            },
                            {
                                field: 'created_at',
                                label: 'Enviada el:',
                            },
                          ]" inline-template>
                    <div class="col-12 col-lg-12" v-if="dataAnalytics.length">
                        <div class="card">
                            <div class="card-content">
                                <div class="row">
                                    <div class="col-12-has-text-left has-text-weight-bold">
                                        Últimas 5 invitaciones
                                    </div>
                                </div>
                                <div class="dashboard-table-container">
                                    <table class="table is-striped">
                                        <thead>
                                        <tr>
                                            <th class="collapsing">
                                                Email
                                            </th>
                                            <th>
                                                Estatus
                                            </th>
                                            <th>
                                                Enviada el:
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(invitation,index) in dataAnalytics">
                                            <td class="collapsing">
                                                <a :href="'/users/invitations?search='+invitation.email">
                                                    @{{invitation.email}}
                                                </a>
                                            </td>
                                            <td>
                                                @{{translateStatus(invitation.status)}}
                                            </td>
                                            <td>
                                                @{{tinyDate(invitation.created_at)}}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="has-text-right">
                                    <a href="/users/invitations">
                            <span class="">
                                Ver todo
                            </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </apithy-table>
            </div>
    </div>
    @else
        <apithy-student-analytics :data-analytics="{{ json_encode($data_analytics) }}"></apithy-student-analytics>
    @endif
    --}}
        @if(Auth::user()->isSuper() || Auth::user()->isAdmin())
            {{--
                <div class="container">
                    <div class="row mb-3">
                        <apithy-chart type="bar"
                                      target="companies"
                                      :datasets="{{json_encode($view['stats'])}}"
                                      :width="300"
                                      :height="300">
                        </apithy-chart>

                        <apithy-chart type="pie"
                                      target="users"
                                      :datasets="{{json_encode($view['stats'])}}"
                                      :width="300"
                                      :height="300">
                        </apithy-chart>

                        <apithy-chart type="pie"
                                      target="invitations"
                                      :datasets="{{json_encode($view['stats'])}}"
                                      :width="300"
                                      :height="300">
                        </apithy-chart>

                        <apithy-chart type="pie"
                                      target="experiences"
                                      :datasets="{{json_encode($view['stats'])}}"
                                      :width="300"
                                      :height="300">
                        </apithy-chart>
                    </div>

                    <div class="row mb-3">
                        <hr width="2" class="d-block d-lg-none">
                        <apithy-table :data-analytics="{{$view['last']['users']}}"
                                      title="Últimos 5 usuarios"
                                      link-view-all="/users"
                                      :columns="[
                        {
                            field: 'email',
                            label: 'Email',
                        },
                        {
                            field: 'registration_method',
                            label: 'Metodo de registro',
                        },
                        {
                            field: 'created_at',
                            label: 'Creado el',
                        },
                      ]">
                        </apithy-table>
                    </div>

                    <div class="row mb-3">
                        <hr width="2" class="d-block d-lg-none">
                        <apithy-table :data-analytics="{{$view['last']['experiences']}}"
                                      title="Últimas 5 Experiencias"
                                      link-view-all="/experiences"
                                      :columns="[
                        {
                            field: 'title',
                            label: 'Titulo',
                            width: '270',
                        },
                        {
                            field: 'type',
                            label: 'Tipo',
                        },
                        {
                            field: 'status',
                            label: 'Estado',
                        },
                        {
                            field: 'created_at',
                            label: 'Creado el:',
                        },
                      ]">
                        </apithy-table>
                    </div>

                    <div class="row mb-3">
                        <apithy-table :data-analytics="{{$view['last']['invitations']}}"
                                      title=" Últimas 5 invitaciones"
                                      link-view-all="/users/invitations"
                                      :columns="[
                                        {
                                            field: 'email',
                                            label: 'Email',
                                        },
                                        {
                                            field: 'status',
                                            label: 'Estatus',
                                        },
                                        {
                                            field: 'created_at',
                                            label: 'Enviada el:',
                                        },
                                      ]"
                                      inline-template>
                            <div class="col-12 col-lg-12" v-if="dataAnalytics.length">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="row">
                                            <div class="col-12-has-text-left has-text-weight-bold">
                                                Últimas 5 invitaciones
                                            </div>
                                        </div>
                                        <div class="dashboard-table-container">
                                            <table class="table is-striped">
                                                <thead>
                                                <tr>
                                                    <th class="collapsing">
                                                        Email
                                                    </th>
                                                    <th>
                                                        Estatus
                                                    </th>
                                                    <th>
                                                        Enviada el:
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr v-for="(invitation,index) in dataAnalytics">
                                                    <td class="collapsing">
                                                        <a :href="'/users/invitations?search='+invitation.email">
                                                            @{{invitation.email}}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        @{{translateStatus(invitation.status)}}
                                                    </td>
                                                    <td>
                                                        @{{tinyDate(invitation.created_at)}}
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="has-text-right">
                                            <a href="/users/invitations">
                                                <span class="">
                                                    Ver todo
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </apithy-table>
                    </div>
                </div>
        --}}
            <div class="ml-0 full-height">
                <apithy-menu-dashboard></apithy-menu-dashboard>
                @include($view['target'])
            </div>
        @elseif(Auth::user()->isPartner())
            <apithy-partner-dashboard></apithy-partner-dashboard>
        @else
            <!--apithy-student-dashboard></apithy-student-dashboard-->
            <div class="container">
                <apithy-student-analytics :data-analytics="{{ json_encode($view) }}"></apithy-student-analytics>
            </div>
        @endif
@endsection