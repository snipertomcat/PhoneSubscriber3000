<?php

namespace Apithy\Http\Controllers;

use Apithy\App;

class AppsController extends Controller
{
    /**
     * UsersController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('active');
        $this->middleware('super');
    }

    /**
     * Show all applications consumer.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('apps.show', [
            'apps' => listing(App::class),
        ]);
    }

    /**
     * create application consumer.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('apps.create');
    }
}
