<?php

namespace Apithy\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class AuthController
 * @package Apithy\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function me(Request $request)
    {
        return $request->user()
            ->load(['roles', 'school', 'level']);
    }
}
