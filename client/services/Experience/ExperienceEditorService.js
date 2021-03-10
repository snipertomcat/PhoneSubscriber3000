const EditorService = {
  async createSession (experience_id, form) {
    let url = route('sessions.store', {experience:experience_id});
    let el = await axios.post(url, form)
    return el;
  },
  async updateSession (session) {
    let url = route('sessions.update', {experience: session.experience_id, session: session.system_id});
    let el = await axios.patch(url, session);
    return el;
  },
  createEvaluation (form) {
    form.status = 1;
    let url = '/evaluation';
    return axios.post(url, form)
  },
  updateEvaluation (evaluation_id, form) {
    let url = `/evaluation/${evaluation_id}`;
    let newData = {
      questions: form.questions,
      title: form.title
    }
    return axios.patch(url, newData)
  },
  addQuestion (evaluation_id, form) {
    let url = '/question';
    form.evaluations = [evaluation_id];
    return axios.post(url, form)
  },
  removeQuestion (evaluation_id, form) {
    _.remove(form.evaluations, i => { return i === evaluation_id })
    let url = `/question/${form.id}`;
    return axios.patch(url, form);
  },
  updateQuestion (evaluation_id, question_id, form, withEvaluationField = false) {
    let url = `/question/${question_id}`;
    if (withEvaluationField)
      form.evaluations = [evaluation_id];
    return axios.patch(url, form);
  },
  createActivity(form) {
    let url = `/sessions/register-activity`;
    return axios.post(url, form)
  },
  async destroyTipTapConstructor(content, hasEnrollments, experience_id) {
    console.clear()
    let evaluations = [];
    //for (let session of content)
    for (let c_i = 0; c_i < _.size(content); c_i++) {
      let c = content[c_i];
      if (c.type === 'content') {
          let session = c;
          session.weight = c_i;
          //console.log(session)
          //for (let element of session.elements)
          for (let i1 = 0; i1 < _.size(session.elements); i1++) {
            //console.log('i1: ',i1)
            let element = session.elements[i1];
            if (element.component_name === 'text' || element.component_name === 'title') {
              // Remove tiptap instance if exist
              element.config.editor = null;
            }
            if (element.component_name === 'sendText') {
              // create activity
              if (_.isNull(element.config.activity_data)) {
                let activity = await EditorService.createActivity(element.config)
                element.config.activity_data = activity.data;
              }
            }
            if (element.component_name === 'evaluation' || element.type === 'activity') {
              // Remove tiptap instance if exist
              try {
                _.each(element.questions, question => {
                  question.configurations.editor = null
                  _.each(question.answers, answer => {
                    answer.configurations.editor = null
                  })
                });
              } catch (e) {
                console.log(e)
              }

              if (!hasEnrollments) {
                // Update evaluation questions
                let is_updating = route().current() === 'experiences.edit.editor';
                if (is_updating && _.has(element, 'id')) {
                  let oElement = element;
                  for (let i = 0; i < _.size(element.questions); i++) {
                    let question = element.questions[i];
                    if (!_.has(question, 'id')) {
                      // Add new question
                      let aux = await EditorService.addQuestion(element.id, question);
                      question.id = aux.data.data.id;
                      question.answers = aux.data.data.answers;
                    } else {
                      // Update question
                      let aux = await EditorService.updateQuestion(element.id, question.id, question, true);
                      question.answers = aux.data.data.answers;
                    }
                  }
                  element = EditorService.insertIds(oElement, element)
                }

                // Remove evaluation questions
                let has_deleted_questions = _.size(element.deleted_questions) > 0;
                if (has_deleted_questions) {
                  for (let i = 0; i < _.size(element.deleted_questions); i++) {
                    let dQuestion = element.deleted_questions[i];
                    let aux = await EditorService.removeQuestion(element.id, dQuestion);
                    let updatedQuestion = _.get(aux, ['data', 'data'], null);
                    if (!_.isNull(updatedQuestion) && _.findIndex(updatedQuestion.evaluations, dQuestion.id) > 0) {
                      _.delete(element.deleted_questions, id => { return id === dQuestion.id });
                    }
                  }
                }

                // Add evaluation questions
                let created = ((_.has(element, 'id') && _.has(element, 'evaluationCreated')) && element.evaluationCreated === true);
                if (!created) {
                  try {
                    let aux = await EditorService.createEvaluation(element).catch(error => {})
                    element = EditorService.insertIds(element, aux.data.data)
                  } catch (e) {

                  }
                }
              } else {
                // Limited update questions
                for (let qI = 0; qI < _.size(element.questions); qI++) {
                  //console.log('qI: ',qI)
                  let question = element.questions[qI];
                  if (_.has(question, 'id')) {
                    let form = {
                      title: question.title,
                      type: question.type,
                      difficulty: question.difficulty,
                      json_data: question.json_data,
                      evaluations: question.evaluations
                    };
                    let aux = await EditorService.updateQuestion(element.id, question.id, form, true);
                  }
                }
              }
            }
          }
      }
      else {
        c.weight = c_i;
        //for (let session of c.elements) {
        for (let sch_i = 0; sch_i < _.size(c.elements); sch_i++) {
          let session = c.elements[sch_i];
          session.weight = sch_i;
          for (let element of session.elements) {
            if (element.component_name === 'text' || element.component_name === 'title') {
              // Remove tiptap instance if exist
              element.config.editor = null;
            }
            if (element.component_name === 'sendText') {
              // create activity
              if (_.isNull(element.config.activity_data)) {
                let activity = await EditorService.createActivity(element.config)
                element.config.activity_data = activity.data;
              }
            }
            if (element.component_name === 'evaluation' || element.type === 'activity') {
              // Remove tiptap instance if exist
              _.each(element.questions, question => {
                question.configurations.editor = null
                _.each(question.answers, answer => {
                  answer.configurations.editor = null
                })
              })

              if (!hasEnrollments) {
                //console.log('no enroll')
                // Update evaluation questions
                let is_updating = route().current() === 'experiences.edit.editor';
                if (is_updating && _.has(element, 'id')) {
                  let oElement = element;
                  for (let i = 0; i < _.size(element.questions); i++) {
                    let question = element.questions[i];
                    if (!_.has(question, 'id')) {
                      // Add new question
                      let aux = await EditorService.addQuestion(element.id, question);
                      question.id = aux.data.data.id;
                      question.answers = aux.data.data.answers;
                    } else {
                      // Update question
                      let aux = await EditorService.updateQuestion(element.id, question.id, question, true);
                      question.answers = aux.data.data.answers;
                    }
                  }
                  element = EditorService.insertIds(oElement, element)
                }

                // Remove evaluation questions
                let has_deleted_questions = _.size(element.deleted_questions) > 0;
                if (has_deleted_questions) {
                  for (let i = 0; i < _.size(element.deleted_questions); i++) {
                    let dQuestion = element.deleted_questions[i];
                    //console.log(dQuestion)
                    let aux = await EditorService.removeQuestion(element.id, dQuestion);
                    let updatedQuestion = _.get(aux, ['data', 'data'], null);
                    if (!_.isNull(updatedQuestion) && _.findIndex(updatedQuestion.evaluations, dQuestion.id) > 0) {
                      _.delete(element.deleted_questions, id => { return id === dQuestion.id });
                    }
                  }
                }

                // Add evaluation questions
                let created = ((_.has(element, 'id') && _.has(element, 'evaluationCreated')) && element.evaluationCreated === true);
                //console.log('=>', created)
                if (!created) {
                  let timestamp = moment().format("DDMMYYYYhhmmss");
                  element.name = `evaluation_${timestamp}_exp_${experience_id}_session_${session.session_id}`;
                  let evaluation = await EditorService.createEvaluation(element).catch(error => {})
                    evaluations.push(evaluation.data.data.id)
                    element = EditorService.insertIds(element, evaluation.data.data)
                }
              } else {
                // Limited update questions
                for (let qI = 0; qI < _.size(element.questions); qI++) {
                  let question = element.questions[qI];
                  if (_.has(question, 'id')) {
                    let form = {
                      title: question.title,
                      type: question.type,
                      difficulty: question.difficulty,
                      json_data: question.json_data
                    };
                    let aux = await EditorService.updateQuestion(element.id, question.id, form);
                  }
                }
              }
              /*let created = ((_.has(element, 'id') && _.has(element, 'evaluationCreated')) && element.evaluationCreated === true);
              if(!created) {
                try {
                  let aux = await EditorService.createEvaluation(element).catch(error => {})
                  evaluations.push(aux.data.data.id)
                  element = EditorService.insertIds(element, aux.data.data)
                }
                catch (e) {

                }
              }*/
            }
          }
          //console.log(session)
          let result = await EditorService.updateSession({
            id: session.session_id,
            system_id: session.system_id,
            weight: session.weight,
            parent_id: session.session_parent_id,
            status: session.status,
            title: session.title,
            experience_id: experience_id,
            summary: session.title,
            visibility: 1,
          })
        }
        //console.log(c)
      }
    }
    return {content: content, evaluations: evaluations};
  },
  insertIds (element, evaluation) {
    element.id = _.get(evaluation, ['id'], 0);

    _.each(element.questions, (question, qIndex) => {
      question.id = _.get(evaluation, ['questions', qIndex, 'id'], 0);
      _.each(question.answers, (answer, aIndex) => {
        answer.id = _.get(evaluation, ['questions', qIndex, 'answers', aIndex, 'id'], 0)
      })
    });
    element.evaluationCreated = true;
    return element;
  },
}

export { EditorService }
