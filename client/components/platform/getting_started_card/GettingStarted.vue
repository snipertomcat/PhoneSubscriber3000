<template>
    <div class="getting-started">
        <div class="overlay"></div>
        <div class="content">
            <div class="cards-content">
                <Stack></Stack>
                <NavigationButtons></NavigationButtons>
                <NavigationProgressBar v-if="session.started"></NavigationProgressBar>
            </div>
        </div>
    </div>
</template>

<script>
    import Stack from './Stack';
    import Dialog from './Dialog';
    import NavigationButtons from './navigation/NavigationButtons';
    import NavigationProgressBar from './navigation/NavigationProgressBar';
    import { mutations } from '../../../helpers/store';

    export default {
        name: "GettingStarted",
        components: {
            Stack,
            NavigationButtons,
            NavigationProgressBar,
            Dialog
        },
        computed: {
            navigation() {
                return mutations.getNavigation();
            }
        },
        created() {
            mutations.setSession(this.session);
            mutations.setNavigation();
            mutations.startSession();
        },
        data() {
            return {
                session: {
                    title: 'Getting started',
                    image: null,
                    description: null,
                    locked: false,
                    finished: false,
                    started: false,
                    abilities: [],
                    cards: [
                        {
                            title: null,
                            component: 'getting-started',
                            elements: {
                                step: null,
                                title: 'Bienvenido',
                                text: 'A continuación te explicamos qué necesitas para comenzar a utilizar aNGLE',
                                image: 'https://s3-us-west-2.amazonaws.com/cdn.apithy.com/getting_started/1-min.jpg',
                                image_position: null
                            }
                        },
                        {
                            title: null,
                            component: 'getting-started',
                            elements: {
                                step: 1,
                                title: 'Usuarios',
                                text: 'Comienza agregando a los colaboradores que desees formar',
                                image: 'https://s3-us-west-2.amazonaws.com/cdn.apithy.com/getting_started/2-min.jpg',
                                image_position: null
                            }
                        },
                        {
                            title: null,
                            component: 'getting-started',
                            elements: {
                                step: 2,
                                title: 'Asignar',
                                text: 'Establece qué experiencias necesitan para su formación',
                                image: 'https://s3-us-west-2.amazonaws.com/cdn.apithy.com/getting_started/3-min.jpg',
                                image_position: null
                            }
                        },
                        {
                            title: null,
                            component: 'getting-started',
                            elements: {
                                step: 3,
                                title: 'Seguimiento',
                                text: 'Puedes agruparlos por planes de carrera, áreas o conocimientos',
                                image: 'https://s3-us-west-2.amazonaws.com/cdn.apithy.com/getting_started/4-min.jpg',
                                image_position: null
                            }
                        },
                        {
                            title: null,
                            component: 'getting-started',
                            elements: {
                                step: 4,
                                title: 'Etiquetas',
                                text: 'Visualiza el crecimiento de cada uno de tus colaboradores o equipos',
                                image: 'https://s3-us-west-2.amazonaws.com/cdn.apithy.com/getting_started/5-min.jpg',
                                image_position: null
                            }
                        }
                    ]
                }
            };
        }
    }
</script>

<style scoped>
    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 5000;
        background: linear-gradient(179.49deg, #1A6BF7 9.87%, #0040AF 149.84%);
    }
    .content {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    .header {
        position: relative;
        height: 60px;
        width: 100%;
        margin-left: auto;
        margin-right: auto;
        align-items: center;
        justify-content: flex-end;
        display: flex;
        padding-left: 3%;
        padding-right: 3%;
     }
    .close-button {
        justify-content: center;
        align-items: center;
        cursor: pointer;
        display: flex;
        width: 33px;
        height: 33px;
        color: #ffffff;
        border-radius: 50%;
        background-color: #fd664a;
        box-shadow: 0 4px 4px rgba(0, 0, 0, 0.25);
        z-index: 5002;
    }
    .cards-content {
        position: absolute;
        width: 100%;
        min-width: 300px;
        height: 100%;
        min-height: 550px;
        z-index: 5001;
        display: inline-flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
</style>