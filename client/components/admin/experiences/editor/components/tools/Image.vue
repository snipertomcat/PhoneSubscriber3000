<template>
    <div class="">
        <div class="image-width-selector">
            <div class=""><span>Ajustar al: </span></div>
            <div class="selector" @click="setWidth(100)"><span>100%</span></div>
            <div class="selector" @click="setWidth(50)"><span>50%</span></div>
            <div class="selector" @click="setWidth(30)"><span>30%</span></div>
        </div>
        <div class="min-h-32 py-6 cursor-pointer">
            <input class="hidden h-0" type="file" ref="file" @change="updateFile">
            <template v-if="!image_selected">
                <template v-if="!uploading.show">
                    <div class="" @click="choseFile">
                        <div class="w-1/2 text-center mx-auto">
                            <span><i class="fas fa-file-image text-4xl"></i></span>
                        </div>
                        <div class="w-1/2 text-center mx-auto mt-3">
                            <span>Selecciona una imagen</span>
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
                <div class="flex justify-center" @click="choseFile">
                    <img :src="image_selected" :width="image_width" alt="">
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    let destiny = '/experiences/jKRLV/sessions/je6Aq/media-content';
    export default {
        name: "Image",
        props: {
            componentOptions: {
                required: true,
                type: Object
            }
        },
        computed: {
            image_selected () { return this.component.source; },
            image_width () { return `${this.component.width}%`; },
            upload_route () {
                let url = route('sessions.media.upload', {
                    experience: this.$parent.getExperience('system_id'),
                    session: this.$parent.getSession('system_id')
                });
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
                formData.append('media-type', 'image');
                formData.append('file', file);

                axios.post(url, formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        },
                        onUploadProgress: (progressEvent) => {
                            if (progressEvent.lengthComputable) {
                                let loaded = (progressEvent.loaded / progressEvent.total) * 100;
                                this.uploading.value = parseInt(loaded);
                                if(!this.uploading.show) this.uploading.show = true;
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
            },
            setWidth(value) {
                this.component.width = value;
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
