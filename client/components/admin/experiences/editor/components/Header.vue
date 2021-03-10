<template>
    <div class="">
        <template v-if="!loading">
            <div class="canvas-header" @click="choseFile">
                <input class="hidden h-0" type="file" ref="file" @change="updateFile">
                <template v-if="has_image">
                    <img :src="header.source" :alt="header.title">
                </template>
                <template v-else>
                    <div class="p-3 mt-5">
                        <div class="w-1/2 text-center mx-auto">
                            <span><i class="far fa-image text-4xl"></i></span>
                        </div>
                        <div class="w-1/2 text-center mx-auto mt-3">
                            <span>Selecciona o arrastra una imagen al header</span>
                        </div>
                    </div>
                </template>
            </div>
        </template>
    </div>
</template>

<script>
    import bus from '../../../../../helpers/bus';
    export default {
        name: "Header",
        inject: ['getExperience','getSession'],
        props: {
            headerData: {
                required: true,
                type: Object
            }
        },
        computed: {
            upload_route () {
                let url = route('sessions.media.upload', {
                    experience: this.getExperience('system_id'),
                    session: this.getSession('system_id')
                });
                return url;
            },
            header () { return this.headerData },
            has_image () { return !_.isNull(this.header.source) || !_.isEmpty(this.header.source) }
        },
        mounted () {
            bus.$on('updateHeader', () => this.updateHeader());
            //this.setHeader();
        },
        methods: {
            setHeader () {
                bus.$emit('setHeader', this.header);
            },
            updateHeader () {
                this.loading = true;
                setTimeout(() => {
                    this.loading = false;
                }, 10);
            },
            choseFile (event) {
                this.$refs.file.click();
            },
            updateFile (event) {
                if (_.isEmpty(event.target.files)) return 0;
                let file = event.target.files[0];
                this.submitImage(file);
            },
            submitImage (file) {
                let url = this.upload_route,
                    formData = new FormData();
                //formData.append('uuid', 0);
                formData.append('title', `image-header`);
                formData.append('description', '');
                formData.append('media-type', 'image');
                formData.append('file', file);

                axios.post(url, formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(response => {
                        let upload_data = response.data;
                        this.header.source = upload_data.full_path_url;
                        this.header.name = upload_data.filename;
                        bus.$emit('setHeader', this.header);
                    })
                    .catch(error => { console.log(error) })
            },
        },
        data () {
            return {
                loading: false
            }
        }
    }
</script>

<style scoped>

</style>