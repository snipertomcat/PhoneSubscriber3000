<?php

namespace Apithy\Services;

use Apithy\Company;
use Apithy\Enrollment;
use Apithy\EnrollmentProgress;
use Apithy\EnrollmentTracking;
use Apithy\Experience;
use Apithy\ExperienceAssignation;
use Apithy\ExperienceLicence;
use Apithy\Invitation;
use Apithy\Session;
use Apithy\User;
use Apithy\Utilities\Master\Master;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StadisticService
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getStats()
    {
        $data = [];

        $data['companies'] = $this->getCompaniesStats();
        $data['users'] = $this->getUsersStats();
        $data['invitations'] = $this->getInvitationsStats();
        $data['experiences'] = $this->getExperiencesStats();

        return $data;
    }

    public function getCompaniesStats()
    {
        $company_ids = [];

        $raw = DB::select(
            'select company_id,count(company_id) as users from company_user group by company_id limit 3'
        );

        foreach ($raw as $company) {
            $company_ids[] = $company->company_id;
        }

        $companies = Company::with(['users'])
            ->whereIn('id', $company_ids)
            ->allowed()->get();

        $data = [];
        $data['total'] = count($companies);

        foreach ($companies as $index => $company) {
            $data['data'][$index]['name'] = $company->name;
            $data['data'][$index]['legal_name'] = $company->legal_name;
            $data['data'][$index]['total'] = count($company->users);
        }

        return $data;
    }

    public function getUsersStats()
    {
        $users = User::allowed()->get();
        $data = [];

        $data['total'] = 0;
        $data['register_by'][User::REGISTRATION_ADMIN] = 0;
        $data['register_by'][User::REGISTRATION_INVITATION] = 0;
        $data['register_by'][User::REGISTRATION_ON_SITE] = 0;
        $data['register_by'][User::REGISTRATION_IMPORT_FILE] = 0;

        foreach ($users as $index => $user) {
            if ($user->registration_method !== null) {
                $data['total'] = $data['total'] + 1;
                $data['register_by'][$user->registration_method] = $data['register_by'][$user->registration_method] + 1;
            }
        }

        return $data;
    }

    public function getInvitationsStats()
    {
        $invitations = Invitation::allowed()->get();
        $data = [];

        $data['total'] = count($invitations);
        $data['data']['pending'] = 0;
        $data['data']['accepted'] = 0;

        foreach ($invitations as $index => $invitation) {
            $status = ($invitation->status) ? 'accepted' : 'pending';
            $data['data'][$status] = $data['data'][$status] + 1;
        }

        return $data;
    }

    public function getExperiencesStats()
    {
        $experiences = Experience::systemVisible()->allowed()->get();
        $data = [];

        $data['total'] = count($experiences);

        foreach ($experiences as $index => $experience) {
            $status = ($experience->status) ? 'published' : 'unpublished';
            $type = $experience->type;
            $title = $experience->title;
            $created_at = $experience->created_at;

            $data['data'][] = [
                'title' => $title,
                'type' => $type,
                'status' => $status,
                'created_at' => $created_at->toDateTimeString()
            ];
        }

        return $data;
    }

    public function getJourneysStats()
    {
        $journeys = Experience::where('type', 'journey')->allowed()->get();
        $data = [];

        $data['total'] = count($journeys);
        $data['data']['published'] = 0;
        $data['data']['unpublished'] = 0;

        foreach ($journeys as $index => $journey) {
            $status = ($journey->status) ? 'published' : 'unpublished';
            $data['data'][$status] = $data['data'][$status] + 1;
        }

        return $data;
    }

    public function getAdventuresStats()
    {
        $adventures = Experience::where('type', 'adventure')->allowed()->get();
        $data = [];

        $data['total'] = count($adventures);
        $data['data']['published'] = 0;
        $data['data']['unpublished'] = 0;

        foreach ($adventures as $index => $adventure) {
            $status = ($adventure->status) ? 'published' : 'unpublished';
            $data['data'][$status] = $data['data'][$status] + 1;
        }

        return $data;
    }

    public function getFiveLast(Model $model)
    {
        $data = null;
        if (method_exists($model, 'scopeSystemVisible')) {
            $data = $model->orderBy('created_at', 'desc')->systemVisible()->take(5)->allowed()->get();
        } else {
            $data = $model->orderBy('created_at', 'desc')->take(5)->allowed()->get();
        }

        foreach ($data as $index => $d) {
            if ($model->getTable() == 'users') {
                $d->registration_method = $this->getRegistrationMethodText($d->registration_method);
            }
            if ($model->getTable() == 'experiences') {
                $d->type = $this->getStatusText($d->type);
                $d->status = $this->getStatusText(($d->status) ? 'published' : 'inactive');
            }
            if ($model->getTable() == 'invitations') {
                $d->status = $this->getStatusText(($d->status) ? 'accepted' : 'pending');
            }
        }

        return $data;
    }

    public function getRegistrationMethodText($registration_method)
    {
        $translated = [
            User::REGISTRATION_ADMIN => 'Administrador',
            User::REGISTRATION_IMPORT_FILE => 'Importado',
            User::REGISTRATION_INVITATION => 'Invitación',
            User::REGISTRATION_ON_SITE => 'Sitio Web'
        ];
        if (!array_key_exists($registration_method, $translated)) {
            return $registration_method;
        }

        return $translated[$registration_method];
    }

    public static function getStatusText($status)
    {
        $translated = [
            'pending' => 'Pendiente',
            'accepted' => 'Aceptada',
            'inactive' => 'Inactiva',
            'published' => 'Publicada',
            'draft' => 'Borrador',
            'adventure' => 'Aventura',
            'journey' => 'Viaje'
        ];

        if (!array_key_exists($status, $translated)) {
            return $status;
        }

        return $translated[$status];
    }


    /**
     * Prepare the dashboard data for root-admin, company-admin and student.
     * @return array
     */

    public function getDashboardData()
    {
        $user = Auth::user();
        if ($user->isSuper()) {
            return $this->getAdminDashboard();
        } else {
            return $this->getStudentDasboard($user);
        }

        return [];
    }

    public function getStudentDasboard($user)
    {
        $enrolled_experiences = (empty($user->enrollments))
            ? 0
            : $user->enrollments('primary', Experience::TYPE_ADVENTURE)
                ->count();

        $completed_experiences = (empty($user->enrollments))
            ? 0
            : $user->enrollments('primary', Experience::TYPE_ADVENTURE)
                ->wherePivot('status', Enrollment::ENROLLMENT_STATUS_FINISHED)->count();

        $in_progress_experiences = (empty($user->enrollments))
            ? 0
            : $user->enrollments('primary', Experience::TYPE_ADVENTURE)
                ->wherePivot('status', Enrollment::ENROLLMENT_STATUS_IN_PROGRESS)
                ->count();

        $enrolled_journeys = (empty($user->enrollments))
            ? 0
            : $user->enrollments('primary', Experience::TYPE_JOURNEY)
                ->count();

        $in_progress_journeys = (empty($user->enrollments))
            ? 0
            : $user->enrollments('primary', Experience::TYPE_JOURNEY)
                ->wherePivot('status', Enrollment::ENROLLMENT_STATUS_IN_PROGRESS)
                ->count();

        $completed_journeys = (empty($user->enrollments))
            ? 0
            : $user->enrollments('primary', Experience::TYPE_JOURNEY)
                ->wherePivot('status', Enrollment::ENROLLMENT_STATUS_FINISHED)
                ->count();

        $available_sessions = (empty($user->getExperienceProgress))
            ? 0
            : $user->getExperienceProgress()
                ->where('experience_enrollment_progress.status', Enrollment::ENROLLMENT_STATUS_ENROLLED)
                ->count();

        $finished_sessions = (empty($user->getExperienceProgress))
            ? 0
            : $user->getExperienceProgress()
                ->where('experience_enrollment_progress.status', EnrollmentService::SESSION_STATUS_FINISHED)
                ->count();

        $in_progress_sessions = (empty($user->getExperienceProgress))
            ? 0
            : $user->getExperienceProgress()
                ->where('experience_enrollment_progress.status', EnrollmentService::SESSION_STATUS_IN_PROGRESS)
                ->count();

        $user_activity = new Collection();

        $user_enrollments_progress = $user->getExperienceProgress()->get()->pluck('id');

        $tracking = EnrollmentTracking::with(['progress'])
            ->whereIn('enrollment_progress_id', $user_enrollments_progress)
            ->orderBy('created_at', 'desc')->get();


        foreach ($tracking as $a_index => $activity) {
            $item = [
                'created_at' => ($activity->created_at) ? $activity->created_at : 'No disponible',
                'duration' => ($activity->duration) ? $activity->duration : 'No disponible',
                'raw_score' => $activity->raw_score,
                'event_type' => $activity->event_type,
                'verb' => ($activity->verb) ? $activity->verb : 'No disponible',
                'session' => $activity->progress->session->title,
                'experience' => $activity->progress->session->experience->title,
                'experience_link' => url('/experiences/' . $activity->progress->session->experience->system_id)
            ];

            $user_activity->push($item);
        }

        $data = [
            'enrolled_journeys' => $enrolled_journeys,
            'completed_journeys' => $completed_journeys,
            'in_progress_journeys' => $in_progress_journeys,
            'enrolled_experiences' => $enrolled_experiences,
            'completed_experiences' => $completed_experiences,
            'in_progress_experiences' => $in_progress_experiences,
            'available_sessions' => $available_sessions,
            'finished_sessions' => $finished_sessions,
            'in_progress_sessions' => $in_progress_sessions,
            'user_activity' => $user_activity
        ];

        return $data;
    }

    public function getAdminDashboard($to = null, $request = null)
    {
        $activity = null;
        $filters = null;
        $time_period = $this->getPeriod($request);

        switch ($to) {
            case 'experiences':
                $target = 'dashboard.parts.experiences';
                $activity = $this->getExperiencesAndUserActivities($request);
                break;
            case 'users':
                $target = 'dashboard.parts.users';
                if (isset($request['user_id'])) {
                    $user_id = $request->get('user_id');
                    $activity = $this->getUserActivity($user_id, $request['filters']);
                    $filters = $this->getUserActivitiesFilter($user_id);
                }
                break;
            case 'general':
                $target = 'dashboard.parts.general';
                $activity = $this->getGeneralActivity($time_period, $request);
                break;
            default:
                $target = 'dashboard.parts.index';
                $activity = [
                    'invitations' => $this->getInvitations($time_period),
                    'recurrence' => $this->getExperiencesRecurrence($time_period),
                    'projection' => [],
                    'licenses_average' => $this->getLicencesStats($time_period),
                    'licenses_stacked' => $this->getLicencesStats($time_period, 'stacked'),
                    'exploitation' => $this->getLicencesStats($time_period, 'exploitation'),
                    'top_five' => $this->getTopEnrollment($time_period)->take(5)
                ];
                if (Auth::user()->isSuper()) {
                    $activity['companies'] = $this->getTopCompanies();

                    $activity['last_five_users'] = User::orderByDesc('created_at')->get()->take(5);

                    $users = User::all();
                    $grouped = $users->groupBy('registration_method');

                    $grouped->each(function ($value, $method) use (&$activity) {
                        if (isset($method) && $method !== '') {
                            $activity[$method] = $value;
                        }
                    });
                }
                break;
        }
        return ['target' => $target, 'activity' => $activity, 'filters' => $filters];
    }

    public function getExperiencesAndUserActivities($request)
    {
        $company = Auth::user()->company()->first();
        $give = $request->give;
        $data = null;

        $experience = $request->experience;
        $user = $request->user;

        if (!isset($experience)) {
            return [$give => []];
        }

        switch ($give) {
            case 'experience':
                $data = [
                    'assignations' => [
                        'total' => [
                            'class' => 'ari-b-gray',
                            'value' => $this->getLicenses(
                                [],
                                ExperienceLicence::STATUS_AVAILABLE,
                                '<>',
                                $experience
                            )
                        ],
                        'started' => [
                            'class' => 'ari-b-blue',
                            'value' => $this->getLicenses(
                                [],
                                ExperienceLicence::STATUS_UNAVAILABLE,
                                '=',
                                $experience
                            )
                        ],
                        'waiting' => [
                            'class' => 'ari-b-pink',
                            'value' => $this->getLicenses(
                                [],
                                ExperienceLicence::STATUS_WAITING_CONFIRMATION,
                                '=',
                                $experience
                            )
                        ]
                    ],
                    'invitations' => [
                        'total' => [
                            'class' => 'ari-b-gray',
                            'value' => $this->getInvitations([], 'sends', $experience['id'])
                        ],
                        'accepted' => [
                            'class' => 'ari-b-blue',
                            'value' => $this->getInvitations([], 'accepted', $experience['id'])
                        ],
                        'waiting' => [
                            'class' => 'ari-b-pink',
                            'value' => $this->getInvitations([], 'waiting', $experience['id'])
                        ]
                    ],
                    'enrollment' => [
                        'total' => [
                            'class' => 'ari-b-gray',
                            'value' => $this->getEnrollments([], $company, $experience['id'], 'total')
                        ],
                        'finished' => [
                            'class' => 'ari-b-blue',
                            'value' => $this->getEnrollments([], $company, $experience['id'], 'finished')
                        ],
                        'started' => [
                            'class' => 'ari-b-cyan',
                            'value' => $this->getEnrollments([], $company, $experience['id'], 'in_progress')
                        ],
                        'waiting' => [
                            'class' => 'ari-b-pink',
                            'value' => $this->getEnrollments([], $company, $experience['id'], 'wait_starting')
                        ]
                    ],
                    'performance_average' => $this->getPerformance([], $experience['id'], $company),
                    'recurrence' => $this->getExperiencesRecurrence([], $experience['id']),
                    'duration_average' => $this->getDuration([], $experience['id'], $company),
                    'enrolled_users' => $this->getEnrollments([], $company, $experience['id'], 'users')
                ];
                break;
            case 'user':
                break;
            case 'both':
                $data = [
                    'enrollment' => $this->getDetailedEnrollment($user['id'], $experience['id'], 'enrollment'),
                    'recurrence' => $this->getExperiencesRecurrence([], $experience['id'], $user['id']),
                    'sessions' => $this->getDetailedEnrollment($user['id'], $experience['id'], 'activities'),
                ];
                break;
            case 'search':
                $data = [
                    'target' => $request->target,
                    'result' => $this->search($request->search, $request->target)
                ];
                break;
            case 'experiences_users':
            default:
                $give = 'experiences_users';

                $experiences = Experience::CompanyOnly()
                    ->orHas('enrollments')
                    ->search($experience['search'])
                    ->paginate($experience['per_page'], ['*'], 'page', $experience['page']);

                $users = $company
                    ->users()
                    ->search($user['search'])
                    ->paginate($user['per_page'], ['*'], 'page', $user['page']);

                $data = [
                    'experiences' => $experiences,
                    'users' => $users
                ];
                break;
        }

        return [$give => $data];
    }

    public function search($query = null, $target = null)
    {
        if (!isset($query) || !isset($target)) {
            return null;
        }

        $result = null;

        if ($target === 'user') {
            $result = Auth::user()->company->first()->users()->search($query)->get();
        }

        if ($target === 'experience') {
            $result = Experience::search($query)->get();
        }

        return $result;
    }

    public function getDetailedEnrollment($user_id, $experience_id, $give = null)
    {
        $enrollment = Enrollment::where('user_id', $user_id)
            ->where('experience_id', $experience_id)
            ->get()
            ->last();

        if (isset($enrollment)) {
            // Duration
            if (!isset($enrollment->started_at) || !isset($enrollment->finished_at)) {
                $enrollment->duration = 0;
            } else {
                $finish = new Carbon($enrollment->finished_at);
                $enrollment->duration = $finish->diffInMinutes($enrollment->started_at);
                $enrollment->unit_time = 'minutes';
            }

            // Progress
            if (isset($enrollment)) {
                $sessions = $enrollment->progress()
                    //->with(['session'])
                    ->get()
                    ->each(function ($registry) {
                        $registry->title = $registry->session()->first()->title;
                        $registry->activities = $registry->activities()->where('is_scoreable', true)->get();
                        $registry->show_details = false;
                    });

                $progress = (
                    count(
                        $sessions->where('status', EnrollmentProgress::SESSION_STATUS_FINISHED)
                    ) / count($sessions)
                );

                $enrollment->progress = $progress;
            } else {
                $sessions = Experience::where('id', $experience_id)->first()->sessions;
                $enrollment->progress = null;
            }
        } else {
            $enrollment = [
                'started_at' => null,
                'finished_at' => null,
                'duration' => 0,
                'unit_time' => null,
                'progress' => null,
            ];
            $sessions = Experience::where('id', $experience_id)->first()->sessions;
        }

        $data = [
            'enrollment' => $enrollment,
            'activities' => $sessions
        ];

        if (isset($give)) {
            return $data[$give];
        }

        return $data;
    }

    public function getTopEnrollment($time_period)
    {
        $companyId = Auth::user()->company->first()->id;

        $enrollments = Enrollment::whereHas('user', function ($users) use ($companyId) {
            $users->whereHas('companies', function ($company) use ($companyId) {
                $company->where('companies.id', $companyId);
            });
        });

        //$enrollments = Enrollment::whereIn('user_id', $company_users);

        if ($time_period) {
            $enrollments = $enrollments->whereBetween('experience_enrollment.created_at', $time_period);
        }

        $aux = $enrollments->get()->pluck('experience_id')->unique();
        $experiences = Experience::allowed()
            ->whereIn('id', $aux)
            ->get(['id', 'author', 'price_default', 'title', 'cover']);

        for ($i = 0; $i < count($experiences); $i++) {
            $experience = $experiences[$i];

            $experience->enrollments = $enrollments->get()->where('experience_id', $experience->id)->count();
        }

        return $experiences->sortByDesc('enrollments');
    }

    public function getPerformance($time_period, $experience_id, $company)
    {
        $experience = Experience::findOrFail($experience_id);

        $evaluable = false;

        $sessions = $experience->sessions()->with(['activities'])->get();
        for ($i = 0; $i < count($sessions); $i++) {
            if (count($sessions[$i]->activities) > 0) {
                $evaluable = true;
                break;
            }
        }

        $users = $company->users->pluck('id');

        $enrollments = Enrollment::where('status', Enrollment::ENROLLMENT_STATUS_FINISHED)
            ->where('experience_id', $experience_id)
            ->whereIn('user_id', $users);

        if (count($time_period) > 0) {
            $start = new Carbon($time_period[0]);
            $end = new Carbon($time_period[1]);

            $enrollments->where('finished_at', '<=', $end)->where('started_at', '>=', $start);
        }

        $enrollments = $enrollments->get();

        if ($enrollments->isEmpty()) {
            $performance = 0;
        } else {
            $performance = array_sum($enrollments->pluck('score')->toArray()) / count($enrollments);
        }

        switch (true) {
            case ($performance >= 90):
                $class = 'has-text-ari-green';
                break;
            case ($performance >= 70 && $performance < 90):
                $class = 'has-text-ari-yellow';
                break;
            case ($performance < 70):
                $class = 'has-text-ari-red';
                break;
            default:
                $class = '';
                break;
        }

        return [
            'total_enrollment' => count($enrollments),
            'evaluable' => $evaluable,
            'class' => $class,
            'value' => $performance
        ];
    }

    public function getDuration($time_period, $experience_id, $company)
    {
        $users = $company->users->pluck('id');

        $enrollments = Enrollment::where('status', Enrollment::ENROLLMENT_STATUS_FINISHED)
            ->where('experience_id', $experience_id)
            ->whereIn('user_id', $users);

        if (count($time_period) > 0) {
            $start = new Carbon($time_period[0]);
            $end = new Carbon($time_period[1]);

            $enrollments->where('finished_at', '<=', $end)->where('started_at', '>=', $start);
        }

        $enrollments = $enrollments->get();

        if ($enrollments->isEmpty()) {
            return 0;
        }

        $enrollments->each(function ($enroll) {
            if (!isset($enroll->started_at) || !isset($enroll->finished_at)) {
                $enroll->duration = 0;
            } else {
                $finish = new Carbon($enroll->finished_at);
                $enroll->duration = $finish->diffInMinutes($enroll->started_at);
                $enroll->unit_time = 'minutes';
            }
        });

        return [
            'value' => array_sum($enrollments->pluck('duration')->toArray()) / count($enrollments),
            'unit_time' => 'minutes'
        ];
    }

    public function getGeneralActivity($time_period, $request)
    {
        $give = $request->get('give');
        $data = null;
        $company = Auth::user()->company()->first();

        switch ($give) {
            default:
            case 'licenses':
                $data = [
                    'buyed' => [
                        'class' => 'ari-cyan',
                        'value' => $this->getLicenses($time_period)
                    ],
                    'assigned' => [
                        'class' => 'ari-gray',
                        'value' => $this->getLicenses($time_period, ExperienceLicence::STATUS_AVAILABLE, '<>')
                    ],
                    'available' => [
                        'class' => 'ari-pink',
                        'value' => $this->getLicenses($time_period, ExperienceLicence::STATUS_AVAILABLE)
                    ],
                    'active' => [
                        'class' => 'ari-gray',
                        'value' => $this->getLicenses($time_period, ExperienceLicence::STATUS_UNAVAILABLE)
                    ],
                    'inactive' => [
                        'class' => 'ari-pink',
                        'value' => $this->getLicenses($time_period, ExperienceLicence::STATUS_WAITING_CONFIRMATION)
                    ],

                ];
                break;
            case 'users':
                $data = [
                    'registered' => $this->getUsers($time_period, $company),
                    'administrator' => $this->getUsers($time_period, $company, User::REGISTRATION_ADMIN),
                    'invitations' => $this->getUsers($time_period, $company, User::REGISTRATION_INVITATION),
                    'signup' => $this->getUsers($time_period, $company, User::REGISTRATION_ON_SITE),
                    'imported' => $this->getUsers($time_period, $company, User::REGISTRATION_IMPORT_FILE),
                    'total_amount' => $this->getEnrollments($time_period, $company, null, null, true),
                    'top_ten_users' => $this->getUsers($time_period, $company, null, true)
                ];
                break;
            case 'invitations':
                $data = $this->getInvitations($time_period);
                break;
            case 'enrollments':
                $data = [
                    'recurrence' => $this->getExperiencesRecurrence($time_period),
                    'projection' => [
                        ['total' => 10],
                        ['total' => 15],
                        ['total' => 45],
                        ['total' => 72],
                        ['total' => 98]
                    ],
                    'experiences' => $this->getEnrollments(
                        $time_period,
                        $company,
                        null,
                        null,
                        false,
                        $request->get('page'),
                        $request->get('per_page')
                    )
                ];
                break;
        }
        return [$give => $data];
    }

    public function getLicenses($time_period, $status = null, $operator = '=', $experience_id = null)
    {
        $licenses = ExperienceLicence::BuyedLicenses();

        if (isset($status)) {
            $licenses->where('status', $operator, $status);
        }

        if (count($time_period) > 0) {
            $start = new Carbon($time_period[0]);
            $end = new Carbon($time_period[1]);

            $licenses->where('created_at', '<=', $end)->where('created_at', '>=', $start);
        }

        if (isset($experience_id)) {
            $licenses->where('experience_id', $experience_id);
        }

        return $licenses->count();
    }

    public function getPeriod($request)
    {
        if (isset($request) && $request->has('time_period')) {
            return [$request->time_period[0] . ' 00:00:00', $request->time_period[1] . ' 23:59:59'];
        } else {
            $end = Carbon::now()->format('Y-m-d');
            $start = Carbon::create(
                Carbon::now()->year,
                Carbon::now()->subMonth()->format('m'),
                Carbon::now()->day
            )
                ->format('Y-m-d');
            return [$start . ' 00:00:00', $end . ' 23:59:59'];
        }
    }

    public function getUsers($time_period, $company, $registration_method = null, $by_score = false)
    {
        $users = $company->users();

        if ($by_score) {
            $users = $users->get();

            $enrollments = Enrollment::whereIn('user_id', $users->pluck('id'))
                ->where('status', Enrollment::ENROLLMENT_STATUS_FINISHED);

            if (count($time_period) > 0) {
                $start = new Carbon($time_period[0]);
                $end = new Carbon($time_period[1]);

                //$users->where('created_at', '<=', $end)->where('created_at', '>=', $start);
                $enrollments = $enrollments->whereBetween('created_at', $time_period);
            }

            $enrollments = $enrollments->get();

            if (empty($enrollments)) {
                return [];
            }

            $enrollments = $enrollments->unique(function ($item) {
                return $item->user_id . $item->experience_id;
            })
                ->groupBy('user_id')
                ->map(function ($user_enroll, $id_user) use ($users) {
                    $email = $users->firstWhere('id', $id_user)->email;
                    $finished_experiences = count($user_enroll);

                    $value = array_sum($user_enroll->pluck('score')->toArray()) / $finished_experiences;

                    switch (true) {
                        case ($value >= 90):
                            $class = 'has-text-ari-green';
                            break;
                        case ($value >= 70 && $value < 90):
                            $class = 'has-text-ari-yellow';
                            break;
                        case ($value < 70):
                            $class = 'has-text-ari-red';
                            break;
                        default:
                            $class = '';
                            break;
                    }

                    return [
                        'email' => $email,
                        'finished_experiences' => $finished_experiences,
                        'average' => [
                            'value' => $value,
                            'class' => $class
                        ]
                    ];
                });

            return $enrollments->toArray();
        } else {
            if (isset($registration_method)) {
                $users = $users->where('registration_method', $registration_method);
            }

            //$users->get();

            if (count($time_period) > 0) {
                $start = new Carbon($time_period[0]);
                $end = new Carbon($time_period[1]);

                //$users->where('created_at', '<=', $end)->where('created_at', '>=', $start);
                $users->whereBetween('created_at', $time_period);
            }

            return $users->count();
        }
    }

    public function getInvitations($time_period, $specific = null, $experience_id = null)
    {
        if (Auth::user()->isSuper()) {
            $invitations_list = Invitation::all();
        } else {
            $invitations_list = Auth::user()->company()->first()->invitations()->with('experience');

            if (count($time_period) > 0) {
                $start = new Carbon($time_period[0]);
                $end = new Carbon($time_period[1]);

                $invitations_list->where('created_at', '<=', $end)->where('created_at', '>=', $start);
            }

            $invitations_list = $invitations_list->get();
        }

        if (isset($experience_id)) {
            $invitations_list = $invitations_list->where('experience_id', $experience_id);
        }

        $invitations = [
            'sends' => $invitations_list->count(),
            'accepted' => $invitations_list->where('status', Invitation::INVITATION_ACCEPTED)->count(),
            'waiting' => $invitations_list->where('status', Invitation::INVITATION_PENDING)->count()
        ];

        if (isset($specific)) {
            return $invitations[$specific];
        }

        return $invitations;
    }

    public function getEnrollments(
        $time_period,
        $company,
        $experience_id = null,
        $specific = null,
        $only_total = false,
        $page = null,
        $per_page = null
    ) {
        $experiences = new Collection();
        $company_users = $company->users->pluck('id')->toArray();

        if (isset($experience_id)) {
            $experiences->push($experience_id);
        } else {
            $whereIn = implode(',', $company_users);
            $query = '
                select e.id from experiences e
                inner join experience_enrollment ee
                on e.id = ee.experience_id
                where ee.user_id in (' . $whereIn . ')
            ';
            $aux = new Collection(DB::select($query));
            $experiences = $aux->pluck('id');
        }

        $experiences = Experience::whereIn('id', $experiences)->get();
        $total_enrollments = 0;

        $experiences->each(function ($experience) use (
            $time_period,
            $company_users,
            &$experience_id,
            &$specific,
            &$company,
            &$total_enrollments
        ) {
            $enrollments = $experience->enrollments()->whereIn('experience_enrollment.user_id', $company_users);

            if (count($time_period) > 0) {
                $enrollments = $enrollments->whereBetween('experience_enrollment.created_at', $time_period);
            }

            $enrollments = $enrollments->get();

            $finished = $enrollments->where('pivot.status', Enrollment::ENROLLMENT_STATUS_FINISHED)->count();
            $in_progress = $enrollments->where('pivot.status', Enrollment::ENROLLMENT_STATUS_IN_PROGRESS)->count();
            $wait_starting = $enrollments->where('pivot.status', Enrollment::ENROLLMENT_STATUS_ENROLLED)->count();

            $users = new Collection();

            if (!empty($enrollments)) {
                for ($i = 0; $i < count($enrollments); $i++) {
                    $user = $enrollments[$i];
                    $enroll = $user->pivot;
                    $progress = EnrollmentProgress::where('enrollment_id', $enroll->id)->get();

                    $data = [
                        'id' => $user->id,
                        'full_name' => $user->full_name,
                        'email' => $user->email,
                        'progress' => (
                            count(
                                $progress->where('status', EnrollmentProgress::SESSION_STATUS_FINISHED)
                            ) / count($progress)
                        ),
                        'score' => $enroll->score,
                        'started_at' => $enroll->started_at,
                        'finished_at' => $enroll->finished_at,
                    ];

                    $users->push($data);
                }
            }

            $aux = [
                'users' => $users,
                'items' => $enrollments,
                'total' => $enrollments->count(),
                'finished' => $finished,
                'in_progress' => $in_progress,
                'wait_starting' => $wait_starting
            ];

            $experience->enrollments = $aux;

            $total_enrollments += $enrollments->count();
        });

        $exp = $experiences->firstWhere('id', $experience_id);

        if (isset($exp)) {
            if (isset($specific)) {
                return $exp->enrollments[$specific];
            } else {
                $experience = new Experience();
                $experience->enrollments = [
                    'items' => [],
                    'total' => 0,
                    'finished' => 0,
                    'in_progress' => 0,
                    'wait_starting' => 0
                ];

                return $experience->enrollments;
            }
        }

        if ($only_total) {
            return $total_enrollments;
        }

        if (isset($page) && isset($per_page)) {
            $last_page = (int)ceil(count($experiences) / $per_page);
            $current_page = ($page > $last_page) ? $last_page : $page;
            $items = $experiences->forPage($current_page, $per_page)->all();

            return [
                'data' => $items,
                'current_page' => $current_page,
                'per_page' => $per_page,
                'last_page' => $last_page
            ];
        }

        return $experiences;
    }

    // Dashboard summary section

    private function getTopCompanies()
    {
        $companies = Company::all();

        return $companies->sortByDesc('total_users')->take(5);
    }

    private function getEnrollmentFor(ExperienceLicence $license)
    {
        if (isset($license->user)) {
            $enrollment = $license->user->enrollments()
                ->withPivot(['status'])
                ->where('experience_id', $license->experience_id)
                ->get();
        }

        unset($license->user);

        return (isset($enrollment)) ? $enrollment->last() : null;
    }

    private function getLicencesStats($time_period, $for = null)
    {
        switch ($for) {
            /* This is for the stacked graphs of "Licenciamiento" and "Aprovechamiento" in summary. */
            case 'stacked':
                $available = 0;
                $assigned = 0;
                $waiting_user_register = 0;
                $buyed_total = 0;

                $buyed = ExperienceLicence::BuyedLicenses();

                if (count($time_period) > 0) {
                    $start = new Carbon($time_period[0]);
                    $end = new Carbon($time_period[1]);

                    $buyed->whereBetween('created_at', [$start, $end]);
                }

                $buyed->get();

                $buyed->each(function ($l) use (&$assigned, &$available, &$waiting_user_register, &$buyed_total) {
                    $buyed_total++;
                    if ($l->status === ExperienceLicence::STATUS_AVAILABLE) {
                        $available++;
                    } elseif ($l->status === ExperienceLicence::STATUS_WAITING_CONFIRMATION) {
                        $waiting_user_register++;
                    } else {
                        $assigned++;
                    }
                });

                return [
                    'licenses_buyed' => $buyed_total,
                    'licenses_available' => $available,
                    'licenses_assigned' => $assigned,
                    'licenses_pending' => $waiting_user_register,
                ];
                break;
            /* This is for the table of "Aprovechamiento" detailed view. */
            case 'detailed':
                $started_allowed_statuses = new Collection([
                    Enrollment::ENROLLMENT_STATUS_IN_PROGRESS
                ]);
                $experiences = new Collection();

                $licenses = ExperienceLicence::BuyedLicenses();

                $licenses->each(function ($license) use ($experiences, $started_allowed_statuses) {
                    $experience = $license->experience;

                    $experience->not_started = 0;
                    $experience->started = 0;
                    $experience->finished = 0;

                    if (!$experiences->contains('id', $experience->id)) {
                        $experience_enrollments = $experience->enrollments()
                            ->where('user_id', '<>', \Auth::user()->id)->get();
                        $experience_enrollments->each(function ($enroll) use ($experience, $started_allowed_statuses) {
                            if ($started_allowed_statuses->contains($enroll->pivot->status)) {
                                $experience->started++;
                            } elseif (Enrollment::ENROLLMENT_STATUS_FINISHED === $enroll->pivot->status) {
                                $experience->finished++;
                            }

                            if (Enrollment::ENROLLMENT_STATUS_ENROLLED === $enroll->pivot->status) {
                                $experience->not_started++;
                            }
                        });

                        if (ExperienceLicence::STATUS_WAITING_CONFIRMATION === $license->status) {
                            $experience->not_started++;
                        }

                        unset($experience->enrollments);
                        $experiences->push($experience);
                    } else {
                        $key = $experiences->search(function ($item) use ($experience) {
                            return $item->id === $experience->id;
                        });
                        $item = $experiences->get($key);
                        if (!!$item) {
                            if (ExperienceLicence::STATUS_UNAVAILABLE === $license->status) {
                                $experience->not_started++;
                            }
                            if (ExperienceLicence::STATUS_WAITING_CONFIRMATION === $license->status) {
                                $item->not_started++;
                            }
                            unset($item->enrollments);
                        }
                    }
                });

                return $experiences;
                break;
            /* This is for the stacked graph of "Aprovechamiento" detailed view. */
            case 'exploitation':
                if (count($time_period) > 0) {
                    $start = new Carbon($time_period[0]);
                    $end = new Carbon($time_period[1]);

                    $licenses = ExperienceLicence::BuyedLicenses()
                        ->whereBetween('created_at', [$start, $end])
                        ->get();
                } else {
                    $licenses = ExperienceLicence::BuyedLicenses()
                        ->get();
                }

                $users = [];
                $experiences = [];

                $licenses->each(function ($license) use (
                    &$users,
                    &$experiences
                ) {
                    if ($license->experience_id) {
                        $experiences[$license->experience_id] = $license->experience_id;
                    }

                    if ($license->user_id) {
                        $users[$license->user_id] = $license->user_id;
                    }
                });

                $assigned = Enrollment::whereIn('experience_id', $experiences)
                    ->whereIn('user_id', $users)
                    ->where('status', Enrollment::ENROLLMENT_STATUS_ENROLLED)
                    ->get();

                $in_progress = Enrollment::whereIn('experience_id', $experiences)
                    ->whereIn('user_id', $users)
                    ->where('status', Enrollment::ENROLLMENT_STATUS_IN_PROGRESS)
                    ->get();


                $finished = Enrollment::whereIn('experience_id', $experiences)
                    ->whereIn('user_id', $users)
                    ->where('status', Enrollment::ENROLLMENT_STATUS_FINISHED)
                    ->get();

                $data = [
                    'assigned' => count($assigned),
                    'started' => count($in_progress),
                    'finished' => count($finished)
                ];

                return $data;
                break;
            default:
                $available = 0;
                $assigned = 0;
                $buyed_total = 0;

                $buyed = ExperienceLicence::BuyedLicenses();

                if (count($time_period) > 0) {
                    $start = new Carbon($time_period[0]);
                    $end = new Carbon($time_period[1]);

                    $buyed->whereBetween('created_at', [$start, $end]);
                }

                $buyed->get();

                $buyed->each(function ($l) use (&$assigned, &$available, &$buyed_total) {
                    $buyed_total++;
                    if ($l->status !== ExperienceLicence::STATUS_AVAILABLE) {
                        $assigned++;
                    } elseif ($l->status === ExperienceLicence::STATUS_AVAILABLE) {
                        $available++;
                    }
                });

                return [
                    'licenses_buyed' => $buyed_total,
                    'licenses_available' => $available,
                    'licenses_assigned' => $assigned
                ];
                break;
        }
    }

    // Dashboard experiences and journeys section

    public function getExperiencesExploitation($with_top = false, $detailed = false)
    {
        $all_experiences = new Collection();
        $journeys_finished = new Collection([]);
        $total_assignations = [
            'top_five' => [],
            'assigned' => 0,
            'started' => 0,
            'finished' => 0,
        ];

        if (Auth::user()->isSuper()) {
            $users = User::all();
        } else {
            $users = Auth::user()->company()->first()->users;
        }

        // List of Experience
        $users->each(function ($user) use ($all_experiences, &$total_assignations) {
            $assignated_journeys = new Collection();
            $assignated_experiences = new Collection();
            $started_statuses = new Collection([
                Enrollment::ENROLLMENT_STATUS_STARTED,
                Enrollment::ENROLLMENT_STATUS_IN_PROGRESS,
                Enrollment::ENROLLMENT_STATUS_ENROLLED
            ]);

            $user->assignatedExperiences()
                ->withPivot(['status'])
                ->get()
                ->each(function ($assigned_experience) use (
                    &$total_assignations,
                    &$assignated_journeys,
                    &$assignated_experiences,
                    $all_experiences,
                    $started_statuses
                ) {
                    if ($assigned_experience->type === Experience::TYPE_JOURNEY) {
                        $assignated_journeys->push([
                            'id' => $assigned_experience->id,
                            'title' => $assigned_experience->title,
                            'status_assignation' => $assigned_experience->pivot->status,
                            'assigned_at' => $assigned_experience->pivot->created_at,
                            'last_update' => $assigned_experience->pivot->updated_at
                        ]);
                    } else {
                        $assignated_experiences->push([
                            'id' => $assigned_experience->id,
                            'title' => $assigned_experience->title,
                            'status_assignation' => $assigned_experience->pivot->status,
                            'assigned_at' => $assigned_experience->pivot->created_at,
                            'last_update' => $assigned_experience->pivot->updated_at
                        ]);
                    }

                    $experience_has_counted = $all_experiences->firstWhere('id', $assigned_experience->id);

                    if (!$experience_has_counted) {
                        $all_experiences->push($assigned_experience);

                        $total_assignations['assigned'] += 1;

                        if ($started_statuses->contains($assigned_experience->status)) {
                            $total_assignations['started'] += 1;
                        }

                        if ($assigned_experience->status === Enrollment::ENROLLMENT_STATUS_FINISHED) {
                            $total_assignations['finished'] += 1;
                        }
                    }
                });

            unset($user->assignatedExperiences);

            $user->assignations = [
                'journeys' => $assignated_journeys,
                'experiences' => $assignated_experiences
            ];
        });

        // Experiences user enrolled quantity
        $all_experiences->each(function ($journey) use (&$total_assignations, &$journeys_finished) {
            $total_assignations['assigned'] += $journey->assigned;
            $total_assignations['started'] += count($journey->enrollments);

            $journey->started = count($journey->enrollments);
            $journey->finished = 0;

            $journey->enrollments()
                ->where('experience_enrollment.status', Enrollment::ENROLLMENT_STATUS_FINISHED)
                ->get()
                ->each(function ($j) use (&$journeys_finished) {
                    $j->finished++;
                    $journeys_finished->push($j);
                });
        });
        $total_assignations['finished'] = count($journeys_finished);

        // Top five journeys
        if ($with_top || $detailed) {
            $temp = $all_experiences->sortBy(function ($journey) {
                return $journey->assigned;
            });
            $total_assignations['top_five'] = ($detailed) ? $temp->all() : $temp->take(5);
        }

        return $total_assignations;
    }

    public function getExperiencesRecurrence($time_period, $experience_id = null, $user_id = null)
    {
        $recurrence = new Collection();
        $company_id = Auth::user()->company->first()->id;

        if (Auth::user()->isSuper()) {
            //$user_list = User::all()->pluck('id');

            $enrollments_list = Enrollment::get(['id']);
        } elseif (isset($user_id)) {
            //$user_list = User::where('id', $user_id)->get()->pluck('id');

            $enrollments_list = Enrollment::whereHas('user', function ($user) use ($user_id) {
                $user->where('id', $user_id);
            })->get(['id']);
        } else {
            //$user_list = Auth::user()->company()->first()->users->pluck('id');

            $enrollments_list = Enrollment::whereHas('user', function ($user) use ($company_id) {
                $user->whereHas('companies', function ($company) use ($company_id) {
                    $company->where('companies.id', $company_id);
                });
            })->get(['id']);
        }

        return $this->getCompanyRecurrenceStat($enrollments_list, $time_period, $experience_id);
    }

    public function getExperiencesRating()
    {
        $users_ratings = new Collection();
        $ratings = new Collection();
        $experiences = new Collection();

        Auth::user()->company()->first()->users->each(function ($user) use (&$users_ratings) {
            if (count($user->experiencesRatings) > 0) {
                $users_ratings->push($user->experiencesRatings);
            }
        });

        $users_ratings->each(function ($user) use (&$ratings, &$experiences) {
            $user->each(function ($experience) use (&$ratings, &$experiences) {
                $ratings->push([
                    'rating' => $experience->pivot->rating,
                ]);
                if (!$experiences->contains('id', $experience->id)) {
                    $experiences->push($experience);
                }
            });
        });


        return [
            'rating' => $ratings->groupBy('rating'),
            'list' => $experiences
        ];
    }

    public function getTop($type = Experience::TYPE_JOURNEY . '|' . Experience::TYPE_ADVENTURE, $detailed = false)
    {
        $top_experiences = new Collection();
        $average = 0;

        if (Auth::user()->isSuper()) {
            $users = User::all();
        } else {
            $users = Auth::user()->company()->first()->users;
        }

        $users->each(function ($user) use (&$top_experiences, &$type, &$detailed) {
            if (count($user->enrollments) > 0) {
                $user->enrollments()
                    ->where('experiences.type', 'regexp', $type)
                    ->get()
                    ->each(function ($experience) use (&$top_experiences, &$detailed) {
                        $experience->load(['author']);

                        if (!$top_experiences->contains('id', $experience->id)) {
                            $top_experiences->push($experience);
                        }

                        if ($detailed) {
                            $experience->visit_average = $experience->quantity;
                        }
                    });
            }
        });

        $enrollments = $this->getAssignationsEnrollments();
        $top_experiences = $this->getExperiencesByEnrollment($type);

        $quantity_enrollment = $top_experiences->pluck('enroll_average')->sum();
        $quantity_experiences = $top_experiences->count();

        if (!$detailed) {
            $top_experiences = $top_experiences->take(5);
        }

        //$average = ($quantity_experiences > 0) ? ($quantity_enrollment / $quantity_experiences) * 100 : 0;
        $average = ($enrollments['total'] > 0) ? ($enrollments['enrolled'] / $enrollments['total']) * 100 : 0;

        return [
            'primary' => $average,
            'list' => $top_experiences
        ];
    }

    public function getAssignationsEnrollments(
        $type = Experience::TYPE_JOURNEY . '|' . Experience::TYPE_ADVENTURE,
        $detailed = false,
        $whitout_unique = false
    ) {
        $list = new Collection();
        $total_assignations = new Collection([
            'list' => [],
            'total' => 0,
            'enrolled' => 0,
            'finished' => 0
        ]);


        if (Auth::user()->isSuper()) {
            $users = User::all();
        } else {
            $users = Auth::user()->company()->first()->users;
        }

        $users->each(function ($user) use ($total_assignations, &$list, &$type, $whitout_unique) {
            if ($whitout_unique) {
                $user->enrollments()->where('experiences.type', 'regexp', $type)->get()->each(
                    function ($exp) use ($list) {
                        $is_contained = $list->contains(function ($experience) use ($exp) {
                            return $experience->id === $exp->id;
                        });
                        $exp->finished = 0;
                        if ($is_contained) {
                            $item = $list->firstWhere('id', $exp->id);

                            $item->quantity++;
                        } else {
                            $exp->quantity = 1;
                            $list->push($exp);
                        }

                        $exp->total = count($exp->enrollments);
                    }
                );
            } else {
                $user->enrollments()->where('experiences.type', 'regexp', $type)->get()->unique('experience_id')->each(
                    function ($exp) use ($list) {
                        $is_contained = $list->contains(function ($experience) use ($exp) {
                            return $experience->id === $exp->id;
                        });
                        $exp->finished = 0;
                        if ($is_contained) {
                            $item = $list->firstWhere('id', $exp->id);

                            $item->quantity++;
                        } else {
                            $exp->quantity = 1;
                            $list->push($exp);
                        }

                        $exp->total = count($exp->enrollments);
                    }
                );
            }

            $assignated_experiences = $user->assignatedExperiences()->where('experiences.type', 'regexp', $type)
                ->where('buyed_by', '<>', $user->id)->get();

            if (count($assignated_experiences) > 0) {
                $total_assignations['total'] += count($assignated_experiences);

                $user->assignatedExperiences->each(function ($experience) use (
                    &$total_assignations,
                    &$user,
                    &$type,
                    $list
                ) {
                    $user->enrollments()
                        ->where('experiences.type', 'regexp', $type)
                        ->get()->unique('experience_id')
                        ->each(function ($enroll) use (&$experience, &$total_assignations, $list) {
                            $list->search(function ($item) use ($experience) {
                                return $item->id == $experience->id;
                            });

                            if ($experience->id === $enroll->id) {
                                $total_assignations['enrolled'] += 1;
                                if ($enroll->pivot->status === Enrollment::ENROLLMENT_STATUS_FINISHED) {
                                    $total_assignations['finished'] += 1;
                                }
                            }
                        });
                });
            }
        });

        $total_assignations['list'] = ($detailed) ? $list : $list->take(5);

        return $total_assignations;
    }

    private function getCompanyRecurrenceStat($enrollmens_list, $time_period, $experience_id = null)
    {
        $tracking = EnrollmentTracking::where('verb', 'visit');

        if (!empty($time_period)) {
            $tracking = $tracking->whereBetween('created_at', $time_period);
        }

        if (!empty($enrollmens_list)) {
            //$tracking = $tracking->whereIn('progress.enrollment.id', $enrollmens_list);

            $tracking = $tracking
                ->whereHas('progress', function ($progress) use ($enrollmens_list) {
                    $progress->whereHas('enrollment', function ($enroll) use ($enrollmens_list) {
                        $enroll->whereIn('experience_enrollment.id', $enrollmens_list);
                    });
                });
        }

        if (isset($experience_id)) {
            //$tracking = $tracking->where('progress.enrollment.experience_id', $experience_id);

            $tracking = $tracking->whereHas('progress', function ($progress) use ($experience_id) {
                $progress->whereHas('enrollment', function ($enroll) use ($experience_id) {
                    $enroll->where('experience_enrollment.experience_id', $experience_id);
                });
            });
        }

        $grouped_tracking = $tracking->get()
            ->groupBy(function ($item) {
                return $created_at = Carbon::parse($item->created_at)->toDateString();
            })
            ->map(function ($items) {
                return ['total' => count($items)];
            });

        return $grouped_tracking->take(6);
    }

    // Dashboard user's section

    public function getUserActivity($user_id, $filters = null)
    {
        $activity_user = User::findOrFail($user_id)->getExperienceProgress();

        if (isset($filters)) {
            $filters = json_decode($filters);

            if ($filters->experience->selected !== null) {
                $selected = $filters->experience->selected;
                $activity_user->where('experience_enrollment.experience_id', $selected);
            }
            if ($filters->adventure->selected !== null) {
                $selected = $filters->adventure->selected;
                $activity_user->where('experience_enrollment.experience_id', $selected);
            }
            if ($filters->challenge->selected !== null) {
                $selected = $filters->challenge->selected;
                $activity_user->where('experience_enrollment_progress.session_id', $selected);
            }
        }

        return $activity_user->with(['session'])->get();
    }

    private function getLastEnrollment($user, $enrollment_id)
    {
        $enrollment = $user->experienceEnrollments()->where('id', $enrollment_id)->first();
        return $enrollment;
    }

    public function getUserActivitiesFilter($user_id)
    {
        $filters = new Collection();
        $filters['journeys'] = new Collection();
        $filters['experiences'] = new Collection();
        $filters['sessions'] = new Collection();

        $user = User::findOrFail($user_id);

        if (isset($user)) {
            //print($user->experienceEnrollments);exit;
            $user->enrollments()->withPivot(['created_at'])->get()->each(function ($experience) use (&$filters, $user) {
                //print($experience->toJson());exit;
                if ($experience->type === Experience::TYPE_JOURNEY) {
                    $aux = new Collection();
                    $aux['id'] = $experience->id;
                    $aux['title'] = $experience->title;
                    $aux['type'] = $experience->type;
                    $aux['enroll_status'] = $experience->pivot->status;
                    $aux['enrolled_from'] = $experience->pivot->created_at;
                    $aux['enroll_progress'] = $experience->getUserProgress($user->id);
                    $aux['adventures'] = $experience->adventures->map(function ($adventure) use (
                        &$filters,
                        &$experience,
                        $user
                    ) {
                        $aux = new Collection();
                        $aux['id'] = $adventure->id;
                        $aux['title'] = $adventure->title;
                        $aux['type'] = $adventure->type;
                        $aux['journey_id'] = $experience->id;
                        $aux['enroll_status'] = $experience->pivot->status;
                        $aux['enrolled_from'] = $experience->pivot->created_at;
                        $aux['enroll_progress'] = $adventure->getUserProgress($user->id);
                        $aux['sessions'] = $adventure->sessions->map(function ($session) use (&$filters) {
                            $aux = new Collection();
                            $aux['id'] = $session->id;
                            $aux['type'] = $session->type;
                            $aux['title'] = $session->title;
                            $aux['experience_id'] = $session->experience_id;

                            $already_in_sessions = $filters['sessions']->contains(function ($session) use ($aux) {
                                return (int)$session['id'] === (int)$aux['id'];
                            });

                            if (!$already_in_sessions) {
                                $filters['sessions']->push($aux);
                            }
                            return $aux;
                        });

                        $already_in_experiences = $filters['experiences']->contains(function ($experience) use ($aux) {
                            return (int)$experience['id'] === (int)$aux['id'];
                        });
                        if (!$already_in_experiences) {
                            //$filters['experiences']->push($aux);
                        }

                        return $aux;
                    });

                    $already_in_journeys = $filters['journeys']->contains(function ($journey) use ($aux) {
                        return (int)$journey['id'] === (int)$aux['id'];
                    });
                    if (!$already_in_journeys) {
                        $filters['journeys']->push($aux);
                    }
                } else {
                    $aux = new Collection();
                    $aux['id'] = $experience->id;
                    $aux['title'] = $experience->title;
                    $aux['type'] = $experience->type;
                    $aux['enroll_status'] = $experience->pivot->status;
                    $aux['enrolled_from'] = $experience->pivot->created_at;
                    $aux['enroll_progress'] = $experience->getUserProgress($user->id, true);
                    $aux['last_enrollment'] = $this->getLastEnrollment($user, $experience->pivot->id);
                    $aux['sessions'] = $experience->sessions->map(function ($session) use (&$filters) {
                        $aux = new Collection();
                        $aux['id'] = $session->id;
                        $aux['type'] = $session->type;
                        $aux['title'] = $session->title;
                        $aux['experience_id'] = $session->experience_id;

                        $already_in_sessions = $filters['sessions']->contains(function ($session) use ($aux) {
                            return (int)$session['id'] === (int)$aux['id'];
                        });

                        if (!$already_in_sessions) {
                            $filters['sessions']->push($aux);
                        }
                        return $aux;
                    });

                    $already_in_experiences = $filters['experiences']->contains(function ($experience) use ($aux) {
                        return (int)$experience['id'] === (int)$aux['id'];
                    });
                    if (!$already_in_experiences) {
                        $filters['experiences']->push($aux);
                    }
                }
            });
        }

        return $filters;
    }

    public function getEnrollmentsStatus()
    {
        $statuses = [
            [
                'name' => 'ENROLLMENT_STATUS_IN_PROGRESS',
                'value' => Enrollment::ENROLLMENT_STATUS_IN_PROGRESS,
                'label' => 'in_progress'
            ],
            [
                'name' => 'ENROLLMENT_STATUS_FINISHED',
                'value' => Enrollment::ENROLLMENT_STATUS_FINISHED,
                'label' => 'finished'
            ],
            [
                'name' => 'ENROLLMENT_STATUS_ENROLLED',
                'value' => Enrollment::ENROLLMENT_STATUS_ENROLLED,
                'label' => 'not_started'
            ],
        ];

        return $statuses;
    }

    // Detailed sections

    public function getDetailedData($route_name, $request)
    {
        $to = explode('.', $route_name)[1];
        $activity = null;
        $filters = null;
        switch ($to) {
            case 'licenses':
                $target = 'dashboard.parts.detailed.licenses';
                $activity = [
                    'licenses_stacked' => $this->getLicencesStats('stacked'),
                    'licenses_experiences' => $this->getLicencesStats('detailed'),
                ];
                break;
            case 'progress':
                $target = 'dashboard.parts.detailed.advantage';
                $activity = [
                    'exploitation' => $this->getLicencesStats('detailed'),
                    'exploitation_stacked' => $this->getLicencesStats('exploitation')
                ];
                break;
            case 'journey_enrollments':
                $target = 'dashboard.parts.detailed.enrollments';
                $data = $this->getAssignationsEnrollments(Experience::TYPE_JOURNEY, true);

                foreach ($data['list'] as $journey) {
                    $journey->started = 0;
                    $journey->enrolled = 0;
                    $journey->assigned = $this->assignedUsers($journey);

                    $journey->enrollments->each(function ($enrollment) use (&$journey) {

                        switch ($enrollment->pivot->status) {
                            case Enrollment::ENROLLMENT_STATUS_ENROLLED:
                                $journey->enrolled++;
                                break;
                            case Enrollment::ENROLLMENT_STATUS_STARTED:
                                $journey->started++;
                                break;
                            default:
                                break;
                        }
                    });
                }

                $data['label'] = 'journeys';

                $activity = [
                    'enrollments' => $data,
                    'list' => $data['list'],
                ];

                break;
            case 'journey_popularity':
                $target = 'dashboard.parts.detailed.popularity';
                $data = $this->getTop(Experience::TYPE_JOURNEY, true);
                $activity = [
                    'average' => $data['primary'],
                    'list' => $data['list']
                ];
                break;
            case 'experiences_enrollments':
                $target = 'dashboard.parts.detailed.enrollments';
                $data = $this->getAssignationsEnrollments(Experience::TYPE_ADVENTURE, true);

                foreach ($data['list'] as $adventure) {
                    $adventure->started = 0;
                    $adventure->enrolled = 0;
                    $adventure->assigned = $this->assignedUsers($adventure);

                    $adventure->enrollments->each(function ($enrollment) use (&$adventure) {

                        switch ($enrollment->pivot->status) {
                            case Enrollment::ENROLLMENT_STATUS_ENROLLED:
                                $adventure->enrolled++;
                                break;
                            case Enrollment::ENROLLMENT_STATUS_STARTED:
                                $adventure->started++;
                                break;
                            default:
                                break;
                        }
                    });
                }

                $data['label'] = 'experiences';

                $activity = [
                    'enrollments' => $data,
                    'list' => $data['list'],
                ];
                break;
            case 'experiences_popularity':
                $target = 'dashboard.parts.detailed.popularity';
                $data = $this->getTop(Experience::TYPE_ADVENTURE, true);
                $activity = [
                    'average' => $data['primary'],
                    'list' => $data['list']
                ];
                break;
            case 'experiences-califications':
                $target = 'dashboard.parts.detailed.califications';
                $data = $this->getExperiencesRating();
                $activity = [
                    'rating' => $data['rating'],
                    'list' => $data['list']
                ];
                break;
            default:
                $target = 'dashboard.parts.index';

                break;
        }
        return ['target' => $target, 'activity' => $activity, 'filters' => $filters];
    }

    private function assignedUsers(Experience $experience)
    {
        $assigned = 0;

        if (Auth::user()->isSuper()) {
            $users = User::all();
        } else {
            $users = Auth::user()->company()->first()->users;
        }

        $users->each(function ($user) use (&$experience, &$assigned) {

            $assigned_experiences = $user->assignatedExperiences()->where(
                'experiences.id',
                'regexp',
                $experience->id
            )->get();

            if (count($assigned_experiences) > 0) {
                $assigned += count($assigned_experiences);
            }
        });

        return $assigned;
    }

    private function getExperiencesByEnrollment($time_period, $type = 'adventure')
    {
        if (count($time_period) > 0) {
            $start = new Carbon($time_period[0]);
            $end = new Carbon($time_period[1]);

            $licenses = ExperienceLicence::where('buyed_by', \Auth::user()->id)
                ->where(function ($q) {
                    $q->where('user_id', '<>', \Auth::user()->id)
                        ->orWhere('user_id', null);
                })
                ->whereBetween('created_at', [$start, $end])
                ->where('status', ExperienceLicence::STATUS_UNAVAILABLE)
                ->where('user_id', '<>', 'buyed_by')
                ->with(['experience', 'experience.author'])
                ->get(['id', 'experience_id']);
        } else {
            $licenses = ExperienceLicence::where('buyed_by', \Auth::user()->id)
                ->where(function ($q) {
                    $q->where('user_id', '<>', \Auth::user()->id)
                        ->orWhere('user_id', null);
                })
                ->where('status', ExperienceLicence::STATUS_UNAVAILABLE)
                ->where('user_id', '<>', 'buyed_by')
                ->with(['experience', 'experience.author'])
                ->get(['id', 'experience_id']);
        }

        $licenses->sortByDesc(function ($license) use ($type) {
            if ($license->experience->type === $type) {
                $license->experience->enroll_average = $license->experience->enrollments->count();
                unset($license->experience->enrollments);
                return $license->experience->enroll_average;
            }
        });

        $experiences = $licenses->pluck('experience')->where('type', $type);
        //dd($experiences->toArray());exit;
        return $experiences;
    }
}
