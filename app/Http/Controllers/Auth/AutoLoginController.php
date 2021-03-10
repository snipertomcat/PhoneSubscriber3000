<?php

namespace Apithy\Http\Controllers\Auth;

use Apithy\Http\Controllers\Controller;
use Apithy\Services\AutoLoginService;
use Illuminate\Http\Request;

class AutoLoginController extends Controller
{
    private $autoLoginService;

    public function __construct(Request $request)
    {
        // $this->middleware('signed');
        $this->autoLoginService = new AutoLoginService();
    }

    /**
     * @param string $token
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function autoLogin(string $token)
    {
        return $this->autoLoginService->autoLogin($token);
    }
}
