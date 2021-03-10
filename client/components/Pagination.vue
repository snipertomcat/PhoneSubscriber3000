<template>
    <ul class="ui buttons" :class="customClass">
        <li :class="{'ui button basic':true, 'disabled':!hasPrev}"
            @click.prevent="changePage(prevPage)">
            <a href="#" aria-label="Previous">
                <span aria-hidden="true">{{ prevText }}</span>
            </a>
        </li>

        <li :class="{ 'active': current == page, 'ui button basic': true }"
            @click.prevent="changePage(page)"
            v-for="page in pages">
            <a href="#" v-text="page"></a>
        </li>

        <li :class="{'ui button basic':true, 'disabled':!hasNext}"
            @click.prevent="changePage(nextPage)">
            <a href="#" aria-label="Next">
                <span aria-hidden="true">{{ nextText }}</span>
            </a>
        </li>
    </ul>
</template>

<script>
    export default {
        props: {
            current: {
                type: Number,
                default: 1,
                required: true
            },
            total: {
                type: Number,
                default: 0,
                required: true
            },
            perPage: {
                type: Number,
                default: 0,
                required: true
            },
            pageSidesRange: {
                type: Number,
                default: 3
            },
            customClass: {
                type: String,
                default: 'custom-pagination-class'
            },
            prevText: {
                type: String,
                default: '<'
            },
            nextText: {
                type: String,
                default: '>'
            }
        },

        computed: {
            pages() {
                var pages = [];

                for (var i = this.rangeStart; i <= this.rangeEnd; i++) {
                    pages.push(i)
                }

                return pages
            },
            rangeStart() {
                let start = this.current - this.pageSidesRange
                return (start > 0) ? start : 1
            },
            rangeEnd() {
                let end = this.current + this.pageSidesRange
                return (end < this.totalPages) ? end : this.totalPages
            },
            totalPages() {
                return Math.ceil(this.total / this.perPage)
            },
            nextPage() {
                return this.current + 1
            },
            prevPage() {
                return this.current - 1
            },
            hasPrev() {
                return (this.current > 1);
            },
            hasNext() {
                return this.current < this.totalPages
            }
        },

        methods: {
            changePage(page) {
                this.$emit('page-changed', page)
            }
        }
    }
</script>

<style scoped>
    ul{
        padding: 0;
    }
</style>