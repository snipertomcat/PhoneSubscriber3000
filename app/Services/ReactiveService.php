<?php

namespace Apithy\Services;

use Apithy\Answer;
use Apithy\Enrollment;
use Apithy\EnrollmentProgress;
use Apithy\Evaluation;
use Apithy\EvaluationUser;
use Apithy\ExperienceActivity;
use Apithy\Http\Resources\Reactives\AnswerResource;
use Apithy\Http\Resources\Reactives\EvaluationResource;
use Apithy\Http\Resources\Reactives\QuestionResource;
use Apithy\Http\Resources\Reactives\UserEvaluationResource;
use Apithy\Question;
use Apithy\Services\Eva\BooleanReactive;
use Apithy\Services\Eva\ClickAndDropReactive;
use Apithy\Services\Eva\DefaultEvaluationMethod;
use Apithy\Services\Eva\FillingTheBlankReactive;
use Apithy\Services\Eva\MultipleReactive;
use Apithy\Services\Eva\SingleReactive;
use Apithy\Services\Eva\SortReactive;
use Apithy\Session;
use Apithy\User;
use Apithy\Utilities\Master\Master;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReactiveService
{
    private $request;
    private $taxonomyService;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->taxonomyService = new TaxonomyService($request);
    }

    // Questions

    public function getQuestions()
    {
        $questions = Question::when($this->request->filled('search'), function ($query) {
            return $query->search($this->request->input('search'));
        });
        return QuestionResource::collection($questions->paginate($this->request->input('per_page', 15)));
    }

    public function showQuestion($id)
    {
        try {
            $question = Question::with(['evaluations'])
                ->findOrFail($id);
            return QuestionResource::make($question);
        } catch (\Exception $ex) {
            if (Master::hasDebug()) {
                return Master::exceptionResponse($ex, 'show_question');
            }
        }
        return Master::errorResponse('show_question', 'not_found', 'not_found', 404);
    }

    public function storeQuestion()
    {
        try {
            $question = null;
            \DB::transaction(function () use (&$question) {
                $question = new Question();
                $question->fill($this->request->all());
                $question->company_id = $this->getCompanyId();
                $question->saveOrFail();

                if ($this->request->filled('evaluations')) {
                    //$question->evaluations()->attach($this->request->input('evaluations.*.id'));
                    $question->evaluations()->attach($this->request->input('evaluations'));
                }

                if ($this->request->filled('answers')) {
                    $answers = $this->setTimeStamps($this->request->input('answers'));
                    $question->answers()->createMany($answers);
                }
            });
            return QuestionResource::make($question->load(['evaluations']));
        } catch (\Exception $ex) {
            if (Master::hasDebug()) {
                return Master::exceptionResponse($ex, 'store_question');
            }
        }
        return Master::errorResponse('store_question');
    }

    public function updatedQuestion($id)
    {
        try {
            \DB::transaction(function () use (&$question, $id) {
                $question = Question::findOrFail($id);
                $question->fill($this->request->all());
                $question->saveOrFail();

                $question->evaluations()->sync($this->request->input('evaluations', []));

                if ($this->request->filled('answers')) {
                    $answers = collect($this->request->input('answers'));
                    $answers->each(function ($answer, $key) use (&$question) {
                        $myAnswer = Answer::findOrFail($answer['id']);
                        $myAnswer->fill($answer);
                        $myAnswer->saveOrFail();
                        $myAnswer = null;
                    });
                }
            });
            return QuestionResource::make($question->load(['evaluations']));
        } catch (\Exception $ex) {
            if (Master::hasDebug()) {
                return Master::exceptionResponse($ex, 'update_question');
            }
        }
        return Master::errorResponse('update_question', 'update');
    }

    // ANSWERS

    public function getAnswers()
    {
        $answers = Answer::where('question_id', $this->request->input('question_id'))
            ->when($this->request->filled('search'), function ($query) {
                return $query->search($this->request->input('search'));
            })
            ->paginate($this->request->input('per_page', 15));
        return AnswerResource::collection($answers);
    }

    public function showAnswer($id)
    {
        try {
            $answer = Answer::findOrFail($id);
            return AnswerResource::make($answer);
        } catch (\Exception $ex) {
            if (Master::hasDebug()) {
                return Master::exceptionResponse($ex, 'show_answer');
            }
        }
        return Master::errorResponse('answer_show', 'not_found', 'not_found', 404);
    }

    public function storeAnswer()
    {
        try {
            $answer = new Answer();
            $answer->fill($this->request->all());
            $answer->saveOrFail();
            return AnswerResource::make($answer);
        } catch (\Exception $ex) {
            if (Master::hasDebug()) {
                return Master::exceptionResponse($ex, 'store_answer');
            }
        }
        return Master::errorResponse('store_answer');
    }

    public function updateAnswer($id)
    {
        try {
            $answer = Answer::where([
                ['id', $id],
                ['question_id', $this->request->input('question_id')]
            ])
                ->firstOrFail();
            $answer->fill($this->request->all());
            $answer->saveOrFail();
            return AnswerResource::make($answer);
        } catch (\Exception $ex) {
            if (Master::hasDebug()) {
                return Master::exceptionResponse($ex, 'update_answer');
            }
        }
        return Master::errorResponse('update_answer', 'update');
    }

    public function destroyAnswer($id)
    {
        try {
            $answer = Answer::where([
                ['id', $id],
                ['question_id', $this->request->input('question_id')]
            ])
                ->firstOrFail();
            $answer->delete();
            return Master::successResponse();
        } catch (\Exception $ex) {
            if (Master::hasDebug()) {
                return Master::exceptionResponse($ex, 'delete_answer');
            }
        }
        return Master::errorResponse('delete_answer', 'delete');
    }

    // EVALUATIONS

    public function getEvaluations()
    {
        $evaluations = Evaluation::with(['questions', 'questions.answers'])
            ->when($this->request->filled('search'), function ($query) {
                return $query->search($this->request->input('search'));
            })
            ->when($this->request->filled('experience_id'), function ($query) {
                return $query->WhereHas('experiences', function ($q) {
                    $q->where('id', $this->request->input('experience_id'));
                });
            })
            ->when($this->request->filled('session_id'), function ($query) {
                return $query->WhereHas('experienceSessions', function ($q) {
                    $q->where('id', $this->request->input('session_id'));
                });
            })
            ->paginate($this->request->input('per_page'));
        return EvaluationResource::collection($evaluations);
    }

    public function showEvaluation($id)
    {
        try {
            $evaluation = Evaluation::with(['questions', 'questions.answers'])
                ->findOrFail($id);
            return EvaluationResource::make($evaluation);
        } catch (\Exception $ex) {
            if (Master::hasDebug()) {
                return Master::exceptionResponse($ex, 'show_evaluation');
            }
        }
        return Master::errorResponse('show_evaluation', 'not_found', 'not_found', 404);
    }

    public function storeEvaluation()
    {
        try {
            \DB::transaction(function () use (&$evaluation) {
                $evaluation = new Evaluation();
                $evaluation->fill($this->request->all());
                $evaluation->company_id = $this->getCompanyId();
                $evaluation->user_id = $this->request->user()->id;
                $evaluation->saveOrFail();

                // Retrocompatibilidad
                $this->saveEvaluationAsExperienceActivity($evaluation);

                $this->saveEvaluationQuestions($evaluation);
                $this->saveEvaluationExperience($evaluation);
                $this->saveEvaluationExperienceSessions($evaluation);
            });
            return EvaluationResource::make($evaluation->load('questions'));
        } catch (\Exception $ex) {
            if (Master::hasDebug()) {
                return Master::exceptionResponse($ex, 'store_evaluation');
            }
        }
        return Master::errorResponse('store_evaluation');
    }

    /**
     * @param array $data
     * @param int $companyId
     * @param int $userId
     * @return Evaluation
     * @throws \Throwable
     */
    private function saveEvaluation(array $data, int $companyId, int $userId)
    {
        $evaluation = new Evaluation();
        $evaluation->fill($data);
        $evaluation->forceFill([
            'company_id' => $companyId,
            'user_id' => $userId
        ]);
        $evaluation->saveOrFail();
        return $evaluation;
    }

    /**
     * @param array $data
     * @param int $companyId
     * @return Question
     * @throws \Throwable
     */
    private function saveQuestion(array $data, int $companyId)
    {
        $question = new Question();
        $question->fill($data);
        $question->forceFill(['company_id' => $companyId]);
        $question->saveOrFail();
        return $question;
    }

    /**
     * @param array $questions
     * @param int $companyId
     * @param bool $withAnswers
     * @return array
     */
    public function saveBulkQuestions(array $questions, int $companyId, bool $withAnswers = false)
    {
        $questions = collect($questions);
        $questionIds = collect([]);
        $questions->each(function ($item, $key) use (&$questionIds, $companyId, $withAnswers) {
            $item = collect($item);
            $question = $this->saveQuestion(
                $item->only(['title', 'image', 'type', 'difficulty', 'configurations'])->toArray(),
                $companyId
            );
            if ($withAnswers) {
                $answers = $this->setTimeStamps($item->get('answers'));
                $question->answers()->createMany($answers);
            }
            $questionIds->push($question->id);
            $question = null;
        });
        return $questionIds->toArray();
    }

    public function bulkStoreEvaluation()
    {
        try {
            \DB::transaction(function () use (&$evaluation) {
                $companyId = $this->getCompanyId();
                $evaluation = $this->saveEvaluation(
                    $this->request->only([
                        'name',
                        'title',
                        'description',
                        'type',
                        'difficulty',
                        'creation_type',
                        'status'
                    ]),
                    $companyId,
                    $this->request->user()->id
                );
                $questionIds = $this->saveBulkQuestions(
                    $this->request->input('questions'),
                    $companyId,
                    true
                );

                // Retrocompatibilidad
                $this->saveEvaluationAsExperienceActivity($evaluation);

                // Vincular pregunta con evaluacion
                $evaluation->questions()->attach($questionIds);
                // Vincular evaluacion con experiencia
                $this->saveEvaluationExperience($evaluation);
                // Vincular evaluacion con actividad
                $this->saveEvaluationExperienceSessions($evaluation);

                $experienceService = new ExperiencesService();
                $session_ids = $this->request->input('experience_sessions');

                /*
                    foreach ($session_ids as $session_id) {
                        $session= Session::find($session_id);
                        $experienceService->handleNewEvaluations($evaluation, $session->experience, $session);
                    }
                */
            });
            return EvaluationResource::make($evaluation->load('questions'));
        } catch (\Exception $ex) {
            if (Master::hasDebug()) {
                return Master::exceptionResponse($ex, 'bulk_store_evaluation');
            }
        }
        return Master::errorResponse('bulk_store_evaluation');
    }

    public function saveEvaluationQuestions(Evaluation $evaluation)
    {
        if (!$this->request->filled('questions')) {
            return;
        }
        if ($this->isRequestMethod('post')) {
            $questions = $this->setTimeStamps($this->request->input('questions'));
            $evaluation->questions()->attach($questions);
            return;
        }
        if ($this->isRequestMethod('patch')) {
            $evaluation->questions()->sync($this->request->input('questions.*.question_id'));
            return;
        }
        return;
    }

    public function saveEvaluationExperience(Evaluation $evaluation)
    {
        if (!$this->request->filled('experiences')) {
            return;
        }
        if ($this->isRequestMethod('post')) {
            $evaluation->experiences()->attach($this->request->input('experiences'));
            return;
        }
        if ($this->isRequestMethod('patch')) {
            $evaluation->experiences()->sync($this->request->input('experiences'));
            return;
        }
        return;
    }

    public function saveEvaluationExperienceSessions(Evaluation $evaluation)
    {
        if (!$this->request->filled('experience_sessions')) {
            return;
        }
        if ($this->request->input('type') === 'finally') {
            return;
        }
        if ($this->isRequestMethod('post')) {
            $evaluation->experienceSessions()->attach($this->request->input('experience_sessions'));
            return;
        }
        if ($this->isRequestMethod('patch')) {
            $evaluation->experienceSessions()->sync($this->request->input('experience_sessions'));
            return;
        }
        return;
    }

    private function isRequestMethod($method)
    {
        $method = strtolower($method);
        $requestMethod = strtolower($this->request->getMethod());
        return $method === $requestMethod;
    }

    public function updateEvaluation($id)
    {
        try {
            \DB::transaction(function () use (&$evaluation, $id) {
                $evaluation = Evaluation::findOrFail($id);
                $evaluation->fill($this->request->all());
                $evaluation->saveOrFail();

                $this->saveEvaluationAsExperienceActivity($evaluation, true);

                $this->saveEvaluationQuestions($evaluation);
                $this->saveEvaluationExperience($evaluation);
                $this->saveEvaluationExperienceSessions($evaluation);
            });
            return EvaluationResource::make($evaluation->load('questions'));
        } catch (\Exception $ex) {
            if (Master::hasDebug()) {
                return Master::exceptionResponse($ex, 'update_evaluation');
            }
        }
        return Master::errorResponse('update_evaluation', 'update');
    }

    // Evaluations - Users

    public function getEvaluationUsers()
    {
        $evaluationUser = EvaluationUser::when($this->request->filled('evaluation_id'), function ($query) {
            return $query->where($this->request->only('evaluation_id'));
        })
            ->when($this->request->filled('user_id'), function ($query) {
                return $query->where($this->request->only('user_id'));
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->request->input('per_page', 15));
        $aux = collect();
        $evaluationUser->each(function ($evaUser, $key) use ($aux) {
            $aux->push($this->showEvaluationUser($evaUser->id, false));
        });
        $ptm = collect($evaluationUser->toArray())->except(['data'])->all();
        return UserEvaluationResource::collection($aux)->additional($ptm);
    }

    public function showEvaluationUser($id, $externalCall = true)
    {
        try {
            $evaluationUser = EvaluationUser::with([
                'evaluation.questions',
                'evaluation.questions.answers.evaluationAnswerUser' => function ($query) use ($id) {
                    return $query->FilterByEvaId($id);
                }
            ])
                ->findOrFail($id);
            if ($externalCall) {
                return UserEvaluationResource::make($evaluationUser);
            }
            return $evaluationUser;
        } catch (\Exception $ex) {
            if (Master::hasDebug()) {
                return Master::exceptionResponse($ex, 'show_evaluation_user');
            }
        }
        return Master::errorResponse('show_evaluation_user');
    }

    // For TEST

    public function getEvaluationById(int $evaluationId, bool $withQuestionCount = false) : Evaluation
    {
        return Evaluation::when($withQuestionCount, function ($query) {
            return $query->withCount('questions');
        })
            ->where('id', $evaluationId)
            ->firstOrFail();
    }

    public function getAnswersById(array $answerId) : Collection
    {
        return Answer::whereIN('id', $answerId)
            ->get();
    }

    public function refactorUserAnswers(Collection $answers, array $requestAnswers) : array
    {
        $userAnswer = [];
        foreach ($answers as $answer) {
            foreach ($requestAnswers as $k => $rAnswer) {
                if ($answer->id == $rAnswer['answer_id']) {
                    $userAnswer[] = [
                        'answer_id' => $answer->id,
                        'configurations' => $rAnswer['configurations'] ?? $answer->configurations,
                        'points' => $answer->points,
                        'is_correct' => false
                    ];
                }
            }
        }
        return $userAnswer;
    }

    public function routeTest(Collection $questions, array &$userAnswers) : void
    {
        foreach ($questions as $question) {
            if (isset($this->getTestType()[$question->type])) {
                $question->valid = false;
                $class = $this->getTestType()[$question->type];
                $class->checkAnswers($question, $userAnswers);
                $class = null;
            }
        }
    }

    public function getTestType() : array
    {
        return [
            'bool' => new BooleanReactive(),
            'single' => new SingleReactive(),
            'multiple' => new MultipleReactive(),
            'sort' => new SortReactive(),
            'filling' => new FillingTheBlankReactive(),
            'click_and_drop' => new ClickAndDropReactive()
        ];
    }

    public function byEvaluationMethod(Evaluation $evaluation, array $userAnswers) : array
    {
        $dem = new DefaultEvaluationMethod();
        return $dem->evaluate($evaluation, $userAnswers);
    }

    public function storeUserAnswers()
    {
        try {
            \DB::transaction(function () use (&$evaluated) {
                $user = $this->request->user();
                $answersArray = collect($this->request->input('answers'));
                $answers = $this->getAnswersById($answersArray->pluck('answer_id')->toArray());
                $userAnswers = $this->refactorUserAnswers($answers, $answersArray->values()->toArray());
                $evaluation = $this->getEvaluationById($this->request->input('evaluation'), true);
                $this->routeTest($evaluation->questions, $userAnswers);
                $evaluated = $this->byEvaluationMethod($evaluation, $userAnswers);

                $date = now()->toDateTimeString();
                $extra = [
                    'user_id'       => $user->id,
                    'evaluation_id' => $evaluation->id,
                    'started_at'    => $this->request->input('started_at', $date),
                    'finished_at'   => $date
                ];
                $evaluationUser = new EvaluationUser();
                $evaluationUser->fill(array_merge($evaluated, $extra));
                $evaluationUser->saveOrFail();
                $evaluationUser->evaluationAnswerUser()->createMany($userAnswers);

                // Posibles problemas de compatibilidad, desactivar si se quiere verificar que se guardan datos.
                $this->saveEnrollmentScore(
                    $this->request->input('enrollment_id'),
                    $this->request->input('experience_id'),
                    $this->request->user()->id
                );

                $activity = ExperienceActivity::where('evaluation_id', $evaluation->id)
                    ->first();

                if (isset($activity)) {
                    $this->saveExperienceEnrollmentProgress($user, $evaluated, $activity->id);
                }
            });
            return Master::successResponse($evaluated);
        } catch (\Exception $ex) {
            report($ex);
            if (Master::hasDebug()) {
                return Master::exceptionResponse($ex, 'store_user_answers');
            }
        }
        return Master::errorResponse('store_user_answers');
    }

    // FUNCTIONS

    public function setTimeStamps($data)
    {
        $today = now()->toDayDateTimeString();
        return collect($data)->map(function ($item, $key) use ($today) {
            $times = [
                'created_at' => $today,
                'updated_at' => $today
            ];
            return array_merge($item, $times);
        })
            ->toArray();
    }

    public function getCompanyId()
    {
        return $this->request->user()->company->first()->id;
    }

    // Backward compatibility
    // Experience Activity

    public function saveExperienceEnrollmentProgress(User $user, array $data, int $activityId) : void
    {
        $enrollment = Enrollment::where([
            ['id', $this->request->input('enrollment_id')],
            ['experience_id', $this->request->input('experience_id')],
            ['user_id', $user->id],
            //['status', '<>', Enrollment::ENROLLMENT_STATUS_FINISHED]
        ])
            ->firstOrFail();

        $enrollmentProgress = EnrollmentProgress::where([
            ['enrollment_id', $enrollment->id],
            ['session_id', $this->request->input('session_id')]
        ])->first();

        $session = Session::where('id', $this->request->input('session_id'))->first();

        $sessionActivitiesCount = $session->activities->count();

        //$startedAt = $this->request->input('started_at', now()->toDateTimeString());
        $startedAt = $this->request->input('started_at');
        if (isset($startedAt)) {
            $startedAt = Carbon::createFromTimestamp($startedAt)->toDateTimeString();
        } else {
            $startedAt = now()->toDateTimeString();
        }
        //$finishedAt = $this->request->input('finished_at', now()->toDateTimeString());
        $finishedAt = $this->request->input('finished_at');
        if (isset($finishedAt)) {
            $finishedAt = Carbon::createFromTimestamp($finishedAt)->toDateTimeString();
        } else {
            $finishedAt = now()->toDateTimeString();
        }

        if (!isset($enrollmentProgress)) {
            $enrollmentProgress = new EnrollmentProgress();
            $enrollmentProgress->forceFill([
                'enrollment_id' => $enrollment->id,
                'session_id'    => $this->request->input('session_id'),
                'status'        => EnrollmentProgress::SESSION_STATUS_FINISHED,
                'success'       => $data['success'],
                'score'         => $data['score'],
                'started_at'    => $startedAt,
                'finished_at'   => $finishedAt
            ]);
            $enrollmentProgress->started_at = $startedAt;
            $enrollmentProgress->saveOrFail();
        }

        $enrollmentProgress->score=$data['score'];
        $enrollmentProgress->success=$data['success'];
        $enrollmentProgress->save();

        if ($enrollmentProgress->activities->isEmpty()) {
            $this->saveExperienceEnrollmentActivities($enrollmentProgress, $session);
        }

        $data['started_at'] = $startedAt;
        $data['finished_at'] = $finishedAt;
        $enrollmentProgress->activities()
            ->updateExistingPivot($activityId, $data);

        unset($enrollmentProgress->activities);

        if ($sessionActivitiesCount === $enrollmentProgress->activities->count()) {
            $this->setProgressScore($enrollmentProgress);
        }
    }

    private function saveExperienceEnrollmentActivities(EnrollmentProgress &$enrollmentProgress, Session $session): void
    {
        $activities = $session->activities;
        $data = [];
        $startedAt = $this->request->input('started_at');
        if (isset($startedAt)) {
            $startedAt = Carbon::createFromTimestamp($startedAt)->toDateTimeString();
        } else {
            $startedAt = now()->toDateTimeString();
        }
        $finishedAt = $this->request->input('finished_at');
        if (isset($finishedAt)) {
            $finishedAt = Carbon::createFromTimestamp($finishedAt)->toDateTimeString();
        } else {
            $finishedAt = now()->toDateTimeString();
        }
        foreach ($activities as $activity) {
            $data[] = [
                'activity_id'               => $activity->id,
                'enrollment_progress_id'    => $enrollmentProgress->id,
                'created_at'                => $startedAt,
                'updated_at'                => $finishedAt
            ];
        }
        $enrollmentProgress->activities()->attach($data);
    }

    private function setProgressScore(EnrollmentProgress &$enrollmentProgress): void
    {
        $score = 0;
        $count = 0;
        $total = 0;
        foreach ($enrollmentProgress->activities as $activity) {
            if (isset($activity->pivot->score)) { // only count evaluations with score defined
                $count ++;
                $score += $activity->pivot->score;
            }
        }

        if ($count > 0) {
            $total = $score / $count;
        }

        $startedAt = $this->request->input('started_at');
        if (isset($startedAt)) {
            $startedAt = Carbon::createFromTimestamp($startedAt)->toDateTimeString();
        } else {
            $startedAt = now()->toDateTimeString();
        }

        $finishedAt = $this->request->input('finished_at');
        if (isset($finishedAt)) {
            $finishedAt = Carbon::createFromTimestamp($finishedAt)->toDateTimeString();
        } else {
            $finishedAt = now()->toDateTimeString();
        }

        $enrollmentProgress->forceFill([
            'score'         => $total,
            'success'       => intval($total >= 0.6),
            'started_at'    => $startedAt,
            'finished_at'   => $finishedAt
        ]);
        $enrollmentProgress->saveOrFail();
    }
/*
    public function saveScoreToEvaluationExperienceEnrollment() : void
    {
        $this->request->request->add(['user' => $this->request->user()->id]);
        $scoreService = new ScoreService($this->request);
        $scoreService->setExperienceScores(
            $this->request->input('experience_id'),
            $this->request->input('enrollment_id')
        );
    }
*/
    public function saveEvaluationAsExperienceActivity(Evaluation $evaluation, bool $update = false): ExperienceActivity
    {
        if ($update) {
            $ea = ExperienceActivity::where('evaluation_id', $evaluation->id)
                ->firstOrFail();
        } else {
            $ea = new ExperienceActivity();
        }
        $ea->fill([
            'name'              => $evaluation->name,
            'title'             => $evaluation->title,
            'description'       => $evaluation->description,
            'type'              => $evaluation->type,
            'url'               => '',
            'is_iframe'         => 0,
            'is_scoreable'      => 1,
            'resolution_time'   => 0,
            'evaluation_id'     =>  $evaluation->id
        ]);
        $ea->saveOrFail();
        $this->saveExperienceSessionActivities($ea, $update);
        return $ea;
    }

    public function saveExperienceSessionActivities(ExperienceActivity $activity, bool $update = false) : void
    {
        // $ids = collect($this->request->input('experience_sessions'))->except('weight')->keys();
        if ($update) {
            $activity->sessions()->sync($this->request->input('experience_sessions'));
        } else {
            $activity->sessions()->attach($this->request->input('experience_sessions'));
        }
    }

    public function saveEnrollmentScore(int $enrollmentId = null, int $experienceId = null, int $userId = null) : void
    {
        try {
            $enrollment = Enrollment::where([
                ['id', $enrollmentId],
                ['experience_id', $experienceId],
                ['user_id', $userId]
            ])
                ->firstOrFail();
            $sessions = $enrollment->experience->sessions()->where('parent_id', null)->get();
            $count = 0;
            $eva = collect();
            $sessions->each(function ($session, $key) use (&$count, &$eva, $userId) {
                $count += $session->evaluations->count();
                $session->evaluations->each(function ($evaluation) use (&$eva, $userId) {
                    $eu = EvaluationUser::where([
                        ['user_id', $userId],
                        ['evaluation_id', $evaluation->id]
                    ])
                        ->orderBy('created_at', 'desc')
                        ->first();
                    if (isset($eu)) {
                        $eva->push($eu);
                    }
                });

                if (isset($session->childs[0])) {
                    foreach ($session->childs as $index => $child) {
                        $count += $child->evaluations->count();
                        $child->evaluations->each(function ($evaluation) use (&$eva, $userId) {
                            $eu = EvaluationUser::where([
                                ['user_id', $userId],
                                ['evaluation_id', $evaluation->id]
                            ])
                                ->orderBy('created_at', 'desc')
                                ->first();
                            if (isset($eu)) {
                                $eva->push($eu);
                            }
                        });
                    }
                }
            });

            if ($eva->count() === $count) {
                $score = $eva->sum('score');
                $enrollment->score = (($count > 0) ? $score / $count : 0);
                $enrollment->success = ($score >= 0.6);
                $enrollment->saveOrFail();
            }
        } catch (\Exception $ex) {
            //
        }
    }
}
