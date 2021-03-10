<template>
    <div class="d-inline-flex ml-1 mr-0 dashboard-menu full-width">
        <div class="col-auto spacer"></div>
        <div class="col-auto pl-5 pr-5 dashboard-item"
             :class="{'active':isActive(item.route_name)}"
             @click="goTo(item.route_name, true)"
             v-for="item in dashboard.items">
            <span class="font-18">{{ item.label }}</span>
        </div>
        <div class="col spacer"></div>
    </div>
</template>

<script>
    export default {
        name: "DashboardMenu",
        methods: {
            goTo (target, use_route_name = false) {
                if (use_route_name) {
                    document.location.href = route(target).toString()
                } else {
                    document.location.href = '/' + route
                }
            },
            isActive (route_name) {
                let current = route().current();
                return route_name === current;
            },
            capitalize (string) {
                return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
            }
        },
        data () {
            return {
                dashboard: {
                    items: [
                        {
                            route_name: 'dashboard.index',
                            label: this.capitalize(this.$t('messages.summary'))
                        },
                        {
                            route_name: 'dashboard.general',
                            label: this.capitalize('Datos generales')
                        },
                        {
                            route_name: 'dashboard.experiences',
                            label: this.capitalize(this.$t('messages.experiences') + ' y '+ this.$t('messages.users'))
                        },
                        {
                            route_name: 'dashboard.reports',
                            label: this.capitalize('Indicador de cumplimiento')
                        },
                    ]
                }
            }
        }
    }
</script>

<style scoped>
    .dashboard-menu {
        margin-top: 20px;
        overflow-x: auto;
        margin-bottom: 16px;
    }
    .dashboard-menu .spacer {
        min-width: 53px;
        border-bottom: 1px solid #F5A623;
    }
    .dashboard-menu .dashboard-item {
        padding: 7px 29px;
        color: #444444;
        cursor: pointer;
        border-bottom: 1px solid #F5A623;
    }
    .dashboard-menu .dashboard-item.active {
        color: #F5A623;
        border-left: 1px solid #F5A623;
        border-top: 1px solid #F5A623;
        border-right: 1px solid #F5A623;
        /*border-bottom: 1px solid #E5E5E5;*/
        border-bottom: 1px solid #fff;
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
    }
    @media only screen and (width: 768px) and (orientation: portrait) {
        .dashboard-menu {
            margin-top: 0;
        }
    }
</style>
<style>
    body {
        background-color: #fff;
    }
</style>
