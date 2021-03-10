<template>
    <div :class="{'wrapper': true, 'selected': component.selected }" :id="uid" @click="selectComponent">
        <div class="title-component">
            <span class="w-2/12">
                <template v-if="component.component_name">
                    {{ $t(`messages.editor.${component.component_name}`) }}
                </template>
                <template v-else>
                    {{ $t(`messages.editor.evaluation`) }}
                </template>
            </span>
            <span class="handle w-8/12 text-center"><i class="fas fa-grip-horizontal"></i></span>
            <div class="w-1/12"></div>
            <span class="w-1/12 text-center hover:bg-gray-800" @click="removeTool"><i class="fas fa-trash-alt"></i></span>
        </div>
        <div class="content-component">
            <template v-if="is_evaluable">
                <c-evaluation :component-options="component"></c-evaluation>
                <div class="evaluation-options">
                    <div class="trigger">
                        <i class="fas fa-gear"></i>
                    </div>
                </div>
            </template>
            <template v-else>
                <template v-if="is_image">
                    <c-image :component-options="component" :bus="bus"></c-image>
                </template>
                <template v-if="is_title">
                    <c-title :component-options="component" :bus="bus"></c-title>
                </template>
                <template v-if="is_text">
                    <c-text :component-options="component" :bus="bus"></c-text>
                </template>
                <template v-if="is_video">
                    <c-video :component-options="component" :bus="bus"></c-video>
                </template>
                <template v-if="is_downloadable">
                    <c-downloadable :component-options="component" :bus="bus"></c-downloadable>
                </template>
                <template v-if="is_external_resource">
                    <c-external-resource :component-options="component" :bus="bus"></c-external-resource>
                </template>
                <template v-if="is_send_text">
                    <c-send-text :component-options="component" :bus="bus"></c-send-text>
                </template>
                <template v-if="is_sort_images">
                    <c-sort-images :component-options="component" :bus="bus"></c-sort-images>
                </template>
                <template v-if="is_click_and_drop">
                    <c-click-and-drop :component-options="component" :bus="bus"></c-click-and-drop>
                </template>
            </template>
        </div>
    </div>
</template>

<script>
    import bus from '../../../../../helpers/bus';
    import {index} from '../../../../../helpers';
    import Title from './tools/Title';
    import Text from './tools/Text';
    import Image from './tools/Image';
    import Video from './tools/Video';
    import Downloadable from './tools/Downloadable';
    import Evaluation from './tools/Evaluation';
    import ExternalResource from './tools/ExternalResource';
    import SendText from './tools/SendText';
    import SortImages from './tools/SortImages';
    import ClickAndDrop from './tools/ClickAndDrop';

    export default {
        name: "ComponentWrapper",
        props: {
            componentData: {
                required: true,
                type: Object
            },
          index: {}
        },
        provide: {
            helper: index,
            selectComponent: this.selectComponent,
        },
        inject: ['getExperience','setExperience','getSession','setSession'],
        components: {
            'c-title': Title,
            'c-text': Text,
            'c-image': Image,
            'c-video': Video,
            'c-downloadable': Downloadable,
            'c-evaluation': Evaluation,
            'c-external-resource': ExternalResource,
            'c-send-text': SendText,
            'c-sort-images': SortImages,
            'c-click-and-drop': ClickAndDrop,
        },
        computed: {
            bus () { return bus },
            uid () { return `uid-${this.component.uid}` },
            is_title () {
                return this.component.component === 'Title';
            },
            is_text () {
                return this.component.component === 'Text';
            },
            is_image () {
                return this.component.component === 'Image';
            },
            is_video () {
                return this.component.component === 'Video';
            },
            is_downloadable () {
                return this.component.component === 'Downloadable';
            },
            is_external_resource () {
                return this.component.component === 'ExternalResource';
            },
            is_evaluable () {
                return this.component.component === 'Evaluation';
            },
            is_send_text () {
                return this.component.component === 'SendText';
            },
            is_sort_images () {
                return this.component.component === 'SortImages';
            },
            is_click_and_drop () {
                return this.component.component === 'ClickAndDrop';
            },
        },
        created () {
            this.component = this.componentData;
            if (!this.component.uid) {
                this.component.uid = this._uid;
            }
        },
        mounted() {
            bus.$on('disable_editor', () => this.disableEditor());
            bus.$on('enable_editor', () => this.enableEditor());
            this.enableEditor();
            if (this.component.selected) this.selectComponent();
        },
        methods: {
            removeTool () {
                bus.$emit('removeTool', false,{target:'component', index: this.index});
            },
            selectComponent () {
                bus.$emit('selectComponent', this.component.uid);
            },
            disableEditor () {
                let component = this.$children[0];
                if (_.has(component, 'editor')) {
                    if ('setOptions' in component.editor)
                        component.editor.setOptions({
                            editable: false,
                        })
                }
            },
            enableEditor () {
                let component = this.$children[0];
                if (_.has(component, 'editor')) {
                    if ('setOptions' in component.editor)
                        component.editor.setOptions({
                            editable: true,
                        })
                }
            },
        },
      data () {
          return {
            component: {},
          }
      }
    }
</script>

<style>
    .content-component input,
    .content-component .ProseMirror {
        border: 1px solid gray !important;
    }
</style>
