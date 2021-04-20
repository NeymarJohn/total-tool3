<?php

namespace App;

use App\Notifications\EmailVerification;
use App\Notifications\NewUser;
use App\Observers\CompanyObserver;
use App\Scopes\CompanyScope;
use GuzzleHttp\Client;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Invoice;
use Stripe\Invoice as StripeInvoice;

class Company extends BaseModel
{
    use Notifiable, Billable;

    protected $table = 'companies';

    protected $dates = ['trial_ends_at', 'licence_expire_on', 'created_at', 'updated_at', 'last_login'];

    protected $fillable = ['last_login', 'company_name', 'company_email', 'company_phone', 'website', 'address', 'currency_id', 'timezone', 'locale', 'date_format', 'time_format', 'week_start', 'longitude', 'latitude', 'status'];

    protected $appends = ['logo_url', 'login_background_url','moment_date_format'];

    public static function boot()
    {
        parent::boot();
        static::observe(CompanyObserver::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id')->withoutGlobalScopes(['enable']);
    }
    public function invoice_setting()
    {
        return $this->hasOne(InvoiceSetting::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function employees()
    {
        return $this->hasMany(User::class)
            ->join('employee_details', 'employee_details.user_id', 'users.id');
    }

    public function file_storage()
    {
        return $this->hasMany(FileStorage::class, 'company_id');
    }

    public function getLogoUrlAttribute()
    {
        if (is_null($this->logo)) {
            $global = global_settings();
            return $global->logo_url;
        }
        return asset_url('app-logo/' . $this->logo);
    }

    public function getLoginBackgroundUrlAttribute()
    {
        if (is_null($this->login_background) || $this->login_background == 'login-background.jpg') {
            return asset('img/login-bg.jpg');
        }

        return asset_url('login-background/' . $this->login_background);
    }

    public function validateGoogleRecaptcha($googleRecaptchaResponse)
    {
        $global = global_settings();
        $client = new Client();
        $response = $client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            ['form_params' =>
            [
                'secret' => $global->google_recaptcha_secret,
                'response' => $googleRecaptchaResponse,
                'remoteip' => $_SERVER['REMOTE_ADDR']
            ]]
        );

        $body = json_decode((string) $response->getBody());

        return $body->success;
    }

    public function getMomentDateFormatAttribute()
    {
        $momentDateFormats = [
            'd-m-Y' => 'DD-MM-YYYY',
            'm-d-Y' => 'MM-DD-YYYY',
            'Y-m-d' => 'YYYY-MM-DD',
            'd.m.Y' => 'DD.MM.YYYY',
            'm.d.Y' => 'MM.DD.YYYY',
            'Y.m.d' => 'YYYY.MM.DD',
            'd/m/Y' => 'DD/MM/YYYY',
            'm/d/Y' => 'MM/DD/YYYY',
            'Y/m/d' => 'YYYY/MM/DD',
            'd/M/Y' => 'DD/MMM/YYYY',
            'd.M.Y' => 'DD.MMM.YYYY',
            'd-M-Y' => 'DD-MMM-YYYY',
            'd M Y' => 'DD MMM YYYY',
            'd F, Y' => 'DD MMMM, YYYY',
            'D/M/Y' => 'ddd/MMM/YYYY',
            'D.M.Y' => 'ddd.MMM.YYYY',
            'D-M-Y' => 'ddd-MMM-YYYY',
            'D M Y' => 'ddd MMM YYYY',
            'd D M Y' => 'DD ddd MMM YYYY',
            'D d M Y' => 'ddd DD MMM YYYY',
            'dS M Y' => 'Do MMM YYYY',
        ];
        return $momentDateFormats[$this->date_format];
    }

    public function addUser($company, $request)
    {
        // Save Admin
        $user = User::withoutGlobalScopes([CompanyScope::class, 'active'])->where('email', $request->email)->first();
        if (is_null($user)) {
            $user = new User();
        }
        $user->company_id = $company->id;
        $user->name = 'admin';
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->status = 'active';
        $user->email_verification_code = str_random(40);
        $user->save();

        return $user;
    }
    public function addEmployeeDetails($user, $createdBy=null)
    {
        $employee = new EmployeeDetails();
        $employee->user_id = $user->id;
        $employee->employee_id = 'emp-' . $user->id;
        $employee->company_id = $user->company_id;
        $employee->address = 'address';
        $employee->hourly_rate = '50';
        $employee->save();

        $global = global_settings();

        if ($global->email_verification == 1 && is_null($createdBy) && $createdBy !=='superadmin') {
            // Send verification mail
            $user->notify(new EmailVerification($user));
            $user->status = 'deactive';
            $user->save();

            $message = __('messages.signUpThankYouVerify');
        } else {
            $user->notify(new NewUser(request()->password));
            $message = __('messages.signUpThankYou') . ' <a href="' . route('login') . '">Login Now</a>.';
        }
        return $message;
    }
    public function recaptchaValidate($request)
    {
        $global = global_settings();
        if ($global->google_recaptcha_status) {
            $gRecaptchaResponseInput = 'g-recaptcha-response';
            $gRecaptchaResponse = $request->{$gRecaptchaResponseInput};
            $validateRecaptcha = $this->validateGoogleRecaptcha($gRecaptchaResponse);
            if (!$validateRecaptcha) {
                return false;
            }
        }
        return true;
    }

    public function assignRoles($user)
    {

        // Assign roles even before verification
        $adminRole = Role::where('name', 'admin')->where('company_id', $user->company_id)->first();
        $user->roles()->attach($adminRole->id);

        $employeeRole = Role::where('name', 'employee')->where('company_id', $user->company_id)->first();
        $user->roles()->attach($employeeRole->id);

        return $user;
    }

    public function setSubDomainAttribute($value)
    {
        // domain is added in the request Class
        $this->attributes['sub_domain'] = strtolower($value);
    }
}
