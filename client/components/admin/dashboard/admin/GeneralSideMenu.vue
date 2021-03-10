<template>
    <div class="items-container pt-4 pb-4">
        <div class="item mb-2 pointer" :data-item="item.target" :class="{'active':item.active}" v-for="item in side.items" @click="setActive(item.target)">
            <div class="p-2 has-text-weight-bold text-capitalize">{{ item.label }}</div>
        </div>
    </div>
</template>

<script>
    import { bus } from "../../../../app_platform";

    export default {
        name: "GeneralSideMenu",
        mounted: function () {
            this.setActive('licenses');
        },
        methods: {
            setActive (target) {
                let prev_el = document.querySelector('.item.active')
                let new_el = document.querySelector('.item[data-item="'+target+'"]')

                if (prev_el !== null) {
                    prev_el.classList.remove('active')
                }
                if (prev_el !== null) {
                    new_el.classList.add('active');
                }

                _.each(this.side.items, item => {
                    item.active = target === item.target
                })

                bus.$emit('item-active', this.getItemActive())
            },
            getItemActive () {
                return _.find(this.side.items, {active: true})
            }
        },
        data () {
            return {
                side: {
                    items: [
                        {
                            label: this.$t('messages.dashboard.licenses'),
                            target: 'licenses',
                            active: true,
                        },
                        {
                            label: this.$t('messages.users'),
                            target: 'users',
                            active: false,
                        },
                        {
                            label: this.$t('messages.invitations'),
                            target: 'invitations',
                            active: false,
                        },
                        {
                            label: this.$t('messages.enrollment') + ' / ' + this.$t('messages.experiences'),
                            target: 'enrollments',
                            active: false,
                        },
                    ]
                }
            }
        }
    }
</script>

<style scoped>
    .items-container {
        box-shadow: 2px 4px 6px rgba(0, 0, 0, 0.15);
        height: 100%;
    }
    .items-container .item.active {
        background-color: #FFD79D;
    }
</style>