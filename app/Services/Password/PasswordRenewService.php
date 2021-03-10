<?php

namespace Apithy\Services\Password;

use Apithy\PasswordRenew;
use Apithy\User;
use Apithy\Utilities\Master\Master;
use Illuminate\Http\Request;

class PasswordRenewService
{
    //

    public static function setPasswordRequest(Request $request, PasswordRenew $passwordRenew)
    {
        try {
            \DB::transaction(function () use ($passwordRenew, $request, &$loginLink) {
                $user = User::findOrFail($passwordRenew->user_id);
                $user->status = User::STATUS_PASSWORD_ESTABLISHED;
                $user->password = bcrypt($request->input('password'));
                $user->saveOrFail();
                $loginLink = static::getLoginLink($user);
                $passwordRenew->delete();
            });
            return Master::successResponse(['login_link' => $loginLink]);
        } catch (\Exception $throwable) {
            if (Master::hasDebug()) {
                return Master::exceptionResponse($throwable, 'password_renew');
            }
        }
        return Master::errorResponse('password_renew');
    }

    public static function getLoginLink(User $user): string
    {
        $company = $user->company()->first();
        if ($company) {
            if ($company->account_name !== 'apithy') {
                return '/login';
            }
            return "/{$company->account_name}/login";
        }
        return '/login';
    }
}
