<?php

namespace App\Http\Controllers\Member;

use App\Company;
use App\EmailNotificationSetting;
use App\EmployeeFaq;
use App\GdprSetting;
use App\GlobalSetting;
use App\InvoiceSetting;
use App\Notification;
use App\ProjectActivity;
use App\ProjectTimeLog;
use App\PushNotificationSetting;
use App\Role;
use App\StickyNote;
use App\Traits\FileSystemSettingTrait;
use App\UniversalSearch;
use App\UserActivity;
use App\UserChat;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use App\ThemeSetting;
use Illuminate\Support\Facades\Auth;

class MemberBaseController extends Controller
{
    use FileSystemSettingTrait;
    /**
     * @var array
     */
    public $data = [];

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->data[$name];
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * UserBaseController constructor.
     */
    public function __construct()
    {
        // Inject currently logged in user object into every view of user dashboard

        Carbon::setUtf8(true);
        $this->setFileSystemConfigs();

        $this->middleware(function ($request, $next) {
            $this->user = user();
            $this->global = company_setting();
            $this->pushSetting = push_setting();
            $this->companyName = $this->global->company_name;
            $this->employeeTheme = employee_theme();
            $this->superadmin = global_settings();
            $this->faqs = EmployeeFaq::all();

            App::setLocale($this->user->locale);
            Carbon::setUtf8(true);
            Carbon::setLocale($this->user->locale);
            setlocale(LC_TIME, $this->user->locale . '_' . strtoupper($this->user->locale));

            $this->setFileSystemConfigs();

            $this->modules = $this->user->modules;

            // For GDPR
            try {
                $this->gdpr = GdprSetting::first();

                if (!$this->gdpr) {
                    $gdpr = new GdprSetting();
                    $gdpr->company_id = Auth::user()->company_id;
                    $gdpr->save();

                    $this->gdpr = $gdpr;
                }
            } catch (\Exception $e) {
            }

            $userRole = $this->user->role; // Getting users all roles

            if (count($userRole) > 1) {
                $roleId = $userRole[1]->role_id;
            } // if single role assign getting role ID
            else {
                $roleId = $userRole[0]->role_id;
            } // if multiple role assign getting role ID

            // Getting role detail by ID that got above according single or multiple roles assigned.
            $this->userRole = Role::where('id', $roleId)->first();

            $this->unreadNotificationCount = count($this->user->unreadNotifications);
            $this->unreadMessageCount = UserChat::where('to', $this->user->id)->where('message_seen', 'no')->count();
            $this->unreadExpenseCount = Notification::where('notifiable_id', $this->user->id)
                ->where(function ($query) {
                    $query->where('type', 'App\Notifications\NewExpenseStatus');
                    $query->orWhere('type', 'App\Notifications\NewExpenseMember');
                })
                ->whereNull('read_at')
                ->count();
            $this->unreadProjectCount = Notification::where('notifiable_id', $this->user->id)
                ->where('type', 'App\Notifications\NewProjectMember')
                ->whereNull('read_at')
                ->count();
            $this->stickyNotes = StickyNote::where('user_id', $this->user->id)->orderBy('updated_at', 'desc')->get();
            $this->invoiceSetting = InvoiceSetting::first();
            $this->worksuitePlugins = worksuite_plugins();

            if (config('filesystems.default') == 's3') {
                $this->url = "https://" . config('filesystems.disks.s3.bucket') . ".s3.amazonaws.com/";
            }

            $this->worksuitePlugins = worksuite_plugins();
            
            return $next($request);
        });
    }

    public function logProjectActivity($projectId, $text)
    {
        $activity = new ProjectActivity();
        $activity->project_id = $projectId;
        $activity->activity = $text;
        $activity->save();
    }

    public function logUserActivity($userId, $text)
    {
        $activity = new UserActivity();
        $activity->user_id = $userId;
        $activity->activity = $text;
        $activity->save();
    }

    public function logSearchEntry($searchableId, $title, $route, $type)
    {
        $search = new UniversalSearch();
        $search->searchable_id = $searchableId;
        $search->title = $title;
        $search->route_name = $route;
        $search->module_type = $type;
        $search->save();
    }
}
