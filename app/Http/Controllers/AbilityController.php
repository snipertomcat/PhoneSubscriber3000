<?php

namespace Apithy\Http\Controllers;

use Apithy\Ability;
use Illuminate\Http\Request;
use Apithy\Http\Responses\WebApiResponse;

class AbilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return WebApiResponse
     */
    public function index()
    {
        $abilities = Ability::class;

        return new WebApiResponse([
            'abilities' => listing($abilities)
        ], 'admin.abilities.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return WebApiResponse
     */
    public function store(Request $request)
    {
        $request['company_id'] = \Auth::user()->company[0]->id;
        $ability = Ability::create($request->all());
        return new WebApiResponse(
            $ability,
            false,
            WebApiResponse::RESPONSE_FOR_STORE,
            false,
            []
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apithy\Ability $ability
     * @return WebApiResponse
     */
    public function show(Ability $ability)
    {
        return new WebApiResponse(
            ['ability' => $ability],
            false
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Apithy\Ability  $ability
     * @return \Illuminate\Http\Response
     */
    public function edit(Ability $ability)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Apithy\Ability $ability
     * @return WebApiResponse
     * @throws \Throwable
     */
    public function update(Request $request, Ability $ability)
    {
        $ability->fill($request->all());
        $ability->saveOrFail();

        return new WebApiResponse(
            $ability,
            false,
            WebApiResponse::RESPONSE_FOR_UPDATE,
            false,
            []
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apithy\Ability $ability
     * @return WebApiResponse
     * @throws \Exception
     */
    public function destroy(Ability $ability)
    {
        $ability->delete();

        return new WebApiResponse(
            $ability,
            false,
            WebApiResponse::RESPONSE_FOR_DELETE,
            false,
            []
        );
    }
}
