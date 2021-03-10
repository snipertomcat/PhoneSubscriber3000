<?php

namespace Apithy\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{

    public function notfound()
    {
        return view('errors.404');
    }

    public function fatal()
    {
        return view('errors.500');
    }

    public function unauthorize()
    {
        return view('errors.unauthorize');
    }

    public function mobileRestriction()
    {
        return view('errors.mobile_restriction');
    }
}
