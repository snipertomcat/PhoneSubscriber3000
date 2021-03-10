<?php

namespace Apithy\Http\Controllers;

use Apithy\Company;
use Apithy\CompanyArea;
use Apithy\CompanyPosition;
use Apithy\Http\Responses\WebApiResponse;
use Apithy\Utilities\Controllers\Helpers;
use Apithy\Validators\AreaValidator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

/**
 * Class AreaController
 * @package Apithy\Http\Controllers
 */
class AreaController extends Controller
{
    use Helpers;

    /**
     * @var CompanyArea
     */
    protected $companyArea;

    /**
     * @var Company
     */
    protected $company;

    /**
     * @var CompanyPosition
     */
    protected $position;

    /**
     * CompanyArea constructor.
     *
     * AreaController constructor.
     * @param CompanyArea $companyArea
     * @param Company $company
     */
    public function __construct(CompanyArea $companyArea, Company $company, CompanyPosition $position)
    {
        $this->companyArea = $companyArea;
        $this->company = $company;
        $this->position = $position;
        $this->middleware('auth');
        $this->middleware('active');
        //$this->authorizeResource(CompanyArea::class);
    }

    /**
     *
     * @SWG\Get(
     *     path="/companies/{company_id}/areas",
     *     operationId="api.companies.areas.index",
     *     produces={"application/json"},
     *     tags={"areas"},
     *     security={
     *      {"passport": {}},
     *     },
     *     summary="Return a List of areas",
     *     @SWG\Parameter(
     *         name="company_id",
     *         in="path",
     *         description="The area's company_id to show",
     *         type="integer",
     *         required=true
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=404, description="Company not found")
     * )
     *
     * Display a listing of the resource.
     *
     * @param $company_id
     * @return WebApiResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index($company_id)
    {
        $company = Company::findOrFail($company_id);

        $this->authorize('fetch', [CompanyArea::class, $company]);

        $areas = $company->areas();

        return new WebApiResponse([
            'areas' => listing($areas),
            'company' => $company,
            'auth_user' => \Auth::user(),
        ], 'admin.companies.areas.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $company_id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create($company_id)
    {
        $company = Company::findOrFail($company_id);

        $this->authorize('create', [CompanyArea::class, $company]);

        $areas = CompanyArea::where('company_id', $company->id)->get();

        return view('admin.companies.areas.create', [
            'areas' => $areas,
            'company_area' => new CompanyArea(),
            'company' => $company,
            'auth_user' => \Auth::user(),
        ]);
    }

    /**
     *
     * @SWG\Post(
     *     path="/companies/{company_id}/areas/create",
     *     operationId="api.companies.areas.store",
     *     tags={"areas"},
     *     security={ {"passport":{}} },
     *     summary="Store area's data",
     *     @SWG\Parameter(
     *         name="company_id",
     *         in="path",
     *         description="The area's company_id to store",
     *         type="integer",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          description="Area's data to store",
     *          @SWG\Schema(
     *              ref="#/definitions/Area"
     *          )
     *     ),
     *     @SWG\Response(response=200, description="Successful store"),
     *     @SWG\Response(response=400, description="Invalid data supplied"),
     *     @SWG\Response(response=404, description="Company not found")
     * )
     *
     * Store a newly created resource in storage.
     *
     * @param $company_id
     * @param Request $request
     * @return WebApiResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store($company_id, Request $request)
    {
        $company = Company::findOrFail($company_id);

        $this->authorize('create', [CompanyArea::class, $company]);

        $this->validate($request, AreaValidator::storeRules());

        $request['parent_id'] = ($request['parent_id'] > -1) ? $request['parent_id'] : null;

        $area = $this->companyArea->create($request->all());

        $positions_array = new Collection();

        if (isset($request->positions)) {
            foreach ($request->positions as $index => $position) {
                $parent = (isset($position['parent_id'])) ? $position['parent_id'] : null;

                $position['parent_id'] = null;

                $pos = $this->position->create($position);

                $pos->area_id = $area->id;
                $pos->save();

                $positions_array->push($pos);

                if ($parent !== null) {
                    $parent_id_key = $positions_array->search(function ($position) use ($parent) {
                        return $position->name == $parent;
                    });

                    $pos->parent_id = $positions_array[$parent_id_key]->id;
                    $pos->save();
                }
            }
        }

        return new WebApiResponse(
            $area,
            false,
            WebApiResponse::RESPONSE_FOR_STORE,
            false,
            ['url_redirect' => route("areas.index", [$company])]
        );
    }

    /**
     *
     * @SWG\Get(
     *     path="/companies/{company_id}/areas/{area_id}",
     *     operationId="api.companies.areas.show",
     *     produces={"application/json"},
     *     tags={"areas"},
     *     security={
     *          {"passport": {}},
     *     },
     *     summary="Return the area's data",
     *     @SWG\Parameter(
     *         name="company_id",
     *         in="path",
     *         description="The area's company_id",
     *         type="integer",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         name="area_id",
     *         in="path",
     *         description="The area's id to show",
     *         type="integer",
     *         required=true
     *     ),
     *     @SWG\Response(response=200, description="successful operation"),
     *     @SWG\Response(response=404, description="Area not found")
     * )
     *
     * Display the specified resource.
     *
     * @param $company_id
     * @param $area_id
     * @return WebApiResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Company $company, $company_area)
    {
        $company_area = CompanyArea::findOrFail($company_area);
        $company_area->load(['positions']);
        $this->authorize('view', [CompanyArea::class, $company_area]);

        return new WebApiResponse(
            [
                'company_area' => $company_area,
                'company' => $company,
                'areas' => $company->areas,
                'positions' => $company_area->positions,
                'auth_user' => \Auth::user(),

            ],
            'admin.companies.areas.show'
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $company_id
     * @param $company_area_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Company $company, $company_area_id)
    {
        //$company = Company::findOrFail($company_id);

        $this->authorize('update', [CompanyArea::class, $company]);

        $areas = CompanyArea::where('company_id', $company->id)->get();

        $company_area = CompanyArea::findOrFail($company_area_id);

        return view('admin.companies.areas.edit', [
            'company_area' => $company_area,
            'company' => $company,
            'areas' => $areas,
            'positions' => $company_area->positions,
            'auth_user' => \Auth::user(),
        ]);
    }

    /**
     *
     * @SWG\Post(
     *     path="/companies/{company_id}/areas/{area_id}/update",
     *     operationId="api.companies.areas.update",
     *     tags={"areas"},
     *     security={ {"passport":{}} },
     *     summary="Update area's data",
     *     @SWG\Parameter(
     *         name="company_id",
     *         in="path",
     *         description="The area's company_id to store",
     *         type="integer",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         name="area_id",
     *         in="path",
     *         description="The area's id to update",
     *         type="integer",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          description="Area's data to update",
     *          @SWG\Schema(
     *              ref="#/definitions/Area"
     *          )
     *     ),
     *     @SWG\Response(response=200, description="Successful store"),
     *     @SWG\Response(response=400, description="Invalid data supplied"),
     *     @SWG\Response(response=404, description="Area not found")
     * )
     *
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $company_id
     * @param $company_area
     * @return WebApiResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, $company_id, $company_area)
    {
        $company = Company::findOrFail($company_id);

        $this->authorize('update', [CompanyArea::class, $company]);

        $area = CompanyArea::findOrFail($company_area);

        $this->validate($request, AreaValidator::storeRules($area));

        $area->fill($request->all());

        $area->saveOrFail();

        return new WebApiResponse(
            $area,
            false,
            WebApiResponse::RESPONSE_FOR_UPDATE,
            false,
            ['url_redirect' => route("areas.index", [$company, $area])]
        );
    }

    /**
     *
     * @SWG\Delete(
     *     path="/companies/{company_id}/areas/{area_id}/delete",
     *     operationId="api.companies.areas.destroy",
     *     tags={"areas"},
     *     security={
     *          {"passport": {}},
     *     },
     *     summary="Delete the area",
     *     @SWG\Parameter(
     *         name="company_id",
     *         in="path",
     *         description="The area's company_id to delete",
     *         type="integer",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         name="area_id",
     *         in="path",
     *         description="The area's id to delete",
     *         type="integer",
     *         required=true
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=404, description="Area not found")
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param $company_id
     * @param $company_area
     * @return WebApiResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($company_id, $company_area)
    {
        $company = Company::findOrFail($company_id);

        $area = CompanyArea::findOrFail($company_area);

        $this->authorize('delete', [CompanyArea::class, $area]);

        if (count($area->positions) > 0) {
            $area->positions->each(function ($position) {
                $position->users()->detach();
                $position->delete();
            });
        }

        $area->delete();

        return new WebApiResponse(
            $area,
            false,
            WebApiResponse::RESPONSE_FOR_DELETE,
            false,
            ['url_redirect' => route("areas.index", [$company])]
        );
    }
}
