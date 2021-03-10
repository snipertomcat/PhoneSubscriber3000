<?php

namespace Apithy\Http\Controllers;

use Apithy\Company;
use Apithy\CompanyPosition;
use Apithy\EnrollmentTracking;
use Apithy\Events\LicenseRemoved;
use Apithy\Exceptions\AlreadyRegisteredException;
use Apithy\Experience;
use Apithy\Http\Resources\Company\CompanyResource;
use Apithy\Http\Resources\Role\RoleResource;
use Apithy\Http\Resources\Taxonomy\TaxonomyResource;
use Apithy\Http\Resources\User\EditUserResource;
use Apithy\Http\Responses\WebApiResponse;
use Apithy\Role;
use Apithy\Services\RegisterService;
use Apithy\Services\StadisticService;
use Apithy\Services\TaxonomyService;
use Apithy\Services\UsersService;
use Apithy\User;
use Apithy\Utilities\Controllers\Helpers;
use Apithy\Utilities\Master\Master;
use Apithy\Validators\UserValidator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Twilio\TwiML\MessagingResponse;

/**
 * Class UsersController
 * @package Apithy\Http\Controllers\Admin
 */
class UsersController extends Controller
{
    use Helpers;

    /**
     * @var \Apithy\User
     */
    protected $user;

    /**
     * @var \Apithy\Role
     */
    protected $role;

    /**
     * @var \Apithy\Company
     */
    protected $company;

    protected $position;

    protected $stadistic_service;

    protected $user_service;

    private $taxonomy_service;

    /**
     * UsersController constructor.
     *
     * @param \Apithy\User $user
     * @param \Apithy\Role $role
     * @param \Apithy\Company $company
     * @param CompanyPosition $position
     * @param RegisterService $registerService
     * @param StadisticService $stadisticService
     * @param UsersService $usersService
     * @param Request $request
     */
    public function __construct(
        User $user,
        Role $role,
        Company $company,
        CompanyPosition $position,
        RegisterService $registerService,
        StadisticService $stadisticService,
        UsersService $usersService,
        Request $request
    ) {
        parent::__construct();
        $this->taxonomy_service = new TaxonomyService($request);
        $this->user = $user;
        $this->role = $role;
        $this->company = $company;
        $this->position = $position;
        $this->registerService = $registerService;
        $this->stadistic_service = $stadisticService;
        $this->user_service = $usersService;
        $this->middleware('auth', ['except' => ['demoFlyer']]);
        $this->middleware('active', ['except' => ['demoFlyer']]);
        // $this->authorizeResource(User::class);
    }

    /**
     *
     * @SWG\Get(
     *   path="/users",
     *   operationId="api.users.index",
     *   produces={"application/json"},
     *   tags={"users"},
     *   security={
     *     {"passport": {}},
     *   },
     *   summary="Return a List of Users",
     *   @SWG\Response(response=200, description="successful operation")
     * )
     *
     * Display a listing of the resource.
     *
     * @return WebApiResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */

    public function index(Request $request)
    {
        $this->authorize('fetch', User::class);

        $model = $this->user_service->getUsers($request);

        if ($request->headers->has('X-Requested-With')) {
            return response()->json(['users' => listing($model, [], $request->get('per_page'))], 200);
        }

        return new WebApiResponse([
            'users' => listing(User::with(['company', 'roles', 'position.area'])
                ->orderBy("created_at", "desc")->Allowed()->SystemVisible(), []),
            'auth_user' => \Auth::user(),
            'roles' => $this->role->orderBy('name', 'asc')->allowed()->get(),
            'statuses' => $this->user_service->getStatuses(),
            'taxonomy' => TaxonomyResource::collection($request->user()->company()->first()->taxonomy)
                ->groupBy('type')
                ->toArray(),
            'companies' => $this->company->orderBy('name', 'asc')->allowed()->get(),f
        ], 'admin.users.index');
    }

    /**
     *
     * @SWG\Get(
     *     path="/users/{user_id}",
     *     operationId="api.users.show",
     *     produces={"aplication/json"},
     *     tags={"users"},
     *     security={
     *          {"passport": {}},
     *     },
     *     summary="Return the user's data",
     *     @SWG\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="The user's id to show",
     *         type="integer",
     *         required=true
     *     ),
     *     @SWG\Response(response=200, description="successful operation"),
     *     @SWG\Response(response=404, description="user not found")
     * )
     *
     * Display the specified resource.
     *
     * @param  \Apithy\User $user
     * @return WebApiResponse
     */
    public function show(User $user)
    {
        return new WebApiResponse([
            'user' => $user,
            'roles' => $this->role->notSuper()->get(),
            'companies' => $this->company->with(['areasPositions'])->get(),
            'user_position' => $user->position
        ], 'admin.users.view');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param Request $request
     * @return void
     */

    public function create(Request $request)
    {
        $user = $request->user();

        $roles = $this->role->when(!$user->isSuper() && $user->isAdmin(), function ($query) {
            return $query->adminOptions();
        })
            ->notSuper()
            ->get();

        $valid_domains = [];
        $companies = [];

        if (isset($user->company[0])) {
            $company = $user->company[0]->id;
            if (isset($user->company[0]->valid_domains)) {
                $valid_domains = $user->company[0]->valid_domains;
            }
        } else {
            $company = Company::getDefautCompanyId();
        }

        if ($user->isSuper()) {
            $companies = CompanyResource::collection($this->company->orderBy('name', 'asc')->get());
        } else {
            $companies = $this->company->orderBy('name', 'asc')->allowed()->get();
        }

        return view('admin.users.create')->with([
            'is_super' => $user->isSuper(),
            'companies' => $companies,
            'current_company' => $company,
            'valid_domains' => $valid_domains,
            'roles' => RoleResource::collection($roles),
            'taxonomy_areas' => $this->taxonomy_service->getTaxonomyAreas(),
            'taxonomy_abilities' => $this->taxonomy_service->getTaxonomyAbility(),
            'taxonomy_teams' => $this->taxonomy_service->getTaxonomyTeams(),
            'taxonomy_positions' => $this->taxonomy_service->getTaxonomyPositions(),
            'taxonomy_certifications' => $this->taxonomy_service->getTaxonomyCertifications(),
            'taxonomy_customs' => $this->taxonomy_service->getTaxonomyCustom()
        ]);
    }

    /**
     *
     * @SWG\Post(
     *     path="/users",
     *     operationId="api.users.store",
     *     tags={"users"},
     *     security={
     *       {"passport": {}},
     *     },
     *     summary="Store an user",
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          description="User's data to store",
     *          @SWG\Schema(
     *              ref="#/definitions/User"
     *          )
     *     ),
     *     @SWG\Response(response=200, description="Successful store"),
     *     @SWG\Response(response=400, description="Invalid email supplied")
     * )
     *
     *
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $this->validate($request, UserValidator::registerRules('admin', $request));

        try {
            $data = $this->registerService->validateCompany($request->all());

            $user = $this->registerService->createUser($data);

            // todo desactivado (user active)
            // $user->status = User::STATUS_ACTIVE;
            $user->save();

            $notification = app(\Apithy\Notifications\WelcomeEmail::class);
            $user->notify($notification);

            event(new Registered($user));

            $this->taxonomy_service->saveUserTaxonomy($user->id);

            return Master::successResponse();
            // return new WebApiResponse($user, false, WebApiResponse::RESPONSE_FOR_STORE, 'users');
        } catch (WebserviceException | AlreadyRegisteredException $ex) {
            // flash($e->getMessage(), 'danger');
            //return redirect()->back()->withInput();
            return Master::errorResponse('users', 'create', '', 409, ['e' => $ex->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Apithy\User $user
     * @return WebApiResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(User $user)
    {
        $this->authorize('fetch', User::class);

        $roles = $this->role->when(!auth()->user()->isSuper() && $user->isAdmin(), function ($query) {
            return $query->adminOptions();
        })
            ->notSuper()
            ->get();

        $companies = [];

        if (auth()->user()->isSuper()) {
            $companies = CompanyResource::collection($this->company->orderBy('name', 'asc')->get());
            $company_id = $user->company()->first()->id;
            $this->taxonomy_service->setCompanyId($company_id);
        }

        return view('admin.users.edit')->with([
            'is_super' => auth()->user()->isSuper(),
            'companies' => $companies,
            'user' => EditUserResource::make($user),
            'roles' => RoleResource::collection($roles),
            'taxonomy_areas' => $this->taxonomy_service->getTaxonomyAreas(),
            'taxonomy_abilities' => $this->taxonomy_service->getTaxonomyAbility(),
            'taxonomy_teams' => $this->taxonomy_service->getTaxonomyTeams(),
            'taxonomy_positions' => $this->taxonomy_service->getTaxonomyPositions(),
            'taxonomy_certifications' => $this->taxonomy_service->getTaxonomyCertifications(),
            'taxonomy_customs' => $this->taxonomy_service->getTaxonomyCustom()
        ]);
    }

    /**
     *
     * @SWG\Put(
     *     path="/users/{user_id}",
     *     operationId="api.users.update",
     *     tags={"users"},
     *     security={
     *       {"passport": {}},
     *     },
     *     summary="Update an user",
     *     @SWG\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="The user's id",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          description="User's data to update",
     *          @SWG\Schema(
     *              ref="#/definitions/User"
     *          )
     *     ),
     *     @SWG\Response(response=200, description="Successful store"),
     *     @SWG\Response(response=400, description="Invalid email supplied"),
     *     @SWG\Response(response=404, description="User not found")
     * )
     *
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Apithy\User $user
     * @return EditUserResource|\Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(Request $request, User $user)
    {
        $auth = $request->user();

        $this->validate($request, UserValidator::adminUpdateRules($auth, $user));

        try {
            $user->fill($request->only(['name','personal_code', 'surname', 'gender', 'phone', 'birthday']));
            $user->saveOrFail();

            if ($auth->can('updateRole', $user) && $request->filled('role')) {
                $user->roles()->sync($request->input('role'));
            }

            if ($auth->can('updateCompany', $user) && $request->filled('company_id')) {
                $company = $this->company->findOrFail($request->get('company_id'));
                $user->companies()->sync($company->id);
            }

            $this->taxonomy_service->saveUserTaxonomy($user->id);
            return EditUserResource::make($user);
        } catch (\Exception $ex) {
            // return Master::exceptionResponse('users', ['e' => $ex->getMessage()]);
        }
        return Master::errorResponse('users', 'update');
    }

    /**
     *
     * @SWG\Delete(
     *     path="/users/{user_id}",
     *     operationId="api.users.delete",
     *     tags={"users"},
     *     security={
     *          {"passport": {}},
     *     },
     *     summary="Delete the user's",
     *     @SWG\Parameter(
     *         name="user_id",
     *         in="path",
     *         description="The user's id to delete",
     *         type="integer",
     *         required=true
     *     ),
     *     @SWG\Response(response=200, description="successful operation"),
     *     @SWG\Response(response=404, description="user not found")
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param \Apithy\User $user
     * @return WebApiResponse
     */
    public function destroy(Request $request, User $user)
    {
        $user->status = User::STATUS_DELETE;
        $user->save();
        $company_logo = Company::getDomainLogo($request);
        event(new LicenseRemoved($user->access, $company_logo, 'Invitacion eliminada'));
        return new WebApiResponse($user, false, WebApiResponse::RESPONSE_FOR_DELETE, 'users');
    }

    /**
     * Activate or inactivate user.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Apithy\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function activation(Request $request, User $user)
    {
        try {
            $this->validate($request, UserValidator::activationRules());
            $this->authorize('updateActive', $user);

            $active = boolval($request->get('active'));

            if ($user->active != $active) {
                $user->active = $active;
                $user->save();

                if ($user->active) {
                    $user->sendActivatedNotification();
                }
            }

            $data = collect($user->toArray())->only(['id', 'active']);
            return response()->json(['error' => false, 'user' => $data]);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }


    public function import(Request $request)
    {
        return view(
            'admin.users.import',
            [
                'companies' => $this->company->orderBy('name', 'asc')->allowed()->get(),
                'auth_user' => \Auth::user(),
            ]
        );
    }

    public function importJson(Request $request)
    {
        ini_set('memory_limit', '-1');
        $users = $request->input('users');
        $auth = $request->user();
        $company_id = Company::getDefautCompanyId();
        $message='';

        if ($auth->isSuper()) {
            $company_id = $request->input('company_id', Company::getDefautCompanyId());
        } else {
            if (!empty($auth->company)) {
                $company_id = $auth->company->first()->id;
            }
        }

        $errors = [];
        $user_created = 0;

        $taxonomyService = new TaxonomyService($request);

        $num_users = count($users);
        $code = 200;
        $header = '';

        for ($x = 0; $x < $num_users; $x++) {
            $validate = Validator::make($users[$x], UserValidator::registerRules('import', $request));

            if ($validate->fails()) {
                $errors[(isset($users[$x]['email'])) ? $users[$x]['email'] : $users[$x]['phone']] = $validate->errors();
            }

            $users[$x]['imported'] = false;

            if (!$validate->errors()->count()) {
                try {
                    $users[$x]['role_id'] = 7;
                    $users[$x]['access'] = (isset($users[$x]['email'])) ? $users[$x]['email'] : $users[$x]['phone'];
                    $users[$x]['register_type'] = 'import_file';
                    $users[$x]['company'] = $company_id;

                    // Set Role (ignore super admin)
                    if (isset($users[$x]['role'])) {
                        $role = Role::where('name', $users[$x]['role'])->first();
                        if (isset($role->id)) {
                            if (!$role->super) {
                                $users[$x]['role_id'] = $role->id;
                            }
                        }

                        unset($role);
                    }

                    // Set Area (create if not exists)
                    if (isset($users[$x]['job_area'])) {
                        $area = $taxonomyService->storeTaxonomy($users[$x]['job_area'], 'company_area');
                        $users[$x]['area'] = $area;
                        unset($area);
                    }

                    // Set Position (create if not exists)
                    if (isset($users[$x]['job_title'])) {
                        $position = $taxonomyService->storeTaxonomy($users[$x]['job_title'], 'company_position');
                        $users[$x]['position'] = $position;
                        unset($position);
                    }

                    // Set Tags (create if not exists)
                    if (isset($users[$x]['tags'])) {
                        $tags = explode(',', $users[$x]['tags']);
                        $tag_ids = [];
                        foreach ($tags as $kt => $tag) {
                            $tag_ids[] = $taxonomyService->storeTaxonomy($tag, 'tag');
                        }
                        $users[$x]['tag_ids'] = $tag_ids;
                        unset($tag_ids);
                    }

                    $user = $this->registerService->createUser($users[$x]);
                    $user_created++;
                    $users[$x]['imported'] = true;

                    $user->confirmed_at = time();
                    $user->status = User::STATUS_ACTIVE;

                    if (!$user->email) {
                        $user->contact_preference='sms';
                    } else {
                        $user->contact_preference='email';
                    }

                    $user->save();

                    if (!$user->email) {
                        $notification = app(\Apithy\Notifications\WelcomeSMS::class);
                        $user->notify($notification);
                    } else {
                        $notification = app(\Apithy\Notifications\WelcomeEmail::class);
                        $user->notify($notification);
                    }

                    event(new Registered($user));
                    unset($user);
                    unset($notification);
                    unset($users[$x]['position']);
                    unset($users[$x]['area']);
                    unset($users[$x]['tag_ids']);
                } catch (AlreadyRegisteredException $e) {
                    \Log::debug($e->getMessage());
                    continue;
                } catch (\Exception $ex) {
                    \Log::debug($ex->getMessage());
                    continue;
                }
            }

            $message = "{$user_created} Usuario(s) importados ";
            $header = 'Proceso finalizado';

            if (!empty($errors)) {
                $code = 422;
                $header .= ' con errores';
                $message .= '' . count($errors) . ' usuario(s) ignorados por datos invalídos';
            }
        }

        return response()->json([
            'countErrors' => count($errors),
            'countSave' => $user_created,
            'errors' => $errors,
            'message' => $message,
            'header' => $header,
            'data' => $users
        ], $code);
    }

    public function userActivity(Request $request)
    {
        $user = User::findOrFail($request['user_id']);
        $status = $request->get('status', null);
        $user_activity = new Collection();
        $all_experiences = new Collection();

        if (isset($user) && isset($request['experience_id'])) {
            $progress = $user->getExperienceProgress()->where('experience_id', $request['experience_id'])->get();
            $experience = Experience::findOrFail($request['experience_id']);

            return response()->json([
                'progress' => $progress->sortByDesc('updated_at'),
                'experience_graphs' => $this->user_service->experienceGraphs($user, $experience)
            ], 200);
        } else {
            if (isset($user)) {
                $all_experiences = $this->stadistic_service->getUserActivitiesFilter($user->id);

                $enrollments_progress = $user->getExperienceProgress()->get()->pluck('id');
                $tracking = EnrollmentTracking::with(['progress.enrollment'])
                    ->whereIn('enrollment_progress_id', $enrollments_progress)
                    ->orderBy('created_at', 'desc')
                    ->limit(50)// remover despues de aplicar el infinite scroll
                    ->get();


                foreach ($tracking as $a_index => $activity) {
                    $item = [
                        'created_at' => ($activity->created_at) ? $activity->created_at : 'No disponible',
                        'duration' => ($activity->duration) ? $activity->duration : 'No disponible',
                        'raw_score' => $activity->raw_score,
                        'verb' => ($activity->verb) ? $activity->verb : 'No disponible',
                        'session' => $activity->progress->session->title,
                        'experience' => $activity->progress->session->experience->title,
                        'experience_image' => $activity->progress->session->experience->full_path_cover,
                        'experience_link' => url(
                            '/experiences/' . $activity->progress->session->experience->system_id
                        )
                    ];

                    $user_activity->push($item);
                }
            }

            return response()->json([
                'activity' => $user_activity,
                'enrollments' => $user->enrollments,
                'experiences' => $all_experiences['experiences'],
                'journeys' => $all_experiences['journeys'],
                'experiences_status' => $this->stadistic_service->getEnrollmentsStatus(),
                'general_graphs' => $this->user_service->activitiesGraphs($user, $status)
            ], 200);
        }
        return response()->json([], 200);
    }

    public function getFilteredUsers(Request $request)
    {
        $userService = new UsersService();
        return $userService->getFilteredUsers($request);
    }

    public function createDemoUsers(Request $request)
    {
        ini_set('memory_limit', '-1');
        $users = $request->input('users');
        $auth = $request->user();
        $company_id = Company::getDefautCompanyId();
        $message='';

        if ($auth->isSuper()) {
            $company_id = $request->input('company_id', Company::getDefautCompanyId());
        } else {
            if (!empty($auth->company)) {
                $company_id = $auth->company->first()->id;
            }
        }

        $errors = [];
        $user_created = 0;


        $num_users = count($users);
        $code = 200;
        $header = '';

        for ($x = 0; $x < $num_users; $x++) {
            if (!$users[$x]['email']) {
                unset($users[$x]['email']);
            }

            if (!$users[$x]['phone']) {
                unset($users[$x]['phone']);
            }

            $validate = Validator::make($users[$x], UserValidator::registerRules('import'), $request);

            if ($validate->fails()) {
                $errors[(isset($users[$x]['email'])) ? $users[$x]['email'] : $users[$x]['phone']] = $validate->errors();
            }

            $users[$x]['imported'] = false;

            if (!$validate->errors()->count()) {
                try {
                    $users[$x]['role_id'] = 7;
                    $users[$x]['access'] = (isset($users[$x]['email'])) ? $users[$x]['email'] : $users[$x]['phone'];
                    $users[$x]['register_type'] = 'demo_invitation';
                    $users[$x]['company'] = $company_id;

                    // Set Role (ignore super admin)
                    if (isset($users[$x]['role'])) {
                        $role = Role::where('name', $users[$x]['role'])->first();
                        if (isset($role->id)) {
                            if (!$role->super) {
                                $users[$x]['role_id'] = $role->id;
                            }
                        }

                        unset($role);
                    }

                    // Set Area (create if not exists)

                    $user = $this->registerService->createUser($users[$x]);
                    $user_created++;
                    $users[$x]['imported'] = true;

                    $user->confirmed_at = time();
                    $user->status = User::STATUS_ACTIVE;
                    $user->save();

                    if (!$user->email) {
                        $notification = app(\Apithy\Notifications\WelcomeDemoSMS::class);
                        $user->notify($notification);
                    } else {
                        $notification = app(\Apithy\Notifications\WelcomeEmail::class);
                        $user->notify($notification);
                    }

                    event(new Registered($user));
                    unset($user);
                    unset($notification);
                    unset($users[$x]['position']);
                    unset($users[$x]['area']);
                    unset($users[$x]['tag_ids']);
                } catch (AlreadyRegisteredException $e) {
                    \Log::debug($e->getMessage());
                    continue;
                } catch (\Exception $ex) {
                    \Log::debug($ex->getMessage());
                    continue;
                }
            }

            $message = "{$user_created} Usuario(s) importados ";
            $header = 'Proceso finalizado';

            if (!empty($errors)) {
                $code = 422;
                $header .= ' con errores';
                $message .= '' . count($errors) . ' usuario(s) ignorados por datos invalídos';
            }
        }

        return response()->json([
            'countErrors' => count($errors),
            'countSave' => $user_created,
            'errors' => $errors,
            'message' => $message,
            'header' => $header,
            'data' => $users
        ], $code);
    }
}
