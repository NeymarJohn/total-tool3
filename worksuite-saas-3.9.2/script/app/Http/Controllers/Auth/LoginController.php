<?php

namespace App\Http\Controllers\Auth;

use App\GlobalSetting;
use App\Http\Controllers\Front\FrontBaseController;
use App\Social;
use App\Traits\SocialAuthSettings;
use App\User;
use Carbon\Carbon;
use Froiden\Envato\Traits\AppBoot;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends FrontBaseController
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

    use AuthenticatesUsers, AppBoot, SocialAuthSettings;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function showLoginForm()
    {

        if (!$this->isLegal()) {
            return redirect('verify-purchase');
        }

        if ($this->global->frontend_disable) {
            return view('auth.login', $this->data);
        }

        if ($this->setting->front_design == 1 && $this->setting->login_ui == 1) {
            return view('saas.login', $this->data);
        }
        $this->pageTitle = 'Login Page';
        return view('auth.login', $this->data);
    }

    protected function validateLogin(\Illuminate\Http\Request $request)
    {
        $setting = GlobalSetting::first();

        $rules = [
            $this->username() => 'required|string',
            'password' => 'required|string'
        ];

        // User type from email/username
        $user = User::where($this->username(), $request->{$this->username()})->first();


        if ($setting->google_recaptcha_status && (is_null($user) || ($user && !$user->super_admin))) {
            if($setting->google_captcha_version == "v2"){
                $rules['g-recaptcha-response'] = 'required';
            }else{
                $rules['recaptcha_token'] ='required';
            }
        }

        if (module_enabled('Subdomain')) {
            $rules = $this->rulesValidate($user);
        }
        $this->validate($request, $rules);
    }

    public function googleRecaptchaMessage()
    {
        throw ValidationException::withMessages([
            'g-recaptcha-response' => [trans('auth.recaptchaFailed')],
        ]);
    }

    public function companyInactiveMessage()
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.companyStatusInactive')],
        ]);
    }

    public function validateGoogleRecaptcha($googleRecaptchaResponse)
    {
        $setting = GlobalSetting::first();

        $client = new Client();
        $response = $client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'form_params' =>
                [
                    'secret' => $setting->google_recaptcha_secret,
                    'response' => $googleRecaptchaResponse,
                    'remoteip' => $_SERVER['REMOTE_ADDR']
                ]
            ]
        );

        $body = json_decode((string) $response->getBody());

        return $body->success;
    }

    public function login(\Illuminate\Http\Request $request)
    {
        $setting = GlobalSetting::first();
        $this->validateLogin($request);

        // User type from email/username
        $user = User::where($this->username(), $request->{$this->username()})->first();

        if ($user &&  !$user->super_admin && $user->company->status == 'inactive' && !$user->hasRole('client')) {
            return $this->companyInactiveMessage();
        }

        // Check google recaptcha if setting is enabled
        if ($setting->google_recaptcha_status && (is_null($user) || ($user && !$user->super_admin))) {
                 // Checking is google recaptcha is valid
                $gRecaptchaResponseInput = 'g-recaptcha-response';
                $gRecaptchaResponse = $setting->google_captcha_version == "v2" ? $request->{$gRecaptchaResponseInput} : $request->get('recaptcha_token');

                $validateRecaptcha = $this->validateGoogleRecaptcha($gRecaptchaResponse);

                if (!$validateRecaptcha) {
                    return $this->googleRecaptchaMessage();
                }
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function credentials(\Illuminate\Http\Request $request)
    {
        //return $request->only($this->username(), 'password');
        return [
            'email' => $request->{$this->username()},
            'password' => $request->password,
            'status' => 'active',
            'login' => 'enable'
        ];
    }

    protected function redirectTo()
    {
        $user = auth()->user();
        if ($user->super_admin == '1') {
            return 'super-admin/dashboard';
        } elseif ($user->hasRole('admin')) {
            $user->company()->update([
                'last_login' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
            return 'admin/dashboard';
        }

        if ($user->hasRole('employee')) {
            return 'member/dashboard';
        }

        if ($user->hasRole('client')) {
            return 'client/dashboard';
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $user = auth()->user();
        $this->guard()->logout();

        $request->session()->invalidate();
        if (module_enabled('Subdomain')) {
            if ($user->super_admin == 1) {
                return $this->loggedOut($request) ?: redirect(route('front.super-admin-login'));
            }
        }

        return $this->loggedOut($request) ?: redirect('/login');
    }

    private function rulesValidate($user)
    {
        if (Str::contains(url()->previous(), 'super-admin-login')) {
            $rules = [
                $this->username() => [
                    'required',
                    'string',
                    Rule::exists('users', 'email')->where(function ($query) {
                        $query->where('super_admin', '1');
                    })
                ],
                'password' => 'required|string',
            ];
        } else {
            $company = getCompanyBySubDomain();
            $client = false;
            $companies = [];

            if ($user && User::isClient($user->id)) {
                $companies[]  = $user->company_id;
                $client = true;
                foreach ($user->client as $item) {
                    $companies[] = $item->company_id;
                }

            }

            $rules = [
                $this->username() => [
                    'required',
                    'string',
                    Rule::exists('users', 'email')->where(function ($query) use ($company, $companies, $client) {
                        if ($client) {
                            $query->whereIn('company_id', $companies);
                        } else {
                            $query->where('company_id', $company->id);
                        }
                    })
                ],
                'password' => 'required|string',

            ];
        }
        return $rules;
    }


    public function redirect($provider)
    {
        $this->setSocailAuthConfigs();
        return Socialite::driver($provider)->redirect();
    }

    public function callback(Request $request, $provider)
    {
        $this->setSocailAuthConfigs();
        $redirectRoute = module_enabled('Subdomain') ? 'front.workspace' : 'login';

        try {
            if ($provider != 'twitter') {
                $data = Socialite::driver($provider)->stateless()->user();
            } else {
                $data = Socialite::driver($provider)->user();
            }
        } catch (\Exception $e) {


            if ($request->has('error_description') || $request->has('denied')) {
                return redirect()->route($redirectRoute)->withErrors([$this->username() => 'The user cancelled ' . $provider . ' login']);
            }

            throw ValidationException::withMessages([
                $this->username() => [$e->getMessage()],
            ])->status(Response::HTTP_TOO_MANY_REQUESTS);
        }


        $user = User::where('email', '=', $data->email)->first();
        if ($user) {
            // User found
            \DB::beginTransaction();

            Social::updateOrCreate(['user_id' => $user->id], [
                'social_id' => $data->id,
                'social_service' => $provider,
            ]);

            if ($user->super_admin == 1) {
                \Auth::login($user);
                return redirect()->intended($this->redirectPath());
            }

            \DB::commit();

            $user->social_token = Str::random(60);
            $user->save();

            if (module_enabled('Subdomain')) {
                return redirect()->to(str_replace(request()->getHost(), $user->company->sub_domain, route('login')) . '?token=' . $user->social_token);
            }

            \Auth::login($user);
            return redirect()->intended($this->redirectPath());
        }

        if (module_enabled('Subdomain')) {
            return redirect()->route($redirectRoute)->withErrors(['sub_domain' => Lang::get('auth.sociaLoginFail')]);
        }

        throw ValidationException::withMessages([
            $this->username() => [Lang::get('auth.sociaLoginFail')],
        ])->status(Response::HTTP_TOO_MANY_REQUESTS);
    }
}
