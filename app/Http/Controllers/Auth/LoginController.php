<?php

namespace Apithy\Http\Controllers\Auth;

use Apithy\Company;
use Apithy\CompanyPosition;
use Apithy\Exceptions\AlreadyRegisteredException;
use Apithy\Experience;
use Apithy\Http\Controllers\Controller;
use Apithy\Services\ExperiencesService;
use Apithy\Services\RegisterService;
use Apithy\Services\StadisticService;
use Apithy\Services\UsersService;
use Apithy\User;
use Apithy\Utilities\Master\Master;
use Apithy\Validators\UserValidator;
use BeyondCode\EmailConfirmation\Traits\AuthenticatesUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

/**
 * Class LoginController
 * @package Apithy\Http\Controllers\Auth
 */
class LoginController extends Controller
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

    use AuthenticatesUsers;


    /**
     * @var string
     */
    protected $loginField = 'access';

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * UsersController constructor.
     *

     * @param RegisterService $registerService
     * @param Request $request
     */

    /**
     * Create a new controller instance.
     */
    public function __construct(RegisterService $registerService)
    {
        $this->middleware('company.check');
        $this->middleware('guest', ['except' => ['logout']]);
        $this->middleware('user.company.check', ['except' => ['showLoginForm','logout']]);
        $this->loginField = $this->findUsername();
        $this->registerService = $registerService;
    }

    public function showLoginForm(Request $request)
    {
        $show_signup=false;
        $show_social_connect=false;
        if ($request->session()->get('current.company')) {
            $company=$request->session()->get('current.company');
            if ($company) {
                if ($company->settings('login', 'show_register', 1)->first()) {
                    $show_signup = true;
                }

                if ($company->settings('login', 'show_social_connect', 1)->first()) {
                    $show_social_connect = true;
                }
            }

            return view(
                'auth.company-login',
                ['show_register'=>$show_signup, 'show_social_connect'=>$show_social_connect]
            )
                ->with('company', $request->session()->get('current.company'));
        }
        return view('auth.login')->with('company', $request->session()->get('current.company'));
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, UserValidator::loginRules());
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */

    protected function authenticated(Request $request, $user)
    {
        if ($user->status === User::STATUS_PASSWORD_ESTABLISHED) {
            $user->status = User::STATUS_ACTIVE;
            $user->save();
        }

        if (!$user->getting_started_status) {
            return redirect()->route('getting_started.index');
        }

        if (isset($_COOKIE['public_experience'])) {
            $data = null;
            $cookie = json_decode($_COOKIE['public_experience']);

            if (isset($cookie->id) && isset($cookie->event)) {
                $experience = Experience::findOrFail($cookie->id);
                $experience_service = new ExperiencesService();
                $data = $experience_service->addOrEnroll($experience, $cookie);
            }

            setcookie('operation_result', $data);
            return response()->redirectToRoute($cookie->redirect_to, ['experience' => $cookie->experience]);
        }

        //setcookie('open_user_menu', Str::random(5));

        if ($user->isSuper()) {
            return redirect()->route('users.index');
        }

        if ($user->isAdmin()) {
            return redirect('/home');
        }

        if ($user->company[0]->id !== Company::getDefautCompanyId()) {
            return redirect('/my-experiences');
        }

        return redirect('/experiences');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function findUsername()
    {

        $login = request()->input('access');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'access' : 'phone';


        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return $this->loginField;
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
        } catch (InvalidStateException $e) {
            $user = Socialite::driver($provider)->stateless()->user();
        }

        $apithy_user=User::where('access', $user->email)->first();

        if ($apithy_user) {
            \Auth::login($apithy_user);
            return redirect('/');
        } else {
            switch ($provider) {
                case 'google':
                case 'facebook':
                    $data=[
                        'name'=>$user->name,
                        'email'=>$user->email,
                    ];
                    break;
                case 'linkedin':
                    $data=[
                        'name'=>$user->first_name,
                        'surname'=>$user->last_name,
                        'email'=>$user->email,
                    ];
                    break;
                //Microsoft Service
                case 'graph':
                    $data=[
                        'name'=>$user->givenName,
                        'surname'=>$user->surname,
                        'email'=>$user->userPrincipalName,
                    ];
                    break;
            }

            $data['confirmed_at']=time();
            $data['contact_preference']='mail';
            $data['register_type']='social_login';

            $this->storeFromSocial($data);
            return redirect('/');
        }
    }

    public function storeFromSocial($user_data)
    {

        try {
            $data = $this->registerService->validateCompany($user_data);
            $user = $this->registerService->createUser($data);

            $user->status = User::STATUS_ACTIVE;
            $user->confirmation_code = null;
            $user->confirmed_at = now();
            $user->accepted_privacy_terms_at = now();
            $user->accepted_service_clients_conditions_at = now();
            $user->save();

            $notification = app(\Apithy\Notifications\WelcomeEmail::class);
            $user->notify($notification);

            event(new Registered($user));

            \Auth::login($user);

            return redirect('/');
        } catch (WebserviceException | AlreadyRegisteredException $ex) {
            return Master::errorResponse('users', 'create', '', 409, ['e' => $ex->getMessage()]);
        }
    }
}
