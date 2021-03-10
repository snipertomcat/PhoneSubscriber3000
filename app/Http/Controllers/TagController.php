<?php

namespace Apithy\Http\Controllers;

use Apithy\Tag;
use Illuminate\Http\Request;
use Apithy\Http\Responses\WebApiResponse;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return WebApiResponse
     */
    public function index()
    {
        $user = \Auth::user();
        $tags = Tag::class;

        return new WebApiResponse([
            'tags' => listing($tags)
        ], 'admin.tags.index');
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
        $tag = Tag::create($request->all());
        return new WebApiResponse(
            $tag,
            false,
            WebApiResponse::RESPONSE_FOR_STORE,
            false,
            []
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apithy\Tag $tag
     * @return WebApiResponse
     */
    public function show(Tag $tag)
    {
        return new WebApiResponse(
            ['tag' => $tag],
            false
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Apithy\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Apithy\Tag $tag
     * @return WebApiResponse
     * @throws \Throwable
     */
    public function update(Request $request, Tag $tag)
    {
        $tag->fill($request->all());
        $tag->saveOrFail();

        return new WebApiResponse(
            $tag,
            false,
            WebApiResponse::RESPONSE_FOR_UPDATE,
            false,
            []
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apithy\Tag $tag
     * @return WebApiResponse
     * @throws \Exception
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return new WebApiResponse(
            $tag,
            false,
            WebApiResponse::RESPONSE_FOR_DELETE,
            false,
            []
        );
    }
}
