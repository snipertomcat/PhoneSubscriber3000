<template>
    <div class="container">
        <hr width="2">
        <apithy-experiences-filter :abilities="abilities_data" :authors="authors_data"></apithy-experiences-filter>
        <div class="experience-carousel-wrapper">
            <div v-if="has_data" class="experience-carousel-inner">
                <b-collapse class="panel" :open.sync="isOpen">

                    <div class="row" v-if="show">
                        <div class="col-12 col-md-6 col-lg-3" v-for="(experience,index) in experiences.data">
                            <experience-card :key="experience.id"
                                             :data-experience="experience"
                                             :enrolled="hasEnrolledIn(experience)"
                                             :user-id="user.id"
                                             :user="user">
                            </experience-card>
                        </div>
                    </div>
                </b-collapse>
            </div>
        </div>
    </div>
</template>

<script>
    import ExperienceCard from './ExperienceCard';
    import ExperienceFilter from './ExperienceFilter';

    export default {
        name: "apithy-experience-carousel",
        components: {
            ExperienceCard,
            'apithy-experiences-filter': ExperienceFilter,

        },
        props: {
            user: {
                type: Object,
                default: {}
            },
            url: {
                required: false,
                default: () => {
                    return route('student.index')
                }
            },
            abilities_data: {
                default: []
            },
            authors_data: {
                default: []
            },
            types_data: {
                default: []
            },
            from: {
                type: String,
                required: true
            },
            title_carrousel: {
                type: String,
                required: true,
                default: 'Disponibles'
            },
            experiences_data: {},
            init_load: "",

        },
        beforeMount() {
            if (this.init_load) {
                this.getData();
            } else {
                if (this.experiences.data) {
                    this.has_data = true;
                    this.show = true;
                    this.setData(this.experiences);
                }
            }
        },
        methods: {
            loading() {

            },
            finishLoad() {

            },
            fetchData(page) {
                this.params.page = page || 1;
                this.getData();
            },
            getData() {
                this.loading();
                axios({
                    method: 'GET',
                    url: this.url_to,
                    params: {'page': this.experiences.current_page}
                }).then((response) => {
                    this.setData(response.data);
                    if (this.hasElements(response.data.data)) {
                        this.show = true;
                        this.has_data = true;
                    }
                    this.finishLoad();
                }).catch((error) => {
                    this.finishLoad();
                    console.error(error)
                });
            },
            hasEnrolledIn(experience) {
                let evaluation = false;
                this.enrollments.forEach((enrolledExperience, index) => {
                    if (experience.id === enrolledExperience.id) {
                        evaluation = (enrolledExperience.pivot.status === 1);
                    }
                });
                return evaluation;
            },
            hasElements(data) {
                return (data.length > 0);
            },
            reload() {
                this.show = false;
                setTimeout(() => {
                    this.show = true;
                }, 1000);
            },
            setData(data) {
                switch (this.from) {
                    case 'experiences':
                        this.experiences = [];
                        this.experiences = data;
                        break;
                    case 'enrolled':
                        this.experiences = [];
                        this.experiences = data;
                        break;
                    case 'assignations':
                        let experiences = data.data.map((value, index) => {
                            let experience_tmp = value.experience;
                            delete value.experience;
                            experience_tmp.assignation = value;
                            return experience_tmp;
                        });
                        data.data = experiences;
                        this.experiences = [];
                        this.experiences = data;
                        break;
                }
            },
            next() {
                this.$refs.slick.next();
            },

            prev() {
                this.$refs.slick.prev();
            },

            reInit() {
                // Helpful if you have to deal with v-for to update dynamic lists
                this.$nextTick(() => {
                    this.$refs.slick.reSlick();
                });
            },
        },
        computed: {
            place: () => {
                let place = document.location.pathname;
                return (place === '/enrolled')
            },
            collapseIcon() {
                return this.isOpen ? "fa-angle-up" : "fa-angle-down"
            }
        },
        data() {
            return {
                abilities: this.abilities_data,
                enrollments: this.user.enrollments,
                experiences: this.experiences_data,
                types: this.types_data,
                show: false,
                path: document.location.pathname,
                has_data: false,
                url_to: this.url,
                isOpen: true,
                slickOptions: {
                    slidesToShow: 5,
                    dots: true,
                    infinite: true,
                    speed: 300,
                    adaptiveHeight: true,
                    nextArrow: '<i class="fa fa-angle-right slick-arrow-next"></i>',
                    prevArrow: '<i class="fa fa-angle-left slick-arrow-prev"></i>',
                    responsive: [
                        {
                            breakpoint: 1024,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 3,
                                infinite: true,
                                dots: true,
                                adaptiveHeight: true
                            }
                        },
                        {
                            breakpoint: 600,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 2,
                                infinite: true,
                                dots: false,
                                adaptiveHeight: true
                            }
                        },
                        {
                            breakpoint: 480,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1,
                                infinite: true,
                                dots: false,
                                adaptiveHeight: true,
                                nextArrow: false,
                                prevArrow: false,
                            }
                        }
                    ]
                },
            }
        }
    }
</script>

<style scoped>

    .experience-carousel-wrapper {
        margin-bottom: 20px;
        margin-top: 20px;
    }

    .experience-carousel-wrapper .fa-angle-up, .experience-carousel-wrapper .fa-angle-up {
        cursor: pointer;
    }

</style>