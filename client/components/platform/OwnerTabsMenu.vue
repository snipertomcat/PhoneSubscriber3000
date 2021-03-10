<template>
    <div class="">
        <div class="d-none d-lg-block">
            <div class="row mr-0 tabs-menu" v-if="!is_empty_items">
                <div class="col-auto spacer"></div>
                <div class="col-auto pl-2 pr-2 pl-md-3 pr-md-3 pl-lg-5 pr-lg-5 tab-item"
                     v-for="item in items"
                     v-if="checkEnv(item)"
                     :class="{'active':isActive(item.route_name)}"
                     @click="goTo(item, true)">
                    <span class="d-block d-lg-none font-14">{{ item.label }}</span>
                    <span class="d-none d-lg-block font-18">{{ item.label }}</span>
                </div>
                <div class="col spacer"></div>
            </div>
        </div>
        <div class="d-block d-lg-none mobile-tabs">
            <ul class="mr-0 tabs-menu d-inline-flex" v-if="!is_empty_items">
                <li class="spacer"></li>
                <li class="align-self-end pl-2 pr-2 pl-md-3 pr-md-3 pl-lg-5 pr-lg-5 tab-item"
                    v-for="item in items"
                    v-if="checkEnv(item)"
                    :class="{'active':isActive(item.route_name)}"
                    @click="goTo(item, true)">
                    <span class="d-block d-lg-none font-14">{{ item.label }}</span>
                    <span class="d-none d-lg-block font-18">{{ item.label }}</span>
                </li>
                <li class="spacer width-100"></li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        name: "OwnerTabsMenu",
        props: {
            itemsData: {
                required: true,
                type: Array
            }
        },
        computed: {
            is_empty_items: function () {
                return _.isEmpty(this.items);
            }
        },
        data () {
            return {
                items: []
            }
        },
        mounted () {
            if (!_.isEmpty(this.itemsData)) {
                _.each(this.itemsData, (item, index) => {
                    if ('label'in item && ('route_name' in item || 'link' in item)) {
                        this.items.push(item)
                    } else {
                        console.error('The item on index', index, 'of array of OwnerTabsMenu don\'t have the correct format');
                    }
                })
            }
        },
        methods: {
            checkEnv(item) {
                let env = this.apithy_constants.ENV
                if ('env' in item) {
                    return item.env === env || env === 'local';
                } else {
                    return true
                }
            },
            goTo (item, use_route_name = false) {
                if ('route_name' in item || 'link' in item) {
                    if (use_route_name) {
                        if ('link' in item) {
                            document.location.href = item.link.toString()
                        } else {
                            document.location.href = route(item.route_name).toString()
                        }
                    } else {
                        document.location.href = '/' + item.route_name
                    }
                } else {
                    console.error('The item ', item.label, 'of array of OwnerTabsMenu don\'t have a valid route or link');
                }
            },
            isActive (route_name) {
                let current = route().current();
                return route_name === current;
            }
        }
    }
</script>

<style scoped>
    .tabs-menu {
        margin-top: 54px;
        margin-bottom: 35px;
    }
    .mobile-tabs .tabs-menu {
        margin-top: 20px;
        margin-bottom: 35px;
        width: 100vw;
        overflow-x: auto;
    }
    .mobile-tabs .tabs-menu .tab-item span {
        width: max-content;
    }
    .tabs-menu .spacer {
        min-width: 53px;
        border-bottom: 1px solid #F5A623;
    }
    .tabs-menu .tab-item {
        padding: 7px 29px;
        color: #444444;
        cursor: pointer;
        border-bottom: 1px solid #F5A623;
    }
    .tabs-menu .tab-item.active {
        color: #F5A623;
        border-left: 1px solid #F5A623;
        border-top: 1px solid #F5A623;
        border-right: 1px solid #F5A623;
        /*border-bottom: 1px solid #E5E5E5;*/
        border-bottom: 1px solid #fff;
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
    }
</style>
<style>
    body {
        background-color: #fff;
    }
    @media only screen and (width: 812px) and (orientation: landscape) {
        .owner-left-menu {
            min-width: 0;
        }
    }
</style>
