<?php

namespace Apithy\Http\Composers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\View\View;

/**
 * Class ApplicationComposer
 * @package Apithy\Http\Composers
 */
class ApplicationComposer
{
    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * AppComposer constructor.
     * @param \Illuminate\Contracts\Auth\Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     */
    public function compose(View $view)
    {
        $view->with('auth', $this->auth);
        $view->with('applicationName', config('app.name'));
    }
}
