<?php

namespace Apithy\Http\Controllers\Auth;

use Apithy\Exceptions\AlreadyRegisteredException;
use Apithy\Exceptions\UninvitedException;
use Apithy\Exceptions\WebserviceException;
use Apithy\ExperienceLicence;
use Apithy\Http\Controllers\Controller;
use Apithy\Http\Responses\WebApiResponse;
use Apithy\Invitation;
use Apithy\Services\RegisterService;
use Apithy\SmsVerification;
use Apithy\User;
use Apithy\Validators\CompanyValidator;
use Apithy\Validators\UserValidator;
use BeyondCode\EmailConfirmation\Events\Confirmed;
use BeyondCode\EmailConfirmation\Traits\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * Class RegisterController
 * @package Apithy\Http\Controllers\Auth
 */
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/profile';

    /**
     * @var RegisterService
     */
    protected $registerService;

    /**
     * Create a new controller instance.
     *
     * @param RegisterService $registerService
     */
    public function __construct(RegisterService $registerService)
    {
        $this->middleware('company.check')->only('showRegistrationForm');
        $this->registerService = $registerService;
    }


    /**
     * Show the application registration form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function showRegistrationForm(Request $request)
    {

        $invitation_code = $request->input('invitation_code');
        $invitation = Invitation::where('code', $invitation_code)->first();

        if (!$invitation) {
            return view('website.invalid_register_link');
        }

        if ($invitation->status == Invitation::INVITATION_ACCEPTED) {
            return view('website.invalid_register_link');
        }

        if ($request->session()->has('current.company') && $invitation) {
            return view('auth.company-register')
                ->with([
                    'company' => $request->session()->get('current.company'),
                    'invitation' => $invitation
                ]);
        }

        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $type = $this->registrationType($request);

        if (!$type) {
            flash(trans('messages.invalid_registration_type'), 'danger');
            return redirect()->back()->withInput();
        }

        if ($request->has('company_id')) {
            $this->validator($request->all(), 'on_site_company', $request)->validate();
        } else {
            $this->validator($request->all(), $type, $request)->validate();
        }

        if ($request->get('company_info', false)) {
            $this->validate($request, CompanyValidator::fromUserRegister());
        }

        try {
            $user = $this->create($request->all());
            $user->confirmation_code = Str::random(25);
            $user->save();
            $user->sendConfirmationMessage();

            event(new Registered($user));
            //$this->guard()->login($user);


            $licences = ExperienceLicence::where('email', $user->access)->get();
            if (isset($licences) && count($licences)) {
                $licences->each(function ($licence) use ($user) {
                    $licence->user_id = $user->id;
                    $licence->status = ExperienceLicence::STATUS_UNAVAILABLE;
                    $licence->save();
                });
            }

            if ($user->registrationMethodIs(User::REGISTRATION_ON_SITE) ||
                $user->registrationMethodIs(User::REGISTRATION_INVITATION)) {
                $url_redirect = route("login");
                $url_redirect_param = [
                    'key' => 'confirmation',
                    'value' => __('confirmation::confirmation.confirmation_info')
                ];
                return new WebApiResponse(
                    $user,
                    false,
                    WebApiResponse::RESPONSE_FOR_STORE,
                    'users',
                    ['url_redirect' => $url_redirect, 'url_redirect_with_params' => $url_redirect_param]
                );
            }

            return $this->registered($request, $user)
                ?: redirect($this->redirectAfterRegistrationPath())
                    ->with('confirmation', __('confirmation::confirmation.confirmation_info'));
        } catch (WebserviceException | UninvitedException | AlreadyRegisteredException $e) {
            flash($e->getMessage(), 'danger');

            return redirect()->back()->withInput();
        }
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @param string $type
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, $type, Request $request)
    {
        return Validator::make($data, UserValidator::registerRules($type, $request));
    }

    /**
     * Get the registration type is valid.
     *
     * @param Request $request
     * @return string|null
     */
    protected function registrationType(Request $request)
    {
        $input = $request->get('register_type', 'on_site');
        return in_array($input, UserValidator::registerTypes()) ? $input : null;
    }

    /**
     * Creates new user.
     *
     * @param array $data
     * @return User
     * @throws \Exception
     */
    protected function create(array $data)
    {
        $user = null;
        switch ($data['register_type']) {
            case 'lms':
                $user = $this->registerService->createUser($data);
                break;
            case 'on_site':
                $user = $this->registerService->createUser($data);
                break;
            case 'admin':
                $user = $this->registerService->updateOrCreateLMSUser($data['user'], $data['password']);
                break;

            default:
                $user = $this->registerService->createInvitationUser($data);
                break;
        }
        return $user;
    }

    /**
     * Confirm a user with a given confirmation code.
     *
     * @param $confirmation_code
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm($confirmation_code)
    {
        $model = $this->guard()->getProvider()->createModel();

        $user = $model->where('confirmation_code', $confirmation_code)->first();

        if (!$user) {
            return view('website.invalid_confirmation_link');
        }

        $user->confirmation_code = null;
        $user->confirmed_at = now();
        $user->accepted_privacy_terms_at = now();
        $user->accepted_service_clients_conditions_at = now();
        $user->status = User::STATUS_ACTIVE;
        $user->save();

        event(new Confirmed($user));

        return $this->confirmed($user)
            ?: redirect($this->redirectAfterConfirmationPath())
                ->with('confirmation', __('confirmation::confirmation.confirmation_successful'));
    }

    /**
     * Resend a confirmation code to a user.
     *
     * @param $confirmation_code
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resendConfirmationFromRegister($confirmation_code)
    {
        $model = $this->guard()->getProvider()->createModel();

        $user = $model->where('confirmation_code', $confirmation_code)->firstOrFail();
        $this->sendConfirmationToUser($user);

        return redirect($this->redirectAfterResendConfirmationPath())
            ->with('confirmation', __('confirmation::confirmation.confirmation_resent'));
    }

    public function resendConfirmation(Request $request)
    {
        $session_confirmation_user_id = $request->session()->get('confirmation_user_id');
        $confirmation_user_id = (isset($session_confirmation_user_id))
            ? $session_confirmation_user_id
            : $request->get('confirmation_user_id');

        $user = User::findOrFail($confirmation_user_id);
        //$user = User::findOrFail($request->session()->get('confirmation_user_id'));
        if (isset($user)) {
            if ($user->contact_preference === User::CONTACT_BY_EMAIL) {
                try {
                    $user->save();
                    $user->sendConfirmationMessage();
                    return redirect(route('login'))
                        ->with('confirmation', __('confirmation::confirmation.confirmation_resent'));
                } catch (\Exception $e) {
                    //
                }
            } else {
                $verification = SmsVerification::where('user_id', $user->id)->get()->last();

                if (isset($verification) && $verification->status === SmsVerification::VERIFICATION_PENDING) {
                    try {
                        $user->sendConfirmationMessage();
                        $verification->status = SmsVerification::VERIFICATION_CANCELED;
                        $verification->save();
                        return redirect(route('login'))
                            ->with('confirmation', __('confirmation::confirmation.confirmation_resent'));
                    } catch (\Exception $e) {
                        //
                    }
                }
            }
        }
    }

    public function confirmPhone(Request $request, $code)
    {

        $msj = "Número de teléfono o código inválido.";
        $status = 404;

        if ($request->has("user_id")) {
            $user = User::find($request->user_id);
            $smsVerifcation = SmsVerification::where('user_id', $request->get('user_id'))
                ->where('code', $code)
                ->where('status', 'pending')
                ->first();
        }

        if ($request->has("phone")) {
            $smsVerifcation = SmsVerification::where('contact_number', $request->get('phone'))
                ->where('code', $code)
                ->where('status', 'pending')
                ->first();
        }
        if (isset($smsVerifcation)) {
            if ($code == $smsVerifcation->code) {
                $user = User::find($smsVerifcation->user_id);
                $smsVerifcation->status = 'verified';
                $smsVerifcation->save();

                $user->confirmed_at = now();
                $user->accepted_privacy_terms_at = now();
                $user->accepted_service_clients_conditions_at = now();
                $user->status = User::STATUS_ACTIVE;
                $user->save();

                event(new Confirmed($user));

                Session::flash(
                    'confirmation_sms',
                    'Has confirmado tu número celular correctamente. Por favor ingresa.'
                );

                $msj = "Usuario verificado";
                $status = 200;
            }
        }


        $data = [
            'message' => $msj,
            'status' => $status
        ];

        return response()->json($data, $status);
    }

    public function confirmPhoneCode(Request $request)
    {
        return view('website.confirm_phone_code');
    }

    public function invalidConfirmationLink(Request $request)
    {
        return view('website.invalid_confirmation_link');
    }

    /**
     * Update cntact info of user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeContactInfo(Request $request)
    {
        $user = User::findOrFail($request->get('user_id'));

        if (isset($user) && isset($request['update'])) {
            if ($user->contact_preference === User::CONTACT_BY_EMAIL) {
                $user->email = $request->get('update');
                $user->access = $request->get('update');

                try {
                    $user->save();
                    $user->sendConfirmationMessage();
                    $message = 'Email updated successfully.';
                    $status = 200;
                } catch (\Exception $e) {
                    $message = $e->getMessage();
                    $status = 500;
                }
            } else {
                $verification = SmsVerification::where('user_id', $user->id)->get()->last();

                if (isset($verification) && $verification->status === SmsVerification::VERIFICATION_PENDING) {
                    $user->phone = $request->get('update');

                    try {
                        $user->save();
                        $user->sendConfirmationMessage();
                        $verification->status = SmsVerification::VERIFICATION_CANCELED;
                        $verification->save();
                        $message = 'Phone updated successfully.';
                        $status = 200;
                    } catch (\Exception $e) {
                        $message = $e->getMessage();
                        $status = 500;
                    }
                }
            }
        } else {
            $message = 'Operation failed.';
            $status = 404;
        }

        return response()->json(['message' => $message], $status);
    }
}
