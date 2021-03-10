<template>
    <div class="card-footer">
        <div class="card-footer-item" v-if="assignation">
            <button class="ui basic green button" @click.stop="$parent.details">
                <i class="eye icon"></i>
                Detalles
            </button>
        </div>
        <div class="card-footer-item" v-else>
            <button class="" @click.stop="$parent.details">
                <i class="eye icon"></i>
                Detalles
            </button>
            <button class="" v-bind:class="{ loading: $parent.loading }" v-if="$parent.enrolled_button" @click="$parent.enrollUpdate">
                <i class="calendar times outline icon"></i>
                Leave
            </button>
            <button class="" v-bind:class="{ loading: $parent.loading }" v-else @click="$parent.enroll">
                <i class="calendar plus outline icon"></i>
                Enroll
            </button>

        </div>
    </div>
</template>

<script>
    export default {
        name: "ExperienceEnrollButtons",
        props: {
            enrolled: {
                type: Boolean,
                default: false
            },
            course: {
                type: Object,
                required: true
            }
        },
        computed: {
            assignation() {
                if (this.course.assignation && this.course.assignation.type === 'mandatory') {
                    return true;
                }
                return false;
            }
        }
    }
</script>