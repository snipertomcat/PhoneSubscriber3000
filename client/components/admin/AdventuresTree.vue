<template id="tpl-tree-node">
    <div :class="['tree-node', { 'empty-node': amIEmptyNode }]"
         :draggable="amIEmptyNode ? undefined : true"
         @dragstart.stop="handleDragStart"
         @dragenter.stop="handleDragEnter"
         @dragover.stop="handleDragOver"
         @drop.stop="handleDrop"
         @dragleave.stop="handleDragLeave"
         @dragend.stop="handleDragEnd"

    >
        <div class="tree-item ui">
            <div class="img-wrapper">
                <img width="60px" v-bind:src="data.image">
            </div>
            <div class="label-wrapper">
            <span class="ui floated left">{{ data.label /* undefined if empty node */ }}</span>
            </div>
        </div>
        <div v-if="displayedChildren.length" class="tree-node-children">
            <apithy-adventures-tree
                    v-for="(child, idx) in displayedChildren"
                    :key="idx"
                    :data="child"
                    :experience="experience"
                    :shared="shared"
                    :vm-idx="idx">
            </apithy-adventures-tree>
        </div>
    </div>
</template>
<script>

    export default {
        name: 'apithy-adventures-tree', // as a recursive component
        props: {
            experience:{ type: Object, required: true },
            data: { type: Object, required: true }, // { label: String, children: [{ label, children }] } or an empty object
            shared: { type: Object, default: () => ({/* draggingVm: VueInstance */}) }, // shared data for all the instances
            vmIdx: Number // current instance's index in v-for (if exists)
        },
        computed: {
            amIEmptyNode () {
                // data of an empty node is an empty object: {}
                return !this.data.label
            },
            /**
             * Generate adjacent empty nodes for each real node, in order to implement insertion actions
             * e.g. [R1, R2, R3] === displayed as ===> [E1, R1, E2, R2, E3, R3, E4]
             * (R means Real node, E means Empty node)
             */
            displayedChildren () {
                const realNodes = this.data.children;
                if (!realNodes || !realNodes.length) return [];// an empty node or a real node without children

                return realNodes.reduce((displayedChildren, realNode) => {
                    displayedChildren.push(realNode, {}/* <--- empty node */);
                    return displayedChildren;
                }, [{}]);
            },
            getTree(){
                    return this.$parent.data;
            }
        },
        methods: {
            /**
             * @context {this} - instance of drop-into node
             */
            isAllowedToDrop () {
                let vm = this;
                const { draggingVm } = vm.shared;
                // limitation 1: this cannot be the parent of the dragging node
                if (vm === draggingVm.$parent) {
                    return false
                }

                // limitation 2: this cannot be the adjacent empty node of the dragging node
                if (vm.$parent === draggingVm.$parent && Math.abs(vm.vmIdx - draggingVm.vmIdx) === 1) {
                    return false
                }

                // limitation 3: this cannot be the dragging node itself or its descendant
                while (vm) {
                    if (vm === draggingVm) return false;
                    vm = vm.$parent.$options.name === 'TreeNode' ? vm.$parent : null
                }

                return true
            },
            /**
             * @context {this} - instance of dragging node
             */
            handleDragStart (e) {
                // this.shared.draggingVm = this // cannot ensure reactive
                this.$set(this.shared, 'draggingVm', this);// ensure reactive
                this.$el.classList.add('dragging-node');
                e.dataTransfer.dropEffect = this.isAllowedToDrop() ? 'copy' : 'none'

            },
            /**
             * @context {this} - instance of drop-into node
             */
            handleDragEnter () {
                this.$el.classList.add(this.isAllowedToDrop() ? 'drop-allowed' : 'drop-not-allowed')
            },
            /**
             * @context {this} - instance of drop-into node
             * Note that this function invokes once per every few hundred milliseconds
             */
            handleDragOver (e) {
                e.preventDefault();// must!!!
                e.dataTransfer.dropEffect = this.isAllowedToDrop() ? 'copy' : 'none'
            },
            /**
             * @context {this} - instance of drop-into node
             */
            handleDrop () {
                this.revertClass()
                if (!this.isAllowedToDrop()) return;

                const { draggingVm } = this.shared;

                // remove from the original place
                const realIdxOfOrigin = (draggingVm.vmIdx - 1) / 2;
                draggingVm.$parent.data.children.splice(realIdxOfOrigin, 1);

                // case 1: drop into an empty node
                if (this.amIEmptyNode) {
                    this.$parent.data.children.splice(this.vmIdx / 2, 0, draggingVm.data);
                    return;
                }

                // case 2: drop into a real node as its child
                if (!this.data.children) {
                    this.$set(this.data, 'children', []) // ensure reactive
                }
                this.data.children.push(draggingVm.data)
            },
            /**
             * @context {this} - instance of drop-into node
             */
            handleDragLeave () {
                this.revertClass()
            },
            /**
             * @context {this} - instance of dragging node
             */
            handleDragEnd () {
                this.shared.draggingVm = null;
                this.$el.classList.remove('dragging-node')
                this.sendData();
            },
            revertClass () {
                this.$el.classList.remove('drop-allowed', 'drop-not-allowed')
            },
            getParentData(){
               return this.$root.$children[1].data;
            },
            sendData(){
                axios({
                    method: 'PUT',
                    url: route('experiences.adventures.update',{experience:this.experience.system_id}),
                    params: {
                        adventures: this.getParentData(),
                    }
                }).then((response) => {
                    window.alert(response.data.message);
                }).catch((error) => {
                    console.error(error);
                });
            }
        }
    }
</script>
<style scoped>
    .tree-node {
        display: list-item;
        padding-left: 2px;
        list-style: none;
        line-height: 20px;
        border-left: 2px dashed gray;
    }

    .tree-item{
        min-height: 30px;
        background-color: #FFF;
        margin-bottom: 11px;
        display: block;
        overflow: hidden;
        cursor: move;
        box-shadow: 0 1px 2px 0 rgba(34,36,38,.15);
        padding: 1em 1em;
        border-radius: .28571429rem;
        border: 1px solid rgba(34,36,38,.15);
        position: relative;
    }

    .tree-item .img-wrapper, .tree-item .label-wrapper{
        float:left
    }

    .empty-node {
        height: 0px;
        display: none;
    }

    .empty-node .tree-item {
        background-color: transparent;
        min-height: 10px;
        margin-bottom: 0px;
    }

    .empty-node img {
        display: none;

    }

    .tree-node-children {
        margin-left: 50px; /* indention */
    }

    .dragging-node {
        color: orange;
        opacity: 0.8;
    }

    .drop-allowed {
        height: 80px;
        border: 1px dashed gray;
        border-radius: 5px;
        overflow: hidden;
        cursor: cell;
    }

    .drop-not-allowed {
        opacity: 0.5;
        color:red;
        cursor: no-drop;
    }

    .label-wrapper{
        font-weight: bold;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        width: 50%;
        height: 50%;
        margin: auto 90px;
    }

</style>