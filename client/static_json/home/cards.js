export const cards = [
    {
        step: 1,
        breadcrumbName: 'users',
        image: 'https://s3-us-west-2.amazonaws.com/cdn.apithy.com/home_page/angle_home_options_usuarios.jpg',
        title: 'Usuarios',
        paragraph: 'Invita colaboradores y deja que aNGLE te ayude con la gestión',
        styles: {
            title: {
                color: '#00B2FF'
            },
            title_decoration: {
                'background-color': '#00B2FF'
            },
        },
        subCards: [
            {
                image: 'https://s3-us-west-2.amazonaws.com/cdn.apithy.com/home_page/angle_home_options_usuarios.jpg',
                title: 'Crear importar',
                paragraph: 'Registra individualmente de manera manual o carga desde un Excel múltiples colaboradores',
                styles: {
                    title: {
                        color: '#00B2FF'
                    },
                    title_decoration: {
                        'background-color': '#00B2FF'
                    }
                },
                buttonLink: route('users.create')
            },
            {
                image: 'https://s3-us-west-2.amazonaws.com/cdn.apithy.com/home_page/angle_home_options_usuarios.jpg',
                title: 'Invitar',
                paragraph: 'Envía invitaciones de registro solamente con el correo electrónico o número celular',
                styles: {
                    title: {
                        color: '#00B2FF'
                    },
                    title_decoration: {
                        'background-color': '#00B2FF'
                    }
                },
                buttonLink: route('invitations.index')
            },
            {
                image: 'https://s3-us-west-2.amazonaws.com/cdn.apithy.com/home_page/angle_home_options_usuarios.jpg',
                title: 'Gestionar',
                paragraph: 'Visualiza quiénes ya se registraron',
                styles: {
                    title: {
                        color: '#00B2FF'
                    },
                    title_decoration: {
                        'background-color': '#00B2FF'
                    }
                },
                buttonLink: route('users.index')
            },
        ]
    },
    {
        step: 2,
        breadcrumbName: 'assignments',
        image: 'https://s3-us-west-2.amazonaws.com/cdn.apithy.com/home_page/angle_home_options_asignar.jpg',
        title: 'Asignar',
        paragraph: 'Elige las experiencias que necesita tu equipo para su plan de crecimiento',
        styles: {
            title: {
                color: '#F2AF23'
            },
            title_decoration: {
                'background-color': '#F2AF23'
            }
        },
        subCards: [
            {
                image: 'https://s3-us-west-2.amazonaws.com/cdn.apithy.com/home_page/angle_home_options_asignar.jpg',
                title: 'apithy store',
                paragraph: 'Mira todas las experiencias que tienes y asígnalas a tus colaboradores',
                styles: {
                    title: {
                        color: '#00B2FF'
                    },
                    title_decoration: {
                        'background-color': '#00B2FF'
                    }
                },
                buttonLink: route('experiences.index')
            },
            {
                image: 'https://s3-us-west-2.amazonaws.com/cdn.apithy.com/home_page/angle_home_options_asignar.jpg',
                title: 'Gestionar licencias',
                paragraph: 'Selecciona las experiencias para crear los planes de carrera de tus colaboradores',
                styles: {
                    title: {
                        color: '#00B2FF'
                    },
                    title_decoration: {
                        'background-color': '#00B2FF'
                    }
                },
                buttonLink: route('licenses.index')
            },
        ]
    },
    {
        step: 3,
        breadcrumbName: 'tags',
        image: 'https://s3-us-west-2.amazonaws.com/cdn.apithy.com/home_page/angle_home_options_etiquetas.jpg',
        title: 'Etiquetas',
        paragraph: 'Descubre la flexibilidad para hacer equipos, crear áreas y agrupar conocimientos',
        styles: {
            title: {
                color: '#FF6644'
            },
            title_decoration: {
                'background-color': '#FF6644'
            }
        },
        subCards: [
            {
                image: 'https://s3-us-west-2.amazonaws.com/cdn.apithy.com/home_page/angle_home_options_etiquetas.jpg',
                title: 'Areas',
                paragraph: 'Integra equipos de las áreas que ya existen en tu organización',
                styles: {
                    title: {
                        color: '#00B2FF'
                    },
                    title_decoration: {
                        'background-color': '#00B2FF'
                    }
                },
                shortCard: true,
                buttonLink: route('taxonomy.index', {tab: 'areas'})
            },
            {
                image: 'https://s3-us-west-2.amazonaws.com/cdn.apithy.com/home_page/angle_home_options_etiquetas.jpg',
                title: 'Puestos',
                paragraph: 'Define el puesto de cada uno de tus colaboradores',
                styles: {
                    title: {
                        color: '#00B2FF'
                    },
                    title_decoration: {
                        'background-color': '#00B2FF'
                    }
                },
                shortCard: true,
                buttonLink: route('taxonomy.index', {tab: 'positions'})
            },
            /*{
                image: 'https://s3-us-west-2.amazonaws.com/cdn.apithy.com/home_page/angle_home_options_etiquetas.jpg',
                title: 'Organizacionales',
                paragraph: 'Define su plan de crecimiento asignando experiencias',
                styles: {
                    title: {
                        color: '#00B2FF'
                    },
                    title_decoration: {
                        'background-color': '#00B2FF'
                    }
                },
                shortCard: true,
                buttonLink: route('taxonomy.index')
            },*/
            {
                image: 'https://s3-us-west-2.amazonaws.com/cdn.apithy.com/home_page/angle_home_options_etiquetas.jpg',
                title: 'Habilidades',
                paragraph: 'Establece las habilidades de cada uno de tus colaboradores',
                styles: {
                    title: {
                        color: '#00B2FF'
                    },
                    title_decoration: {
                        'background-color': '#00B2FF'
                    }
                },
                shortCard: true,
                buttonLink: route('taxonomy.view', 'abilities')
            },
            {
                image: 'https://s3-us-west-2.amazonaws.com/cdn.apithy.com/home_page/angle_home_options_etiquetas.jpg',
                title: 'Certificaciones',
                paragraph: 'Asigna certificaciones a tus colaboradores',
                styles: {
                    title: {
                        color: '#00B2FF'
                    },
                    title_decoration: {
                        'background-color': '#00B2FF'
                    }
                },
                shortCard: true,
                buttonLink: route('taxonomy.view', 'certifications')
            },
            /*{
                image: 'https://s3-us-west-2.amazonaws.com/cdn.apithy.com/home_page/angle_home_options_etiquetas.jpg',
                title: 'Equipos',
                paragraph: 'Define su plan de crecimiento asignando experiencias',
                styles: {
                    title: {
                        color: '#00B2FF'
                    },
                    title_decoration: {
                        'background-color': '#00B2FF'
                    }
                },
                shortCard: true,
                buttonLink: route('taxonomy.view', 'teams')
            },*/
            {
                image: 'https://s3-us-west-2.amazonaws.com/cdn.apithy.com/home_page/angle_home_options_etiquetas.jpg',
                title: 'Personalizadas',
                paragraph: 'Crea equipos según tus necesidades',
                styles: {
                    title: {
                        color: '#00B2FF'
                    },
                    title_decoration: {
                        'background-color': '#00B2FF'
                    }
                },
                shortCard: true,
                buttonLink: route('taxonomy.view', 'custom')
            }
        ]
    },
    {
        step: 4,
        breadcrumbName: 'stalking',
        image: 'https://s3-us-west-2.amazonaws.com/cdn.apithy.com/home_page/angle_home_options_seguimiento.jpg',
        title: 'Seguimiento',
        paragraph: 'Conoce el crecimiento de cada persona o equipo',
        styles: {
            title: {
                color: '#8A53FF'
            },
            title_decoration: {
                'background-color': '#8A53FF'
            }
        },
        subCards: [
            {
                image: 'https://s3-us-west-2.amazonaws.com/cdn.apithy.com/home_page/angle_home_options_seguimiento.jpg',
                title: 'Resumen',
                paragraph: 'Ten a la mano una visualización general de la actividad de tu equipo',
                styles: {
                    title: {
                        color: '#00B2FF'
                    },
                    title_decoration: {
                        'background-color': '#00B2FF'
                    }
                },
                buttonLink: route('dashboard.index')
            },
            {
                image: 'https://s3-us-west-2.amazonaws.com/cdn.apithy.com/home_page/angle_home_options_seguimiento.jpg',
                title: 'Datos generales',
                paragraph: 'Revisa todas tus acciones y conoce el progreso',
                styles: {
                    title: {
                        color: '#00B2FF'
                    },
                    title_decoration: {
                        'background-color': '#00B2FF'
                    }
                },
                buttonLink: route('dashboard.general')
            },
            {
                image: 'https://s3-us-west-2.amazonaws.com/cdn.apithy.com/home_page/angle_home_options_seguimiento.jpg',
                title: 'Gestionar',
                paragraph: 'Estudia a detalle los resultados de formación por persona o por experiencia',
                styles: {
                    title: {
                        color: '#00B2FF'
                    },
                    title_decoration: {
                        'background-color': '#00B2FF'
                    }
                },
                buttonLink: route('dashboard.experiences')
            },
        ]
    },
]
