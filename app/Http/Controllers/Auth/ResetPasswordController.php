<?php

namespace Apithy\Http\Controllers\Auth;

use Apithy\Http\Controllers\Controller;
use Apithy\SmsVerification;
use Apithy\User;
use Apithy\Utilities\Master\Master;
use Apithy\Validators\UserValidator;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

/**
 * Class ResetPasswordController
 * @package Apithy\Http\Controllers\Auth
 */
class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/profile';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        $this->validate($request, UserValidator::resetPasswordRules());

        $response = $this->broker()->reset($this->credentials($request), function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($request, $response)
            : $this->sendResetFailedResponse($request, $response);
    }

    public function byPhone(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'user' => 'required|numeric',
            'password' => 'required|min:6|confirmed',
        ]);
        try {
            \DB::transaction(function () use ($request) {
                $token = SmsVerification::where('code', $request->input('token'))
                    ->where('user_id', $request->input('user'))
                    ->where('status', SmsVerification::VERIFICATION_VERIFIED)
                    ->firstOrFail();
                $user = User::findOrFail($token->user_id);
                $user->password = \Hash::make($request->input('password'));
                $user->remember_token = Str::random();
                $user->saveOrFail();
            });
            return Master::successResponse();
        } catch (\Exception $ex) {
            // return Master::exceptionResponse('reset_password', ['e' => $ex->getMessage()]);
        }
        return Master::errorResponse('reset_password', 'store');
    }
}
