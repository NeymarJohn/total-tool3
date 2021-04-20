<?php

namespace App;

use App\Notifications\ResetPassword;
use App\Observers\UserObserver;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Trebol\Entrust\Traits\EntrustUserTrait;


class User extends BaseModel implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Notifiable, EntrustUserTrait, Authenticatable, Authorizable, CanResetPassword;


    protected static function boot()
    {
        parent::boot();
        static::observe(UserObserver::class);

        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('users.status', '=', 'active');
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'mobile', 'login', 'status', 'image', 'gender', 'locale', 'onesignal_player_id', 'email_notifications', 'country_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at'
    ];

    public $dates = ['created_at', 'updated_at', 'last_login'];

    protected $appends = ['image_url', 'modules', 'user_other_role'];

    public function getImageUrlAttribute()
    {
        return ($this->image) ? asset_url('avatar/' . $this->image) : asset('img/default-profile-3.png');
    }



    /**
     * Route notifications for the Slack channel.
     *
     * @return string
     */
    public function routeNotificationForSlack()
    {
        $slack = SlackSetting::setting();
        return $slack->slack_webhook;
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


    public function client_details()
    {
        return $this->hasOne(ClientDetails::class, 'user_id');
    }

    public function lead()
    {
        return $this->hasOne(Lead::class, 'user_id');
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

    public function lead_agent()
    {
        return $this->hasMany(LeadAgent::class, 'user_id');
    }

    public function group()
    {
        return $this->hasMany(EmployeeTeam::class, 'user_id');
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
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
        return cache()->remember(
            'all-clients',
            60 * 60 * 24,
            function () {
                return User::withoutGlobalScope('active')
                    ->join('role_user', 'role_user.user_id', '=', 'users.id')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->join('client_details', 'users.id', '=', 'client_details.user_id')
                    ->select('users.id', 'users.name', 'users.email', 'users.created_at', 'client_details.company_name', 'users.image', 'users.email_notifications', 'users.mobile', 'users.country_id')
                    ->where('roles.name', 'client')
                    ->orderBy('users.name', 'asc')
                    ->get();
            }
        );
    }

    public static function allEmployees($exceptId = NULL)
    {
        if (!is_null($exceptId)) {
            $users = User::withoutGlobalScope('active')
                ->join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->join('employee_details', 'employee_details.user_id', '=', 'users.id')
                ->leftJoin('designations', 'employee_details.designation_id', '=', 'designations.id')
                ->select('users.id', 'users.name', 'users.email', 'users.created_at', 'users.image', 'designations.name as designation_name', 'users.email_notifications', 'users.mobile', 'users.country_id')
                ->where('roles.name', '<>', 'client')
                ->where('users.id', '<>', $exceptId);
            $users->orderBy('users.name', 'asc');
            $users->groupBy('users.id');
            return $users->get();
        }

        return cache()->remember(
            'all-employees',
            60 * 60 * 24,
            function () use ($exceptId) {
                $users = User::withoutGlobalScope('active')
                    ->join('role_user', 'role_user.user_id', '=', 'users.id')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->join('employee_details', 'employee_details.user_id', '=', 'users.id')
                    ->leftJoin('designations', 'employee_details.designation_id', '=', 'designations.id')
                    ->select('users.id', 'users.name', 'users.email', 'users.created_at', 'users.image', 'designations.name as designation_name', 'users.email_notifications', 'users.mobile', 'users.country_id')
                    ->where('roles.name', '<>', 'client');

                if (!is_null($exceptId)) {
                    $users->where('users.id', '<>', $exceptId);
                }

                $users->orderBy('users.name', 'asc');
                $users->groupBy('users.id');
                return $users->get();
            }
        );
    }

    public static function allAdmins($exceptId = NULL)
    {
        if (!is_null($exceptId)) {
            $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select('users.*')
                ->where('roles.name', 'admin');

            if (!is_null($exceptId)) {
                $users->where('users.id', '<>', $exceptId);
            }

            return $users->get();
        }

        return cache()->remember(
            'all-admins',
            60 * 60 * 24,
            function () use ($exceptId) {
                $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->select('users.*')
                    ->where('roles.name', 'admin');

                if (!is_null($exceptId)) {
                    $users->where('users.id', '<>', $exceptId);
                }

                return $users->get();
            }
        );
    }

    public static function departmentUsers($teamId)
    {
        $users = User::join('employee_details', 'employee_details.user_id', '=', 'users.id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('employee_details.department_id', $teamId);

        return $users->get();
    }

    public static function userListLatest($userID, $term)
    {

        if ($term) {
            $termCnd = "and users.name like '%$term%'";
        } else {
            $termCnd = '';
        }

        $messageSetting = message_setting();

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
                    SELECT users.id,'0' AS groupId, users.name, users.image, users_chat.created_at as last_message, users_chat.message, users_chat.message_seen, users_chat.user_one
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
        $user = User::find($userId);
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

    public function getModulesAttribute()
    {
        return user_modules();
    }

    public function sticky()
    {
        return $this->hasMany(StickyNote::class, 'user_id')->orderBy('updated_at', 'desc');
    }

    public function user_chat()
    {
        return $this->hasMany(UserChat::class, 'to')->where('message_seen', 'no');
    }

    public function employee_details()
    {
        return $this->hasOne(EmployeeDetails::class);
    }

    public function getUnreadNotificationsAttribute()
    {
        return $this->unreadNotifications()->get();
    }

    /**
     * Check if user has a permission by its name.
     *
     * @param string|array $permission Permission string or array of permissions.
     * @param bool         $requireAll All permissions in the array are required.
     *
     * @return bool
     */
    public function can($permission, $requireAll = false)
    {
        config(['cache.default' => 'array']);
        if (is_array($permission)) {
            foreach ($permission as $permName) {
                $hasPerm = $this->can($permName);

                if ($hasPerm && !$requireAll) {
                    return true;
                } elseif (!$hasPerm && $requireAll) {
                    return false;
                }
            }

            // If we've made it this far and $requireAll is FALSE, then NONE of the perms were found
            // If we've made it this far and $requireAll is TRUE, then ALL of the perms were found.
            // Return the value of $requireAll;
            return $requireAll;
        } else {
            foreach ($this->cachedRoles() as $role) {
                // Validate against the Permission table
                foreach ($role->cachedPermissions() as $perm) {
                    if (Str::is($permission, $perm->name)) {
                        return true;
                    }
                }
            }
        }
        config(['cache.default' => 'file']);

        return false;
    }

    public function getUserOtherRoleAttribute()
    {
        if (!module_enabled('RestAPI')) {
            return true;
        }
        $userRole = null;
        $roles = cache()->remember(
            'non-client-roles',
            60 * 60 * 24,
            function () {
                return Role::where('name', '<>', 'client')
                    ->orderBy('id', 'asc')->get();
            }
        );
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

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
    public function contracts()
    {
        return $this->hasMany(Contract::class, 'client_id', 'id');
    }
}
