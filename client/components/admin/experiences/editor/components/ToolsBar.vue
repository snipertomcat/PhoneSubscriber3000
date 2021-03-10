<template>
    <div class="toolbar">
        <div class="w-1/4 h-full flex">
            <template v-if="!principalSelected">
                <button class="w-1/5 rounded-l-lg tools-item" @click="select('reset')">
                    <span class="icon"><i class="fas fa-times"></i></span>
                </button>
                <button class="w-4/5 tools-item">
                    <span class="icon">
                        <template v-if="!!selected_section.icon">
                                <i :class="`fas ${selected_section.icon}`"></i>
                            </template>
                            <template v-else>
                                <i class="fas fa-save"></i>
                            </template>
                    </span>
                    <span class="text">{{ $t(`messages.editor.${selected_section.title}`) }}</span>
                </button>
            </template>
            <template v-else>
                <div class="tools-item w-full rounded-l-lg">
                    <span class="text-xl">{{ $t('messages.editor.tools') }}</span>
                </div>
            </template>
        </div>

        <div class="w-2/4 flex border-l-2 border-r-2" style="overflow-x: auto; overflow-Y: hidden;">
            <template v-if="principalSelected">
                <template v-for="section in tools_section.tools">
                    <button class="tools-item w-1/2" @click="select(section.title)">
                        <span class="icon">
                            <template v-if="!!section.icon">
                                <i :class="`fas ${section.icon}`"></i>
                            </template>
                            <template v-else>
                                <i class="fas fa-save"></i>
                            </template>
                        </span>
                        <span class="text">{{ $t(`messages.editor.${section.title}`) }}</span>
                    </button>
                </template>
            </template>
            <template v-else-if="!!selected_section">
                <template v-for="tool in selected_section.list">
                    <button class="w-1/4 px-1 tools-item" @click="selectTool(tool)" style="min-width:100px;">
                        <span class="icon">
                            <template v-if="!!tool.icon">
                                <i :class="`fas ${tool.icon}`"></i>
                            </template>
                            <template v-else>
                                <i class="fas fa-save"></i>
                            </template>
                        </span>
                        <span class="text">{{ $t(`messages.editor.${tool.component_name}`) }}</span>
                    </button>
                </template>
            </template>
        </div>

        <div class="w-1/4 flex">
            <button class="tools-item w-1/2" @click="preview">
                <span class="icon"><i class="fas fa-eye"></i></span>
                <span class="text">{{ $t('messages.editor.preview') }}</span>
            </button>
            <button class="tools-item w-1/2 rounded-r-lg" @click="publish">
                <span class="icon"><i class="fas fa-upload"></i></span>
                <span class="text">{{ $t('messages.editor.publish') }}</span>
            </button>
        </div>
    </div>
</template>

<script>
    import bus from "../../../../../helpers/bus";
    import { index } from "../../../../../helpers";

    export default {
        name: "ToolsBar",
        props: {
            toolsBar: {
              required: true
            }
        },
        computed: {
            principalSelected () { return !this.selected_section; },
            selected_section () { return this.tools_section.selected.section; },
            selected_tool () { return this.toolsBar.selected.tool; },
            selected_tab () { return this.toolsBar.selected.tab; }
        },
        methods: {
            select (section = null) {
                if (_.isNull(section) || _.isEmpty(section)) return 0;
                if (section === 'reset') this.tools_section.selected.section = null;
                _.each(this.tools_section.tools, sec => {
                    if (sec.title === section) {
                        sec.selected = true;
                        this.tools_section.selected.section = sec;
                    } else {
                        sec.selected = false;
                    }
                })
            },
            selectTool(tool) {
              this.toolsBar.selected.tool = tool;
              this.toolsBar.config = tool.config;
              bus.$emit("addTool", index.clone(tool));
            },
            selectTab(tab) {
              if (_.isEmpty(this.selected_tool)) return 0;
              this.toolsBar.selected.tab = tab;
            },
            preview() {
                bus.$emit('preview');
            },
            publish() {
                bus.$emit('publish');
            }
        },
        data() {
            return {
                tools_section: this.toolsBar
            };
        }
    };
</script>

<style>
</style>
