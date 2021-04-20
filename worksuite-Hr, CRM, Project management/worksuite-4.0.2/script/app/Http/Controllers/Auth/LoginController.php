<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Social;
use App\Traits\SocialAuthSettings;
use App\User;
use Froiden\Envato\Traits\AppBoot;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

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

        $setting = $this->global;
        $socialAuthSettings = $this->socialAuthSettings;

        return view('auth.login', compact('setting', 'socialAuthSettings'));
    }

    protected function validateLogin(\Illuminate\Http\Request $request)
    {
        $rules = [
            $this->username() => 'required|string',
            'password' => 'required|string'
        ];

        // User type from email/username
        $user = User::where($this->username(), $request->{$this->username()})->first();

        // Check google recaptcha if setting is enabled
        if ($this->global->google_recaptcha && (is_null($user) || ($user && !$user->hasRole('admin')))) {
            $rules['g-recaptcha-response'] = 'required';
        }

        $this->validate($request, $rules);
    }

    public function googleRecaptchaMessage()
    {
        throw ValidationException::withMessages([
            'g-recaptcha-response' => [trans('auth.recaptchaFailed')],
        ]);
    }

    public function validateGoogleRecaptcha($googleRecaptchaResponse)
    {
        $client = new Client();
        $response = $client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'form_params' =>
                [
                    'secret' => $this->global->google_recaptcha_secret,
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

        $this->validateLogin($request);

        // User type from email/username
        $user = User::where($this->username(), $request->{$this->username()})->first();

        // Check google recaptcha if setting is enabled
        if ($this->global->google_recaptcha && (is_null($user) || ($user && !$user->hasRole('admin')))) {
            // Checking is google recaptcha is valid
            $gRecaptchaResponseInput = 'g-recaptcha-response';
            $gRecaptchaResponse = $request->{$gRecaptchaResponseInput};
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
        if ($user->hasRole('admin')) {
            return 'admin/dashboard';
        }

        if ($user->hasRole('employee')) {
            return 'member/dashboard';
        }

        if ($user->hasRole('client')) {
            return 'client/dashboard';
        }
    }


    public function redirect($provider)
    {
        $this->setSocailAuthConfigs();
        return Socialite::driver($provider)->redirect();
    }

    public function callback(Request $request, $provider)
    {
        $this->setSocailAuthConfigs();

        try {
            if($provider != 'twitter') {
                $data = Socialite::driver($provider)->stateless()->user();
            } else {
                $data = Socialite::driver($provider)->user();
            }
        }
        catch (\Exception $e) {
            if ($request->has('error_description') || $request->has('denied')) {
                return redirect()->route('login')->withErrors([$this->username() => 'The user cancelled '.$provider.' login']);
            }

            throw ValidationException::withMessages([
                $this->username() => [$e->getMessage()],
            ])->status(Response::HTTP_TOO_MANY_REQUESTS);
        }


        $user = User::where('email', '=', $data->email)->first();
        if($user) {
            // User found
            \DB::beginTransaction();

            Social::updateOrCreate(['user_id' => $user->id],[
                'social_id' => $data->id,
                'social_service' => $provider,
            ]);

            \DB::commit();

            \Auth::login($user);
            return redirect()->intended($this->redirectPath());
        } else {
            throw ValidationException::withMessages([
                $this->username() => [Lang::get('auth.sociaLoginFail')],
            ])->status(Response::HTTP_TOO_MANY_REQUESTS);
        }

    }
}
