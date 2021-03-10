<?php

namespace Apithy\Http\Controllers;

use Apithy\Company;
use Apithy\CompanyBudget;
use Apithy\Country;
use Apithy\Http\Requests\Company\CompanyCoverRequest;
use Apithy\Http\Responses\WebApiResponse;
use Apithy\Role;
use Apithy\Services\CompanyService;
use Apithy\User;
use Apithy\Utilities\Controllers\Helpers;
use Apithy\Validators\CompanyValidator;
use Apithy\Services\ImageuploadS3Service;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{
    use Helpers;

    /**
     * @var \Apithy\Company
     */
    protected $company;

    /**
     * @var \Apithy\Country
     */
    protected $country;

    /**
     * @var \Apithy\Services\ImageuploadS3Service
     */
    protected $image_upload_service;

    private $companyService;

    /**
     * CompaniesController constructor.
     *
     * @param \Apithy\Company $company
     * @param \Apithy\Country $country
     */

    public function __construct(
        Company $company,
        Country $country,
        ImageuploadS3Service $imageuploadS3Service,
        Request $request
    ) {
        parent::__construct();

        $this->company = $company;
        $this->country = $country;
        $this->image_upload_service = $imageuploadS3Service;
        $this->companyService = new CompanyService($request);

        $this->middleware('auth');
        $this->middleware('active');
        // $this->authorizeResource(Company::class);
    }

    /**
     * @SWG\Get(
     *     path="/companies",
     *     operationId="api.companies.index",
     *     produces={"application/json"},
     *     tags={"companies"},
     *     security={
     *      {"passport": {}},
     *     },
     *     summary="Return a List of Companies",
     *     @SWG\Response(response=200, description="successful operation")
     * )
     *
     * Display a listing of the resource.
     *
     * @return WebApiResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */

    public function index(Request $r)
    {
        $this->authorize('fetch', Company::class);

        return new WebApiResponse([
            'companies' => listing(Company::class, ['country']),
            'countries' => $this->country->has('companies')->orderBy('name')->get(),
            'auth_user' => \Auth::user(),
        ], 'admin.companies.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.companies.create', [
            'company' => new Company(),
            'countries' => $this->country->orderBy('name')->get(),
            'auth_user' => \Auth::user(),
        ]);
    }

    /**
     * @SWG\Post(
     *     path="/companies",
     *     operationId="api.companies.store",
     *     tags={"companies"},
     *     security={ {"passport":{}} },
     *     summary="Store company's data",
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          description="Company's data to store",
     *          @SWG\Schema(
     *              ref="#/definitions/Company"
     *          )
     *     ),
     *     @SWG\Response(response=200, description="Successful store"),
     *     @SWG\Response(response=400, description="Invalid data supplied")
     * )
     *
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return WebApiResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, CompanyValidator::storeRules());
        $company = $this->company->create($request->all());

        return new WebApiResponse($company, false, WebApiResponse::RESPONSE_FOR_STORE, 'companies');
    }

    /**
     *
     * @SWG\Get(
     *     path="/companies/{company_id}",
     *     operationId="api.companies.show",
     *     produces={"application/json"},
     *     tags={"companies"},
     *     security={
     *          {"passport": {}},
     *     },
     *     summary="Return the company's data",
     *     @SWG\Parameter(
     *         name="company_id",
     *         in="path",
     *         description="The company's id to show",
     *         type="integer",
     *         required=true
     *     ),
     *     @SWG\Response(response=200, description="successful operation"),
     *     @SWG\Response(response=404, description="Company not found")
     * )
     *
     * Display the specified resource.
     *
     * @param  \Apithy\Company $company
     * @return WebApiResponse
     */
    public function show(Company $company)
    {
        return new WebApiResponse([
            'company' => $company,
            'countries' => Country::orderBy('name')->get(),
            'auth_user' => \Auth::user(),
        ], 'admin.companies.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Apithy\Company $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        return view('admin.companies.edit', [
            'company' => $company,
            'countries' => Country::orderBy('name')->get(),
            'auth_user' => \Auth::user(),
        ]);
    }

    /**
     *
     * @SWG\Put(
     *     path="/companies/{company_id}",
     *     operationId="api.companies.update",
     *     tags={"companies"},
     *     security={
     *       {"passport": {}},
     *     },
     *     summary="Update an company",
     *     @SWG\Parameter(
     *         name="company_id",
     *         in="path",
     *         description="The company's id",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          description="Company's data to update",
     *          @SWG\Schema(
     *              ref="#/definitions/Company"
     *          )
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Invalid data supplied"),
     *     @SWG\Response(response=404, description="Company not found")
     * )
     *
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Apithy\Company $company
     * @return WebApiResponse
     */
    public function update(Request $request, Company $company)
    {
        $this->validate($request, CompanyValidator::storeRules($company));

        $company->fill($request->all());

        $company->save();

        return new WebApiResponse($company, false, WebApiResponse::RESPONSE_FOR_UPDATE, 'companies');
    }

    /**
     *
     * @SWG\Delete(
     *     path="/companies/{company_id}",
     *     operationId="api.companies.destroy",
     *     tags={"companies"},
     *     security={
     *          {"passport": {}},
     *     },
     *     summary="Delete the company",
     *     @SWG\Parameter(
     *         name="company_id",
     *         in="path",
     *         description="The company's id to delete",
     *         type="integer",
     *         required=true
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=404, description="Company not found")
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param  \Apithy\Company $company
     * @return WebApiResponse
     * @throws \Exception
     */
    public function destroy(Company $company)
    {
        $company->delete();
        return new WebApiResponse($company, false, WebApiResponse::RESPONSE_FOR_DELETE, 'companies');
    }

    /**
     * @SWG\Post(
     *     path="/companues/{company}/logo",
     *     operationId="api.companies.logo.update",
     *     produces={"application/json"},
     *     tags={"companies"},
     *     security={ {"passport":{}} },
     *     summary="Update the company logo",
     *     @SWG\Parameter(
     *         name="company",
     *         in="path",
     *         type="number",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         description="file to upload",
     *         in="formData",
     *         name="logo",
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
     * @param  \Apithy\Experience $experience
     * @return JsonResponse
     */
    public function logo(Request $request, Company $company)
    {
        $path = $this->image_upload_service->handleImageUpload($request, $company, 'logo');

        if ($path) {
            $company->logo = $path;
            $company->save();

            return new JsonResponse(['src' => $company->full_path_logo]);
        }
    }

    public function cover(CompanyCoverRequest $request, company $company)
    {
        $path = $this->image_upload_service->handleImageUpload(
            $request,
            $company,
            'cover',
            $size = ['width'=> 1500, 'height'=> 1080]
        );

        if ($path) {
            $company->company_login_cover = $path;
            $company->save();

            return new JsonResponse(['src' => $company->full_path_cover]);
        }
        return response()->json(['e' => 'Error al cargar el cover'], 500);
    }

    public function users(Company $company)
    {
        return new WebApiResponse([
            'users' => listing(User::class, ['company', 'roles']),
            'roles' => Role::orderBy('name', 'asc')->allowed()->get(),
            'company' => $company,
            'auth_user' => \Auth::user(),
        ], 'admin.companies.users');
    }

    public function budgetForm(Request $request, Company $company)
    {
        return new WebApiResponse([
            'company' => $company->load(['areas']),
            'budgets' => $company->budgets()->with(['user'])->get(),
            'auth_user' => \Auth::user(),
        ], 'admin.companies.budget-form');
    }

    public function budgetStore(Request $request, Company $company)
    {
        //$this->validate($request, CompanyValidator::storeRules());
        $current_balance = $company->budget_balance += $request->get('amount');
        $request->merge([
            'current_balance' => $current_balance,
            'type' => 'global',
            'operation_type' => CompanyBudget::BUDGET_TYPE_IN
        ]);
        $companyBudget = CompanyBudget::create($request->all());

        $company->budget_balance = $current_balance;
        $company->save();

        return new WebApiResponse(
            $companyBudget,
            false,
            WebApiResponse::RESPONSE_FOR_STORE,
            'companies'
        );
    }

    public function updateAllowedDomain(Request $request)
    {
        // actualizar el rs de js
        $this->authorize('updateAllowedDomain', Company::class);
        $this->validate($request, CompanyValidator::allowedDomainValidator());
        return $this->companyService->updateAllowedDomain();
    }
}
