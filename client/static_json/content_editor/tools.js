export const evaluableToolsArray = [
  'Evaluation',
  'TrueAndFalse',
  'MultipleOptions',
  'SortAnswers',
  'SortImages',
  'FillingBlanks',
  'ClickAndDrop'
];
export const tools = {
    basic: {
        title: 'basics',
        selected: false,
        icon: 'experience-editor-icon-basics',
        list: [
            {
                component_name: 'image',
                component: 'Image',
                icon: 'experience-editor-icon-image',
                selected: false,
                config: {
                    source: null,
                    width: 100,
                    height: 0,
                    name: null
                }
            },
            {
                component_name: 'video',
                component: 'Video',
                icon: 'experience-editor-icon-video',
                selected: false,
                config: {
                    source: '',
                    cover: 'https://s3.us-west-2.amazonaws.com/cdn.apithy.com/web/placeholder-play-video.jpg'
                    // width: 0,
                    // height: 0
                }
            },
            {
                component_name: 'text',
                component: 'Text',
                icon: 'experience-editor-icon-text',
                selected: false,
                config: {
                    content: '' // tiptap
                }
            },
            {
                component_name: 'title',
                component: 'Title',
                icon: 'fa-heading',
                selected: false,
                config: {
                    html: null,
                    json: null
                }
            },
            {
                component_name: 'downloadable',
                component: 'Downloadable',
                icon: 'fa-file-download',
                selected: false,
                config: {
                    title: '',
                    source: ''
                }
            },
            {
                component_name: 'externalResource',
                component: 'ExternalResource',
                icon: 'fa-file-code',
                selected: false,
                config: {
                    title: '',
                    source: ''
                }
            },
            {
                component_name: 'sendText',
                component: 'SendText',
                icon: 'fa-file-download',
                selected: false,
                config: {
                    activity_data: null,
                    session: null,
                    experience: null,
                    enrollment_progress: null,
                    activity_info: {
                        apithy_activity_metadata: {
                          id: 0,
                          cid: 0,
                          title: null,
                          name: null,
                          description: "",
                          is_mandatory: 0,
                          resolution_time: ""
                        }
                    }

                }
            },
            {
                component_name: 'sort_images',
                component: 'SortImages',
                type: 'sort',
                title: null,
                image: null,
                icon: 'experience-editor-icon-order',
                selected: false,
                not_evaluable: true,
                configurations: {
                    input_description: 'Add an element',
                    input_sentence: true,
                    input_sentence_value: null,
                    placeholder: `Write a text`,
                    add_question_shortcut: true,
                    title: 'settings',
                    description: 'Set the correct order',
                    default_answer: {
                        title: 'option',
                        image:null,
                        type:"select",
                        is_correct:false,
                        points: 0,
                        configurations:{
                            order: {
                                weight: 0
                            },
                            imageData: null,
                            imageText: null
                        }
                    },
                    shuffle_answers: true,
                    html: null,
                    json: null
                },
                answers: [],
            },
            {
                component_name: 'click_and_drop',
                component: 'ClickAndDrop',
                type: 'click_and_drop',
                title: null,
                image: null,
                icon: 'experience-editor-icon-order',
                difficulty: 'basic',
                selected: false,
                not_evaluable: true,
                configurations: {
                    input_description: 'Add an element',
                    input_sentence: true,
                    input_sentence_value: null,
                    placeholder: `Write a text`,
                    add_question_shortcut: true,
                    title: 'settings',
                    description: 'Set the correct order',
                    default_answer: {
                        title: 'option',
                        image:null,
                        type:"select",
                        is_correct:false,
                        points: 0,
                        configurations:{
                            related_options: []
                        }
                    },
                    shuffle_answers: true,
                    html: null,
                    json: null
                },
                answers: [],
            },
        ]
    },
    evaluable: {
        title: 'evaluable',
        selected: false,
        icon: 'experience-editor-icon-evaluable',
        difficulties: ['basic', 'intermediate', 'advanced'],
        evaluation: {
            types: ['diagnostic', 'activity', 'finally'],
            creation_types: ['dynamic', 'manual'],
            template: {
                bulk: 1,
                name: '',
                title: '',
                description: '',
                component: 'Evaluation',
                status: 0,
                type: '',
                difficulty: 'basic',
                creation_type: '',
                configurations: null,
                questions: [],
                experiences: null,
                experience_sessions: null
            },
        },
        list: [
            {
                component_name: 'true_and_false',
                component: 'TrueAndFalse',
                type: 'bool',
                title: null,
                image: null,
                icon: 'experience-editor-icon-binary',
                difficulty: 'basic',
                selected: false,
                configurations: {
                    input_sentence: true,
                    input_sentence_value: null,
                    placeholder: 'Write a question here',
                    add_question_shortcut: true,
                    title: 'settings',
                    description: 'Mark the correct answer',
                    html: null,
                    json: null
                },
                answers: [
                    {
                        title: 'true',
                        image: null,
                        type: 'checkbox',
                        value: true,
                        is_correct: false,
                        configurations: {}
                    },
                    {
                        title: 'false',
                        image: null,
                        type: 'checkbox',
                        value: false,
                        is_correct: false,
                        configurations: {}
                    },
                ]
            },
            {
                component_name: 'multiple_options',
                component: 'MultipleOptions',
                type: 'multiple',
                title: null,
                image: null,
                icon: 'experience-editor-icon-multiple',
                difficulty: 'basic',
                selected: false,
                configurations: {
                    input_sentence: true,
                    input_sentence_value: null,
                    placeholder: `Write a question here <br> - option`,
                    add_question_shortcut: true,
                    title: 'settings',
                    description: 'Mark the correct answers',
                    default_answer: {
                        title: 'option',
                        sequence: 0,
                        image:null,
                        type:"checkbox",
                        is_correct:false,
                        points:0,
                        configurations: {}
                    },
                    html: null,
                    json: null
                },
                answers: [],
            },
            /*{
                component_name: 'simple_question',
                component: 'single',
                type: 'single',
                title: null,
                image: null,
                icon: null,
                difficulty: 'basic',
                selected: false,
                configurations: {
                    input_sentence: true,
                    input_sentence_value: null,
                    placeholder: `Write a question here <br> - option`,
                    add_question_shortcut: true,
                    title: 'settings',
                    description: 'Mark the correct answers',
                    default_answers: [
                        {
                            title: 'option',
                            correct_answer: false
                        }
                    ],
                    html: null,
                    json: null
                },
                answers: [],
            },*/
            {
                component_name: 'sort',
                component: 'SortAnswers',
                type: 'sort',
                title: null,
                image: null,
                icon: 'experience-editor-icon-order',
                difficulty: 'basic',
                selected: false,
                configurations: {
                    input_description: 'Add an element',
                    input_sentence: true,
                    input_sentence_value: null,
                    placeholder: `Write a text`,
                    add_question_shortcut: true,
                    title: 'settings',
                    description: 'Set the correct order',
                    default_answer: {
                        title: 'option',
                        image:null,
                        type:"select",
                        is_correct:false,
                        points: 0,
                        configurations:{
                            order: {
                                weight: 0
                            }
                        }
                    },
                    shuffle_answers: true,
                    html: null,
                    json: null
                },
                answers: [],
            },
            {
                component_name: 'sort_images',
                component: 'SortImages',
                type: 'sort',
                title: null,
                image: null,
                icon: 'experience-editor-icon-order',
                difficulty: 'basic',
                selected: false,
                configurations: {
                    input_description: 'Add an element',
                    input_sentence: true,
                    input_sentence_value: null,
                    placeholder: `Write a text`,
                    add_question_shortcut: true,
                    title: 'settings',
                    description: 'Set the correct order',
                    default_answer: {
                        title: 'option',
                        image:null,
                        type:"select",
                        is_correct:false,
                        points: 0,
                        configurations:{
                            order: {
                                weight: 0
                            },
                            imageData: null,
                            imageText: null
                        }
                    },
                    shuffle_answers: true,
                    html: null,
                    json: null
                },
                answers: [],
            },
            {
                component_name: 'click_and_drop',
                component: 'ClickAndDrop',
                type: 'click_and_drop',
                title: null,
                image: null,
                icon: 'experience-editor-icon-order',
                difficulty: 'basic',
                selected: false,
                configurations: {
                    input_description: 'Add an element',
                    input_sentence: true,
                    input_sentence_value: null,
                    placeholder: `Write a text`,
                    add_question_shortcut: true,
                    title: 'settings',
                    description: 'Set the correct order',
                    default_answer: {
                        title: 'option',
                        image:null,
                        type:"select",
                        is_correct:false,
                        points: 0,
                        configurations:{
                            related_options: []
                        }
                    },
                    shuffle_answers: true,
                    html: null,
                    json: null
                },
                answers: [],
            },
            /*{
                component_name: 'fill_the_blanks',
                component: 'FillingBlanks',
                type: 'filling',
                title: null,
                image: null,
                icon: 'experience-editor-icon-fill-blanks',
                difficulty: 'basic',
                selected: false,
                configurations: {
                    input_sentence: true,
                    input_sentence_value: null,
                    placeholder: 'wrong_answer_filling',
                    add_question_shortcut: true,
                    title: 'settings',
                    description: 'Mark the correct answers',
                    default_answers: {
                        title:null,
                        image:null,
                        type:'checkbox',
                        is_correct:true,
                        points:0,
                        configurations:{
                            filling:{
                                text:null,
                                weight:null
                            }
                        }
                    },
                    html: '',
                    json: null
                },
                answers: [],
            }*/
        ]
    },
};
