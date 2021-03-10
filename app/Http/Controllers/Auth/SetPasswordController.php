<?php

namespace Apithy\Http\Controllers\Auth;

use Apithy\Http\Controllers\Controller;
use Apithy\PasswordRenew;
use Apithy\Services\Password\PasswordRenewService;
use Illuminate\Http\Request;

class SetPasswordController extends Controller
{
    /**
     * @param Request $request
     * @param PasswordRenew $passwordRenew
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, PasswordRenew $passwordRenew)
    {
        return view('auth.passwords.set-password', ['token' => $passwordRenew->token]);
    }

    /**
     * @param Request $request
     * @param PasswordRenew $passwordRenew
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function renewPassword(Request $request, PasswordRenew $passwordRenew)
    {
        $this->validate($request, PasswordRenew::storeRules());
        return PasswordRenewService::setPasswordRequest($request, $passwordRenew);
    }
}
