<?php

namespace Apithy\Http\Controllers\Auth;

use Apithy\Http\Controllers\Controller;
use Apithy\SmsVerification;
use Apithy\User;
use Apithy\Utilities\Master\Master;
use BeyondCode\EmailConfirmation\Traits\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use NotificationChannels\Twilio\TwilioSmsMessage;

/**
 * Class ForgotPasswordController
 * @package Apithy\Http\Controllers\Auth
 */
class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validateEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email|exists:users']);
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param string $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse($response)
    {
        return Master::successResponse(trans($response));
        // return back()->with('status', trans($response));
    }

    public function resetBySms(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|numeric|min:10|exists:users,phone'
        ]);
        try {
            $user = User::where('phone', $request->input('email'))
                ->firstOrFail();

            // Clear
            $last_code = SmsVerification::where('user_id', $user->id)
                ->where('status', SmsVerification::VERIFICATION_PENDING)
                ->get()
                ->last();

            if (isset($last_code)) {
                $last_code->status = SmsVerification::VERIFICATION_CANCELED;
                $last_code->save();
            }


            if ($user->contact_preference == 'mail') {
                $user->contact_preference = 'sms';
            }

            $user->sendConfirmationMessage();

            return Master::successResponse([
                'id' => $user->id
            ]);
        } catch (\Exception $ex) {
            /*return Master::exceptionResponse(
                'forgot_password',
                ['e' => $ex->getMessage()]
            );*/
        }
        return Master::errorResponse('forgot_password', 'store');
    }
}
