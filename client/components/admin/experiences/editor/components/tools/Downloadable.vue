<template>
    <div class="">
        <div class="min-h-32 py-6 cursor-pointer">
            <input class="hidden h-0" type="file" ref="file" @change="updateFile">
            <template v-if="!file_selected">
                <template v-if="!uploading.show">
                    <div class="" @click="choseFile">
                        <div class="w-1/2 text-center mx-auto">
                            <span><i class="fas fa-file-download text-4xl"></i></span>
                        </div>
                        <div class="w-1/2 text-center mx-auto mt-3">
                            <span>Selecciona un archivo</span>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <div class="">
                        <div class="w-1/2 ml-auto mr-auto">
                            <div class="bg-teal-400 text-xs leading-none text-center text-white"
                                 :style="`width: ${uploading.value}%`">
                                {{uploading.value}}%
                            </div>
                        </div>
                    </div>
                </template>
            </template>
            <template v-else>
                <div class="">
                    <a :href="file_selected" target="_blank">
                        <button class="button">Descargar archivo {{ file_name }}</button>
                    </a>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Downloadable",
        props: {
            componentOptions: {
                required: true,
                type: Object
            }
        },
        computed: {
            file_selected () { return this.component.source; },
            file_name () { return this.component.name; },
            upload_route () {
                let url = route('sessions.media.upload', {
                    experience: this.$parent.getExperience('system_id'),
                    session: this.$parent.getSession('system_id')
                });
                console.log(url)
                return url;
            }
        },
        methods: {
            choseFile (event) {
                this.$refs.file.click();
            },
            submitImage (file) {
                let url = this.upload_route,
                    formData = new FormData(),
                    upload_data = null;
                formData.append('uuid', this.componentOptions.uid);
                formData.append('title', `image-uid-${this.componentOptions.uid}`);
                formData.append('description', '');
                formData.append('media-type', 'file');
                formData.append('file', file);

                axios.post(url, formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        },
                        onUploadProgress: (progressEvent) => {
                            if (progressEvent.lengthComputable) {
                                //console.log(progressEvent.loaded + ' ' + progressEvent.total);
                                let loaded = (progressEvent.loaded / progressEvent.total) * 100;
                                this.uploading.value = parseInt(loaded);
                                if(!this.uploading.show) this.uploading.show = true;
                                //this.updateProgressBarValue(progressEvent);
                            }
                        }
                    })
                    .then(response => { upload_data = response.data })
                    .then( () => {
                        this.updateData(upload_data);
                    })
                    .catch(error => { console.log(error) })
            },
            updateFile (event) {
                if (_.isEmpty(event.target.files)) return 0;
                let file = event.target.files[0];
                this.submitImage(file);
            },
            updateData (upload_data) {
                if (_.isNull(upload_data) || _.isEmpty(upload_data)) return 0;
                this.component.source = upload_data.full_path_url;
                this.component.name = upload_data.filename;
            }
        },
        data () {
            return {
                component: this.componentOptions.config,
                uploading: {
                    show: false,
                    value: 0,
                    error: null
                }
            };
        }
    }
</script>

<style scoped>

</style>
