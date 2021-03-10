<template>
    <div id="" class="">
        <div class="dialog-backdrop" @click="closeDialog" v-if="show"></div>
        <div class="dialog">
            <div class="dialog-card" v-if="show">
                <div class="header">
                    <slot name="header"></slot>
                    <div class="close-button" @click="closeDialog">
                        <span><i class="fa fa-times"></i></span>
                    </div>
                </div>
                <div class="body">
                    <div class="body-content">
                        <slot name="body"></slot>
                    </div>
                </div>
                <div class="footer">
                    <div class="footer-content">
                        <slot name="footer"></slot>
                    </div>
                </div>
            </div>

            <slot name="secondaryDialog"></slot>
        </div>
    </div>
</template>

<script>
import { mutations, store } from '../../../helpers/store';

export default {
    name: 'Dialog',
    computed: {
        show() {
            return store.showDialog;
        }
    },
    methods: {
        closeDialog() {
            mutations.closeDialog();
        },
        openSession() {
            mutations.openSession();
        }
    }
};
</script>

<style scoped>
.dialog {
    position: absolute;
    width: 70%;
    max-width: 400px;
    top: 50%;
    left: 50%;
    z-index: 1080;
    transform: translate(-50%, -50%);
}
.dialog-card {
    background-color: #ffffff;
    box-shadow: -4px 4px 10px rgba(0, 0, 0, 0.25);
}
.header {
    position: relative;
    height: 30px;
}
.header .close-button {
    position: absolute;
    top: 0;
    right: 8px;
    width: 33px;
    height: 33px;
    display: flex;
    color: #ffffff;
    cursor: pointer;
    border-radius: 50%;
    align-items: center;
    justify-content: center;
    background-color: #320972;
    transform: translateY(-50%);
    box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
}
.body-content {
    padding: 16px;
    font-size: 18px;
}
.body-content > div:last-child {
    font-weight: bold;
}
.dialog-card .footer {
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #180f8a;
}
.btn {
    width: 140px;
    padding: 8px;
    cursor: pointer;
    text-align: center;
    border-radius: 4px;
    box-shadow: 0 4px 4px rgba(0, 0, 0, 0.25);
}
.btn a {
    color: unset;
    text-decoration: none;
}
.btn-red {
    color: #ffffff;
    background-color: #fd664a;
}
.start-button {
    color: #ffffff;
    background-color: #6531a0;
}
.secondary-dialog-card {
    margin-top: 16px;
    background: #ffffff;
    box-shadow: -4px 4px 10px rgba(0, 0, 0, 0.25);
}
.dialog-card-content {
    padding: 16px;
}
.dialog-card-content div {
    text-align: left;
}
.dialog-card-content > div:first-child {
    margin-bottom: 8px;
    font-weight: bold;
    font-size: 12px;
    color: #0244bb;
}
.dialog-card-content > div:last-child {
    font-size: 14px;
}
.dialog-backdrop {
    background-color: rgba(0, 0, 0, 0.5);
    width: 100vw;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    cursor: pointer;
    z-index: 1080;
}
</style>