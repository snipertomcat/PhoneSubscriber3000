<?php

namespace Apithy\Http\Controllers;

use Apithy\User;

/**
 * Class BlockedController
 * @package Apithy\Http\Controllers
 */
class BlockedController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function notActivated()
    {
        $user = request()->user();

        if ($user && $user->status == User::STATUS_ACTIVE) {
            return redirect('/profile');
        }
        return view('blocked.not-activated');
    }

    /**
     * Show the application settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function isBanned()
    {
        $user = request()->user();

        if ($user && !$user->banned) {
            return redirect('/profile');
        }

        return view('blocked.is-banned');
    }
}
