<template>
    <div class="position-absolute cart-notify" v-if="visible">
        <div class="card">
            <div class="cart-content">
                <div class="row pt-4 pb-4">
                    <div class="col-3">
                        <div class="image-container width-75">
                            <div class="experience-image">
                                <img :src="added_experience.full_path_cover" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-9 m-top-10">
                        <div class="row">
                            <div class="col-12">
                                <div class="">
                                    <div class="row justify-content-between">
                                        <div class="col-auto">
                                            <span class="experience-title">
                                                {{ $t('messages.adding_to_cart') }}
                                            </span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="close-cart-notify button" @click="close">
                                                <span class="icon"><i class="fa fa-times"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                                </div>
                                    <div class="col-4">
                                        <div class="has-text-weight-bold">
                                            {{ added_experience.name }}
                                        </div>
                                        <div class="">
                                            {{ added_experience.challenges.total }} {{ $t('messages.challenge') }}
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="">
                                            <div class="cart-details">
                                                <div class="">
                                                    {{ cart_details.experiences.total }} {{ $t('messages.products_in_the_cart') }}
                                                </div>
                                                <div class="">
                                                    {{ cart_details.challenges.total }} {{ $t('messages.challenge') }}
                                                </div>
                                                <div class="has-text-weight-bold">
                                                    {{ cart_details.total }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4 has-text-centered">
                                        <div class="">
                                    <span class="button is-primary width-50" @click="goToCart">
                                        {{ $t('messages.go_to_cart') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "CartNotify",
        mounted() {
            let data = this.$cookie.get('cartNotify');
            if(data !== null) {
                data = JSON.parse(data);
                this.load(data);
            }
            setTimeout(() => {
                this.close();
            },20000);
        },
        methods: {
            setAddedExperience(data) {
                this.added_experience = {
                    name: data.name,
                    full_path_cover: data.full_path_cover,
                    challenges: {
                        total: data.challenges.total
                    }
                };
            },
            setCartDetails(data) {
                this.cart_details = {
                    total: data.price,
                    experiences: {
                        total: data.experiences.total
                    },
                    challenges: {
                        total: data.challenges.total
                    }
                };
            },
            getAddedExperience() {
                return this.added_experience;
            },
            getCartDetails() {
                return this.cart_details;
            },
            load(data) {
                this.setAddedExperience(data.experience);
                this.setCartDetails(data.cart);
                this.visible = true;
            },
            clean() {
                this.added_experience = {
                    name: '',
                        full_path_cover: '',
                        challenges: {
                        total: 0
                    }
                };
                this.cart_details = {
                    total: 0,
                        experiences: {
                        total: 0
                    },
                    challenges: {
                        total: 0
                    }
                };
                this.visible = false;
            },
            close() {
                this.toggleVisibility();
                this.clean();
                this.$cookie.delete('cartNotify');
            },
            toggleVisibility() {
                this.visible = !this.visible;
            },
            goToCart() {
                this.close();
                document.location.href = route('shopping-cart.checkout').toString();
            }
        },
        data() {
            return {
                visible: false,
                added_experience: {
                    name: '',
                    full_path_cover: '',
                    challenges: {
                        total: 0
                    }
                },
                cart_details: {
                    total: 0,
                    experiences: {
                        total: 0
                    },
                    challenges: {
                        total: 0
                    }
                }
            }
        }
    }
</script>

<style scoped>
    .cart-notify {
        left: 0;
        right: 0;
        margin: 0 auto;
        z-index: 4;
    }
    .close-cart-notify {
        left: -20px;
    }
    .image-container {
        height: 100px;
        overflow: hidden;
        border-radius: 20px;
        margin: 15px auto;
    }
    .experience-image {
        transform: translate(0px, -15%)
    }
    .experience-title {
        color: #59D694;
        font-size: 1.3rem;
        font-weight: bold;
    }
</style>