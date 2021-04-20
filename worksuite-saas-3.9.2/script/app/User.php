<?php

namespace App;

use App\Notifications\EmailVerificationSuccess;
use App\Notifications\NewUser;
use App\Notifications\ResetPassword;
use App\Observers\UserObserver;
use App\Scopes\CompanyScope;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Trebol\Entrust\Traits\EntrustUserTrait;


class User extends BaseModel implements AuthenticatableContract, CanResetPasswordContract
{
    use Notifiable, EntrustUserTrait, Authenticatable, CanResetPassword;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];
    protected $fillable = [
        'name', 'email', 'password', 'mobile', 'login', 'status', 'image', 'gender', 'locale', 'onesignal_player_id', 'email_notifications', 'country_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public $dates = ['created_at', 'updated_at'];


    public $appends = ['image_url', 'modules', 'user_other_role'];

    protected static function boot()
    {
        parent::boot();

        static::observe(UserObserver::class);

        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('users.status', '=', 'active');
        });

        static::addGlobalScope(new CompanyScope);
    }

    /**
     * Route notifications for the Slack channel.
     *
     * @return string
     */
    public function routeNotificationForSlack()
    {
        $slack = SlackSetting::first();
        return $slack->slack_webhook;
    }

    public function getUnreadNotificationsAttribute()
    {
        if (user()->company_id) {
            return $this->unreadNotifications()->where('company_id', company()->id)->get();
        }

        return $this->unreadNotifications()->get();
    }

    public function routeNotificationForOneSignal()
    {
        return $this->onesignal_player_id;
    }

    public function routeNotificationForTwilio()
    {
        if (!is_null($this->mobile) && !is_null($this->country_id)) {
            return '+' . $this->country->phonecode . $this->mobile;
        } else {
            return null;
        }
    }

    public function routeNotificationForNexmo($notification)
    {
        if (!is_null($this->mobile) && !is_null($this->country_id)) {
            return $this->country->phonecode . $this->mobile;
        } else {
            return null;
        }
    }

    public function routeNotificationForMsg91($notification)
    {
        if (!is_null($this->mobile) && !is_null($this->country_id)) {
            return $this->country->phonecode . $this->mobile;
        } else {
            return null;
        }
    }

    public function routeNotificationForEmail($notification = null)
    {

        $containsExample = Str::contains($this->email, 'example');
        if (\config('app.env') === 'demo' && $containsExample) {
            return null;
        }

        return $this->email;
    }

    public function routeNotificationForDatabase($notification = null)
    {

        $containsExample = Str::contains($this->email, 'example');
        if (\config('app.env') === 'demo' && $containsExample) {
            return null;
        }

        return $this->notifications();
    }

    public function client()
    {
        return $this->hasMany(ClientDetails::class, 'user_id');
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function lead_agent()
    {
        return $this->hasMany(LeadAgent::class, 'user_id');
    }

    public function client_detail()
    {
        if(company()){
              return $this->hasOne(ClientDetails::class, 'user_id')
            ->where('client_details.company_id', company()->id);
       }
        return $this->hasOne(ClientDetails::class, 'user_id');
    }

    public function client_details()
    {
        return $this->hasOne(ClientDetails::class, 'user_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'user_id');
    }

    public function employee()
    {
        return $this->hasMany(EmployeeDetails::class, 'user_id');
    }


    public function employeeDetail()
    {
        return $this->hasOne(EmployeeDetails::class, 'user_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'client_id');
    }

    public function member()
    {
        return $this->hasMany(ProjectMember::class, 'user_id');
    }

    public function template_member()
    {
        return $this->hasMany(ProjectTemplateMember::class, 'user_id');
    }

    public function role()
    {
        return $this->hasMany(RoleUser::class, 'user_id');
    }

    public function attendee()
    {
        return $this->hasMany(EventAttendee::class, 'user_id');
    }

    public function agent()
    {
        return $this->hasMany(TicketAgentGroups::class, 'agent_id');
    }

    public function group()
    {
        return $this->hasMany(EmployeeTeam::class, 'user_id');
    }

    public function skills()
    {
        return EmployeeSkill::select('skills.name')->join('skills', 'skills.id', 'employee_skills.skill_id')->where('user_id', $this->id)->pluck('name')->toArray();
    }

    public function leaveTypes()
    {
        return $this->hasMany(EmployeeLeaveQuota::class);
    }

    public static function allClients()
    {
        $clients = ClientDetails::join('users', 'client_details.user_id', '=', 'users.id')
            ->select('users.id', 'client_details.name', 'users.email', 'users.email_notifications', 'users.created_at', 'client_details.company_name', 'users.image')
            ->get();

        return $clients;
    }

    public static function allSuperAdmin()
    {
        return User::withoutGlobalScopes(['active', CompanyScope::class])
            ->where('super_admin', '1')
            ->get();
    }

    public static function allEmployees($exceptId = null)
    {
        $users = User::withoutGlobalScope('active')->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.email_notifications', 'users.created_at', 'users.image')
            ->where('roles.name', '<>', 'client');

        if (!is_null($exceptId)) {
            $users->where('users.id', '<>', $exceptId);
        }

        $users->orderBy('users.name', 'asc');
        $users->groupBy('users.id');
        return $users->get();
    }

    public static function allAdmins($exceptId = null)
    {
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email_notifications', 'users.email', 'users.created_at', 'users.image')
            ->where('roles.name', 'admin');

        if (!is_null($exceptId)) {
            $users->where('users.id', '<>', $exceptId);
        }

        return $users->get();
    }

    public static function firstAdmin()
    {
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', 'admin')
            ->orderBy('users.id', 'asc');
        return $users->first();
    }

    public static function frontAllAdmins($companyId)
    {
        return User::withoutGlobalScope('active')->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.*')
            ->where('roles.name', 'admin')
            ->where('users.company_id', $companyId)
            ->get();
    }

    public static function teamUsers($teamId)
    {
        $users = User::join('employee_teams', 'employee_teams.user_id', '=', 'users.id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('employee_teams.team_id', $teamId);

        return $users->get();
    }

    public static function userListLatest($userID, $term)
    {

        if ($term) {
            $termCnd = "and users.name like '%$term%'";
        } else {
            $termCnd = '';
        }

        $messageSetting = MessageSetting::first();

        if (auth()->user()->hasRole('admin')) {
            if ($messageSetting->allow_client_admin == 'no') {
                $termCnd .= "and roles.name != 'client'";
            }
        } elseif (auth()->user()->hasRole('employee')) {
            if ($messageSetting->allow_client_employee == 'no') {
                $termCnd .= "and roles.name != 'client'";
            }
        } elseif (auth()->user()->hasRole('client')) {
            if ($messageSetting->allow_client_admin == 'no') {
                $termCnd .= "and roles.name != 'admin'";
            }
            if ($messageSetting->allow_client_employee == 'no') {
                $termCnd .= "and roles.name != 'employee'";
            }
        }

        $query = DB::select("SELECT * FROM ( SELECT * FROM (
                    SELECT users.id,'0' AS groupId, users.name,  users.image,  users_chat.created_at as last_message, users_chat.message, users_chat.message_seen, users_chat.user_one
                    FROM users
                    INNER JOIN users_chat ON users_chat.from = users.id
                    LEFT JOIN role_user ON role_user.user_id = users.id
                    LEFT JOIN roles ON roles.id = role_user.role_id
                    WHERE users_chat.to = $userID $termCnd
                    UNION
                    SELECT users.id,'0' AS groupId, users.name,users.image, users_chat.created_at  as last_message, users_chat.message, users_chat.message_seen, users_chat.user_one
                    FROM users
                    INNER JOIN users_chat ON users_chat.to = users.id
                    LEFT JOIN role_user ON role_user.user_id = users.id
                    LEFT JOIN roles ON roles.id = role_user.role_id
                    WHERE users_chat.from = $userID  $termCnd
                    ) AS allUsers
                    ORDER BY  last_message DESC
                    ) AS allUsersSorted
                    GROUP BY id
                    ORDER BY  last_message DESC");

        return $query;
    }

    public static function isAdmin($userId)
    {
        $user = User::find($userId);
        if ($user) {
            return $user->hasRole('admin') ? true : false;
        }
        return false;
    }

    public static function isClient($userId)
    {
        $user = User::withoutGlobalScope(CompanyScope::class)->find($userId);
        if ($user) {
            return $user->hasRole('client') ? true : false;
        }
        return false;
    }

    public static function isEmployee($userId)
    {
        $user = User::find($userId);
        if ($user) {
            return $user->hasRole('employee') ? true : false;
        }
        return false;
    }

    public static function findClient($id)
    {
        return User::withoutGlobalScopes([CompanyScope::class, 'active'])->findOrFail($id);
    }

    public function getModulesAttribute()
    {
        $user = auth()->user();

        if ($user) {

            $module = new ModuleSetting();

            if ($user->hasRole('admin')) {
                $module = $module->where('type', 'admin');
            } elseif ($user->hasRole('client')) {
                $module = $module->where('type', 'client');
            } elseif ($user->hasRole('employee')) {
                $module = $module->where('type', 'employee');
            }

            $module = $module->where('status', 'active');
            $module->select('module_name');

            $module = $module->get();
            $moduleArray = [];
            foreach ($module->toArray() as $item) {
                array_push($moduleArray, array_values($item)[0]);
            }

            return $moduleArray;
        }

        return [];
    }

    public function getNameAttribute($value)
    {
        if (!is_null($this->id) && $this->isClient($this->id)) {
            $client = ClientDetails::select('id', 'company_id', 'name')
                ->where(
                    'user_id',
                    $this->id
                )
                ->first();
            if($client){
                return $value;
            }
            return $client['name'];
        }

        return $value;
    }

    public function getEmailAttribute($value)
    {
        if (!is_null($this->id) && $this->isClient($this->id) && user()) {
            $client = ClientDetails::select('id', 'company_id', 'email')
                ->where(
                    'user_id',
                    $this->id
                )
                ->first();

            return $client['email'];
        }

        return $value;
    }

    public function getImageAttribute($value)
    {
        if (!is_null($this->id) && $this->isClient($this->id)) {
            $client = ClientDetails::select('id', 'company_id', 'image')
                ->where(
                    'user_id',
                    $this->id
                )
                ->first();

            return $client['image'];
        }

        return $value;
    }

    public function getImageUrlAttribute()
    {
        return ($this->image) ? asset_url('avatar/' . $this->image) : asset('img/default-profile-3.png');
    }

    public function getMobileAttribute($value)
    {
        if (!is_null($this->id) && $this->isClient($this->id)) {
            $client = ClientDetails::select('id', 'company_id', 'mobile')
                ->where(
                    'user_id',
                    $this->id
                )
                ->first();

            return $client['mobile'];
        }

        return $value;
    }

    public function getUserOtherRoleAttribute()
    {
        $userRole = null;
        $roles = Role::where('name', '<>', 'client')
            ->orderBy('id', 'asc')->get();
        foreach ($roles as $role) {
            foreach ($this->role as $urole) {
                if ($role->id == $urole->role_id) {
                    $userRole = $role->name;
                }
                if ($userRole == 'admin') {
                    break;
                }
            }
        }
        return $userRole;
    }

    public static function emailVerify($code)
    {
        $user = User::where('email_verification_code', $code)
            ->whereNotNull('email_verification_code')
            ->withoutGlobalScope('active')
            ->first();

        // When verification url doesnot exit in database
        if (!$user) {
            $message = __('messages.verificationUrl').' <a href="' . route('login') . '"><strong>'.__('app.here').'</strong></a>' .__('app.toLogin');
            return $message;
        }

        $user->status = 'active';
        $user->email_verification_code = null;
        $user->save();

        $user->notify(new EmailVerificationSuccess($user));

        $message = __('messages.verifiedEmailText').' <a href="' . route('login') . '"><strong>'.__('app.here').'</strong></a> ' .__('app.toLogin');

        return $message;
    }

    public static function allEmployeesByCompany($companyID)
    {
        return User::withoutGlobalScope('active')->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', '<>', 'client')
            ->groupBy('users.id')
            ->where('users.company_id', $companyID)
            ->get();
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
}
