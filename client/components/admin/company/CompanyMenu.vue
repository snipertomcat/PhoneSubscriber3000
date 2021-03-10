<template>
    <div class="container block-centered ">
        <div class="row menu">
            <div class="col-3 col-lg-1 has-text-centered" v-for="menu in menus">
                <div :class="{'menu-item pointer':true, 'active':thisPlace(menu)}">
                    <span @click="goTo(menu.url)">
                        {{ $t('messages.'+menu.label).toUpperCase() }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "CompanyMenu",
        props: {
            companyData: {
                type: Object,
                required: true
            },
            sectionActive: {
                type: String,
                required: false,
                default: null
            }
        },
        methods: {
            goTo (url) {
                window.location.href = url;
            },
            thisPlace (menu) {
                if(this.sectionActive) {
                    return (this.sectionActive == menu.label);
                }
                else {
                    let actual_url = window.location.href.toString();
                    return (actual_url == menu.url || actual_url == menu.url+'/edit');
                }
            }
        },
        computed: {
        },
        data() {
            return {
                menus: [
                    {
                        url: route('companies.show',[this.companyData.id]).toString(),
                        label: 'data'
                    },
                    {
                        url: route('companies.users',[this.companyData.id]).toString(),
                        label: 'users'
                    },
                    {
                        url: route('areas.index',[this.companyData.id]).toString(),
                        label: 'job_areas'
                    },
                    {
                        url: '#',
                        label: 'settings'
                    },
                ]
            }
        }
    }
</script>

<style scoped>
    .menu {
        margin: 20px 0px;
        overflow-x: auto;
    }
    .menu-item {}
    .menu-item.active {
        border-bottom: 2px #2D7EFC solid;
    }
    .menu-item.active span{
        color: #2D7EFC !important;
    }
    .menu-item span{
        color: #6FAAFB;
    }
</style>