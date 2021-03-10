<template>
    <nav v-if="pagination.last_page > 1" class="pagination is-centered col-sm-8" aria-label="pagination">
        <a v-if="pagination.current_page > 1" class="pagination-previous" href="javascript:void(0)" aria-label="Previous"
           v-on:click.prevent="changePage(pagination.current_page - 1)">
            Anterior
        </a>

        <a class="pagination-next" v-if="pagination.current_page < pagination.last_page" href="javascript:void(0)"
           aria-label="Next"
           v-on:click.prevent="changePage(pagination.current_page + 1)">
            Siguiente
        </a>

        <ul class="pagination-list">
            <li>
                <a v-for="page in pagesNumber" v-bind:class="{'active': page == pagination.current_page}" class="pagination-link"
                   href="javascript:void(0)" v-on:click.prevent="changePage(page)">
                    {{ page }}
                </a>
            </li>
        </ul>
    </nav>
</template>
<script>
    export default {
        name: 'apithy-resource-paginator',
        props: {
            pagination: {
                type: Object,
                required: true
            }
        },
        computed: {
            pagesNumber() {

                if (!this.pagination.to) {
                    return [];
                }

                let from = this.pagination.current_page - this.pagination.per_page;

                if (from < 1) {
                    from = 1;
                }

                let to = from + (this.pagination.per_page * 2);

                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }

                let pagesArray = [];

                for (let page = from; page <= to; page++) {
                    pagesArray.push(page);
                }

                return pagesArray;
            }
        },
        methods: {
            changePage(page) {
                this.pagination.current_page = page;
                this.$emit('paginate');
            }
        },
    }
</script>