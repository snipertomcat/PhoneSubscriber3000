<?php

namespace Apithy\Http\Controllers;

use Apithy\Ability;
use Apithy\Certificates;
use Apithy\Company;
use Apithy\Enrollment;
use Apithy\Events\InvitationCreatedFromPhone;
use Apithy\Events\LicenseAssignation;
use Apithy\Experience;
use Apithy\ExperienceAssignation;
use Apithy\ExperienceDraft;
use Apithy\ExperienceLicence;
use Apithy\Http\Controllers\Controller as Controller;
use Apithy\Http\Resources\Taxonomy\TaxonomyResource;
use Apithy\Http\Responses\WebApiResponse;
use Apithy\Invitation;
use Apithy\Events\InvitationCreated;
use Apithy\Rating;
use Apithy\Role;
use Apithy\Services\EnrollmentService;
use Apithy\Services\ExperiencesService;
use Apithy\Services\ImageuploadS3Service;
use Apithy\Services\RegisterService;
use Apithy\Services\TaxonomyService;
use Apithy\Session;
use Apithy\SessionsFiles;
use Apithy\Tag;
use Apithy\Taxonomy;
use Apithy\User;
use Apithy\Utilities\Controllers\Helpers;
use Apithy\Utilities\Master\Master;
use Apithy\Validators\ExperienceValidator;
use Apithy\Validators\SessionValidator;
use Carbon\Carbon;
use Darryldecode\Cart\Cart;
use http\Env\Response;
use http\Exception\RuntimeException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\JsonResponse;
use PDF;

/**
 * Class ExperiencesController
 * @property Experience experience
 * @package Apithy\Http\Controllers
 */
class ExperiencesController extends Controller
{
    use Helpers;

    /**
     * @var \Apithy\Experience
     */
    protected $experience;

    /**
     * @var \Apithy\Ability
     */
    protected $ability;

    /**
     * @var \Apithy\Company
     */
    protected $company;

    /**
     * @var \Apithy\User
     */
    protected $user;


    /**
     * @var \Apithy\Services\experiencesService
     */
    protected $experience_service;

    /**
     * @var \Apithy\ExperienceAssignation
     */
    protected $experience_assignation;

    /**
     * @var \Apithy\Services\ImageuploadS3Service
     */
    protected $image_upload_service;

    protected $tags;

    private $taxonomy_service;

    /**
     * CompaniesController constructor.
     *
     * @param \Apithy\Experience $experience
     * @param Ability $ability
     * @param Company $company
     * @param User $user
     * @param ExperiencesService $experience_service
     * @param ExperienceAssignation $experience_assignation
     * @param ImageuploadS3Service $image_upload_service
     * @param Tag $tags
     * @param Request $request
     */

    public function __construct(
        Experience $experience,
        Ability $ability,
        Company $company,
        User $user,
        ExperiencesService $experience_service,
        ExperienceAssignation $experience_assignation,
        ImageuploadS3Service $image_upload_service,
        Tag $tags,
        Request $request
    ) {
        parent::__construct();
        $this->taxonomy_service = new TaxonomyService($request);
        $this->experience = $experience;
        $this->ability = $ability;
        $this->company = $company;
        $this->user = $user;
        $this->experience_service = $experience_service;
        $this->experience_assignation = $experience_assignation;
        $this->image_upload_service = $image_upload_service;
        $this->tags = $tags;

        $this->middleware('auth', ['except' => ['preview']]);
        $this->middleware('active', ['except' => ['preview']]);
        //$this->authorizeResource(experience::class);
    }

    /**
     * @SWG\Get(
     *   path="/experiences",
     *   operationId="api.experiences.index",
     *   produces={"application/json"},
     *   tags={"experiences"},
     *   security={ {"passport": {}}, },
     *   summary="Return a list of experiences",
     *   @SWG\Response(response=200, description="Successful operation")
     * )
     *
     * Display a listing of the resource.
     *
     * @return WebApiResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        //$this->authorize('fetch', experience::class);
        $user = Auth::user()->load('enrollments');
        //$users = $this->user->orderBy('id')->allowed()->get();

        $template = $this->experience_service->getTemplateForRole('experiences.index');

        if ($user->isAdmin()) {
            return new WebApiResponse([
                'experiences' => listing(Experience::with([
                    'author.company',
                    'sessions',
                    //'enrollments',
                ])->orderBy("id", "desc")->StoreOnly()->SystemVisible(), [], -1),
                'abilities' => new Collection([]),
                'partners' => new Collection([]), // $users,
                'authors' => new Collection([]), // $users,
                'user' => $user,
                'types' => [
                    ['title' => trans('Adventure'), 'value' => Experience::TYPE_ADVENTURE],
                    ['title' => trans('Journey'), 'value' => Experience::TYPE_JOURNEY]
                ]
            ], $template);
        }

        return new WebApiResponse([
            'experiences' => listing(Experience::with([
                'author.company',
                'sessions',
                //'enrollments',
            ])->orderBy("id", "desc")->Allowed()->SystemVisible(), [], -1),
            'abilities' => new Collection([]),
            'partners' => new Collection([]), // $users,
            'authors' => new Collection([]), // $users,
            'user' => $user,
            'types' => [
                ['title' => trans('Adventure'), 'value' => Experience::TYPE_ADVENTURE],
                ['title' => trans('Journey'), 'value' => Experience::TYPE_JOURNEY]
            ]
        ], $template);
    }

    /**
     * @SWG\Get(
     *   path="/experiences",
     *   operationId="api.experiences.index",
     *   produces={"application/json"},
     *   tags={"experiences"},
     *   security={ {"passport": {}}, },
     *   summary="Return a list of experiences",
     *   @SWG\Response(response=200, description="Successful operation")
     * )
     *
     * Display a listing of the resource.
     *
     * @return WebApiResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function company()
    {
        $user = Auth::user()->load('enrollments');
        //$users = $this->user->orderBy('id')->allowed()->get();

        $template = $this->experience_service->getTemplateForRole('experiences.index');

        return new WebApiResponse([
            'experiences' => listing(Experience::with([
                'author.company',
                'sessions',
                //'enrollments',
            ])->orderBy("id", "desc")->CompanyOnly()->SystemVisible(), [], -1),
            'abilities' => new Collection([]),
            'partners' => new Collection([]), // $users,
            'authors' => new Collection([]), // $users,
            'user' => $user,
            'types' => [
                ['title' => trans('Adventure'), 'value' => Experience::TYPE_ADVENTURE],
                ['title' => trans('Journey'), 'value' => Experience::TYPE_JOURNEY]
            ]
        ], $template);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function create(Request $request)
    {
        $this->authorize('create', experience::class);

        $experience_draft = $this->experience_service->createExperienceDraft();

        return view('admin.experiences.create', [
            'experience' => $experience_draft,
        ]);
    }

    /**
     * @SWG\Post(
     *     path="/experiences/store",
     *     operationId="api.experiences.store",
     *     produces={"application/json"},
     *     tags={"experiences"},
     *     security={ {"passport":{}} },
     *     summary="Store a experience",
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         description="Experience to store",
     *         @SWG\Schema(
     *             ref="#/definitions/Experience"
     *         )
     *     ),
     *     @SWG\Response(response=200, description="Successful store"),
     *     @SWG\Response(response=400, description="Invalid data supplied")
     * )
     *
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return WebApiResponse
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $this->validate($request, ExperienceValidator::storeRules());
        $experience = $this->experience_service->createExperience($request);

        return new WebApiResponse($experience, false, WebApiResponse::RESPONSE_FOR_STORE, 'experiences');
    }

    /**
     * @SWG\Get(
     *     path="/experiences/{experience}",
     *     operationId="api.experiences.show",
     *     produces={"application/json"},
     *     tags={"experiences"},
     *     security={ {"passport":{}} },
     *     summary="Return the experience's data",
     *     @SWG\Parameter(
     *         name="experience",
     *         in="path",
     *         type="number",
     *         required=true
     *     ),
     *     @SWG\Response(response=200, description="successful operation"),
     *     @SWG\Response(response=404, description="Position not found")
     * )
     *
     * Display the specified resource.
     *
     * @param \Apithy\Experience $experience
     * @return WebApiResponse
     * @throws \Exception
     */
    public function show(Experience $experience, $enrollment_id = null)
    {

        $companies_data = $this->experience_service->prepareCompaniesData($this->company);
        $users_data = $this->experience_service->prepareUserData($this->user);
        $template = $this->experience_service->getTemplateForRole('experiences.show');

        $user = Auth::user();

        if (isset($enrollment_id)) {
            $enrollment_progress = Enrollment::find($enrollment_id);
        } else {
            $enrollment_progress = $user->experienceEnrollments($experience)->first();
        }

        $experience_assignations = $experience->assignations()
            ->where('user_id', $user->id)
            ->get();

        if ($experience->type === Experience::TYPE_JOURNEY) {
            $experience->load(['abilities', 'tags', 'adventures', 'adventures.sessions', 'adventures.abilities']);

            foreach ($experience->adventures as &$adventure) {
                $enrollment = $user->experienceEnrollments($adventure, false, 'inherit')->first();
                $adventure->enrollment_id = $enrollment->id;
            }
        } else {
            $experience->load(['abilities', 'sessions', 'tags']);
            if ($enrollment_progress) {
                $current_progress = $enrollment_progress->progress()->with(['tracking'])->get();
                if ($current_progress) {
                    foreach ($experience->sessions as $index => $session) {
                        $current_enrollment_progress = $current_progress
                            ->where('session_id', $session->id)
                            ->first()
                            ->toArray();

                        $current_enrollment_progress['tracking'] = $current_progress
                            ->where('session_id', $session->id)
                            ->first()
                            ->tracking
                            ->toArray();

                        $current_enrollment_progress['stats'] = $current_progress
                            ->where('session_id', $session->id)
                            ->first()
                            ->getGlobalStats();

                        switch ($current_enrollment_progress['status']) {
                            case EnrollmentService::SESSION_STATUS_BLOCKED:
                                $current_enrollment_progress['status_text'] = "Bloqueado";
                                $current_enrollment_progress['status_text_color'] = "gray";
                                break;
                            case EnrollmentService::SESSION_STATUS_AVAILABLE:
                                $current_enrollment_progress['status_text'] = "Disponible";
                                $current_enrollment_progress['status_text_color'] = "purple";
                                break;
                            case EnrollmentService::SESSION_STATUS_IN_PROGRESS:
                                $current_enrollment_progress['status_text'] = "En progreso";
                                $current_enrollment_progress['status_text_color'] = "blue";
                                break;
                            case EnrollmentService::SESSION_STATUS_FINISHED:
                                $current_enrollment_progress['status_text'] = "Completado";
                                $current_enrollment_progress['status_text_color'] = "green";
                                break;

                            case EnrollmentService::SESSION_STATUS_RETRY:
                                $current_enrollment_progress['status_text'] = "orange";
                                $current_enrollment_progress['status_text_color'] = "red";
                                break;

                            case EnrollmentService::SESSION_STATUS_NOT_ANSWERED:
                                $current_enrollment_progress['status_text'] = "Respuestas no enviadas";
                                $current_enrollment_progress['status_text_color'] = "yellow";
                                break;
                        }

                        $experience->sessions[$index]->current_enrollment_progress = $current_enrollment_progress;
                    }
                }
            }
        }

        return new WebApiResponse([
            'experience' => $experience,
            'enrollments' => $experience->enrollments(true)->get(),
            'companies' => $companies_data,
            'users' => $users_data,
            'user' => $user->load(['enrollments']),
            'assignations' => $experience_assignations
        ], $template);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Apithy\Experience $experience
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function edit(Experience $experience)
    {

        $companies_data = $this->experience_service->prepareCompaniesData($this->company);
        //$users_data = $this->experience_service->prepareUserData($this->user);
        $user = Auth::user();

        $route_edit = $this->experience_service->getEditRoute($experience);


        return view('admin.experiences.edit', [
            'experience' => $experience->load(['taxonomyAbility', 'taxonomyTags', 'companies', 'adventures']),
            'adventures' => Experience::where('type', '=', 'adventure')->SystemVisible()->orderBy('title')->get(),
            'abilities' => $this->taxonomy_service->getTaxonomyAbility(),
            'companies' => $companies_data,
            'partners' => new Collection([]), //$this->user->orderBy('name')->get(),
            'authors' => $this->user->roleIn('9')->orderBy('name')->get(),
            'users' => new Collection([]),//$users_data,
            'user' => $user,
            'route_edit' => $route_edit,
            'all_tags' => $this->taxonomy_service->getTaxonomyTags()
        ]);
    }

    /**
     * @SWG\Post(
     *     path="/experiences/{experience}/cover",
     *     operationId="api.experiences.cover.update",
     *     produces={"application/json"},
     *     tags={"experiences"},
     *     security={ {"passport":{}} },
     *     summary="Update the experience cover",
     *     @SWG\Parameter(
     *         name="experience",
     *         in="path",
     *         type="number",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         description="file to upload",
     *         in="formData",
     *         name="cover",
     *         required=false,
     *         type="file"
     *     ),
     *     @SWG\Response(response=200, description="Successful store"),
     *     @SWG\Response(response=400, description="Invalid data supplied"),
     *     @SWG\Response(response=404, description="Experience not found")
     * )
     *
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param \Apithy\Experience $experience
     * @return JsonResponse
     */

    public function cover(Request $request, Experience $experience)
    {
        $file = $request->file('cover');

        if ($file) {
            $original = $file->getFileInfo()->getPathname();

            /* @var \Intervention\Image\Image $image */
            $image = Image::make($original);

            //$image->fit(880, 640);

            $image->save($original);

            $extension = $file->extension();

            $name = sprintf('%s.%s', $experience->id . "-" . time(), $extension);

            $path = $file->storeAs('experience_covers', $name, ['visibility' => 'public']);

            if ($path) {
                $experience->cover = $path;
                $experience->save();

                return new JsonResponse(['src' => $experience->full_path_cover]);
            }
        }
    }

    /**
     * @SWG\Post(
     *     path="/experiences/{experience}/cover-top",
     *     operationId="api.experiences.cover_top.update",
     *     produces={"application/json"},
     *     tags={"experiences"},
     *     security={ {"passport":{}} },
     *     summary="Update the experience cover top",
     *     @SWG\Parameter(
     *         name="experience",
     *         in="path",
     *         type="number",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         description="file to upload",
     *         in="formData",
     *         name="cover",
     *         required=false,
     *         type="file"
     *     ),
     *     @SWG\Response(response=200, description="Successful store"),
     *     @SWG\Response(response=400, description="Invalid data supplied"),
     *     @SWG\Response(response=404, description="Experience not found")
     * )
     *
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param \Apithy\Experience $experience
     * @return JsonResponse
     */

    public function coverTop(Request $request, Experience $experience)
    {
        $file = $request->file('cover_top');

        if ($file) {
            $original = $file->getFileInfo()->getPathname();

            /* @var \Intervention\Image\Image $image */
            $image = Image::make($original);

            //$image->fit(1500, 500);

            $image->save($original);

            $extension = $file->extension();

            $name = sprintf('%s.%s', $experience->id . "-" . time(), $extension);

            $path = $file->storeAs('experience_covers_top', $name, ['visibility' => 'public']);

            if ($path) {
                $experience->cover_top = $path;
                $experience->save();

                return new JsonResponse(['src' => $experience->full_path_cover_top]);
            }
        }
    }

    /**
     * @SWG\Put(
     *     path="/experiences/{experience}",
     *     operationId="api.experiences.update",
     *     tags={"experiences"},
     *     security={ {"passport":{}} },
     *     summary="Update a experience",
     *     @SWG\Parameter(
     *         name="experience",
     *         in="path",
     *         type="number",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @SWG\Schema(
     *             ref="#/definitions/Experience"
     *         )
     *     ),
     *     @SWG\Response(response=200, description="Successful store"),
     *     @SWG\Response(response=400, description="Invalid data supplied"),
     *     @SWG\Response(response=404, description="Experience not found")
     * )
     *
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Apithy\Experience $experience
     * @return WebApiResponse
     * @throws \Exception
     */
    public function update(Request $request, Experience $experience)
    {
        $this->validate($request, ExperienceValidator::updateRules($experience));

        $experience = $this->experience_service->updateExperience($request, $experience);

        return new WebApiResponse(
            $experience,
            false,
            WebApiResponse::RESPONSE_FOR_UPDATE,
            'experiences'
        );
    }

    /**
     * @SWG\Post(
     *     path="/experiences/{experience}",
     *     operationId="api.experiences.update",
     *     tags={"experiences"},
     *     security={ {"passport":{}} },
     *     summary="Update a experience",
     *     @SWG\Parameter(
     *         name="experience",
     *         in="path",
     *         type="number",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @SWG\Schema(
     *             ref="#/definitions/Experience"
     *         )
     *     ),
     *     @SWG\Response(response=200, description="Successful store"),
     *     @SWG\Response(response=400, description="Invalid data supplied"),
     *     @SWG\Response(response=404, description="Experience not found")
     * )
     *
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Apithy\Experience $experience
     * @return WebApiResponse
     * @throws \Exception
     */
    public function updateCompanySettings(Request $request, Experience $experience, Company $company)
    {
        //$this->validate($request, ExperienceValidator::updateRules($experience));
        $experience = $this->experience_service->updateCompanySettings($request, $experience, $company);

        return new WebApiResponse(
            $experience,
            false,
            WebApiResponse::RESPONSE_FOR_UPDATE,
            'experiences'
        );
    }

    /**
     * @SWG\Delete(
     *     path="/experiences/{experience}",
     *     operationId="api.experiences.destroy",
     *     tags={"experiences"},
     *     security={
     *          {"passport": {}},
     *     },
     *     summary="Delete the experience",
     *     @SWG\Parameter(
     *         name="experience",
     *         in="path",
     *         type="number",
     *         required=true
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=404, description="Position not found")
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param \Apithy\Experience $experience
     * @return WebApiResponse
     * @throws \Exception
     */
    public function destroy(Experience $experience)
    {
        if (count($experience->abilities) > 0) {
            foreach ($experience->abilities as $index => $ability) {
                $experience->abilities()->detach($ability->id);
            }
        }

        if (count($experience->tags) > 0) {
            foreach ($experience->tags as $index => $tag) {
                $experience->tags()->detach($tag->id);
            }
        }

        $experience->delete();

        return new WebApiResponse(
            $experience,
            false,
            WebApiResponse::RESPONSE_FOR_DELETE,
            'experiences',
            ['use_hash_id' => true]
        );
    }

    /**
     * @SWG\Get(
     *     path="/experiences/{experience}/set-status/{status}",
     *     operationId="api.experiences.update.status",
     *     produces={"application/json"},
     *     tags={"experiences"},
     *     security={ {"passport":{}} },
     *     summary="Update the experience's status",
     *     @SWG\Parameter(
     *         name="experience",
     *         in="path",
     *         type="number",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         name="status",
     *         in="path",
     *         type="number",
     *         required=true
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=404, description="Experience not found")
     * )
     *
     * Update the status of the specified resource in storage.
     *
     * @param \Apithy\Experience $experience
     * @param $status
     * @return WebApiResponse
     */
    public function updateStatus(Experience $experience, $status)
    {
        $experience->status = $status;
        $experience->save();

        return new WebApiResponse(
            $experience,
            false,
            WebApiResponse::RESPONSE_FOR_STORE,
            'experiences',
            ['use_hash_id' => true]
        );
    }

    /**
     * @SWG\Put(
     *     path="/experiences/{experience}/adventures",
     *     operationId="api.experiences.adventures",
     *     produces={"application/json"},
     *     tags={"experiences"},
     *     security={ {"passport":{}} },
     *     summary="Return the adventures in a experience",
     *     @SWG\Parameter(
     *         name="experience",
     *         in="path",
     *         type="number",
     *         required=true
     *     ),
     *     @SWG\Response(response=200, description="successful operation"),
     *     @SWG\Response(response=404, description="Position not found")
     * )
     *
     * Display the specified resource.
     *
     * @param \Apithy\Experience $experience
     * @return WebApiResponse
     */
    public function updateAdventures(Experience $experience, Request $request)
    {
        $new_adventures_tree = json_decode($request->get("adventures"), true);

        $template = $this->experience_service->getTemplateForRole('experiences.adventures');
        $adventures_tree = $this->experience_service->updateExperienceTree($experience, $new_adventures_tree);

        return new WebApiResponse([
            'adventures' => $experience->adventures,
            'experience' => $experience,
            'adventures_tree' => $adventures_tree
        ], $template);
    }

    /**
     * @SWG\Get(
     *     path="/experiences/{experience}/adventures",
     *     operationId="api.experiences.adventures",
     *     produces={"application/json"},
     *     tags={"experiences"},
     *     security={ {"passport":{}} },
     *     summary="Return the adventures in a experience",
     *     @SWG\Parameter(
     *         name="experience",
     *         in="path",
     *         type="number",
     *         required=true
     *     ),
     *     @SWG\Response(response=200, description="successful operation"),
     *     @SWG\Response(response=404, description="Position not found")
     * )
     *
     * Display the specified resource.
     *
     * @param \Apithy\Experience $experience
     * @return WebApiResponse
     * @throws \Exception
     */
    public function adventures(Experience $experience)
    {

        $template = $this->experience_service->getTemplateForRole('experiences.adventures');
        $adventures_tree = $this->experience_service->prepareExperienceTree($experience);

        return new WebApiResponse([
            'adventures' => $experience->adventures(),
            'experience' => $experience,
            'adventures_tree' => $adventures_tree
        ], $template);
    }

    /**
     * Update the order of the sessions resource in storage.
     *
     * @param \Apithy\Experience $experience
     * @param \Illuminate\Http\Request $request
     * @return WebApiResponse
     */
    public function sortSessions(Experience $experience, Request $request)
    {
        $sessions_list = $request->get('sessions_list', []);
        foreach ($sessions_list as $weight => $session_id) {
            DB::table('experience_sessions')
                ->where('id', $session_id)
                ->update(['weight' => $weight]);
        }

        return new WebApiResponse(
            $experience,
            false,
            WebApiResponse::RESPONSE_FOR_STORE,
            'experiences',
            ['use_hash_id' => true]
        );
    }
}
