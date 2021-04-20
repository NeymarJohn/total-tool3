<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\EmailNotificationSetting;
use App\GdprSetting;
use App\LanguageSetting;
use App\ModuleSetting;
use App\Notification;
use App\Notifications\LicenseExpire;
use App\Package;
use App\PackageSetting;
use App\ProjectActivity;
use App\PushNotificationSetting;
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
use App\GlobalSetting;
use App\TaskHistory;
use App\User;
use Illuminate\Support\Facades\Redirect;

class AdminBaseController extends Controller
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
        parent::__construct();
        // Inject currently logged in user object into every view of user dashboard
        $this->middleware(function ($request, $next) {

            $this->global = $this->company = company_setting();
            $this->invoiceSetting = invoice_setting();
            $this->superadmin = global_settings();

            $this->pushSetting = push_setting();
            $this->companyName = $this->global->company_name;

            $this->adminTheme = admin_theme();
            $this->languageSettings = language_setting();

            App::setLocale($this->global->locale);
            Carbon::setLocale($this->global->locale);
            setlocale(LC_TIME, $this->global->locale . '_' . strtoupper($this->global->locale));
            $this->setFileSystemConfigs();

            
            $this->isClient = User::withoutGlobalScope(CompanyScope::class)
                ->join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select('users.id', 'users.name', 'users.email', 'users.created_at')
                ->where('roles.name', 'client')
                ->where('role_user.user_id', user()->id)
                ->where('users.company_id', user()->company_id)
                ->first();

            $this->user = user();
            $this->unreadNotificationCount = count($this->user->unreadNotifications);

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

            $company = $this->global;
            $expireOn = $company->licence_expire_on;
            $currentDate = Carbon::now();

            $packageSettingData = package_setting();
            $this->packageSetting = ($packageSettingData->status == 'active') ? $packageSettingData : null;

            if ((!is_null($expireOn) && $expireOn->lessThan($currentDate))) {

                $this->checkLicense($company);
            }

            $this->modules = $this->user->modules;

            $this->unreadMessageCount = UserChat::where('to', $this->user->id)->where('message_seen', 'no')->count();
            $this->unreadTicketCount = Notification::where('notifiable_id', $this->user->id)
                ->where('type', 'App\Notifications\NewTicket')
                ->whereNull('read_at')
                ->count();

            $this->unreadExpenseCount = Notification::where('notifiable_id', $this->user->id)
                ->where('type', 'App\Notifications\NewExpenseAdmin')
                ->whereNull('read_at')
                ->count();

            $this->stickyNotes = StickyNote::where('user_id', $this->user->id)
                ->orderBy('updated_at', 'desc')
                ->get();

            $this->worksuitePlugins = worksuite_plugins();

            if (config('filesystems.default') == 's3') {
                $this->url = "https://" . config('filesystems.disks.s3.bucket') . ".s3.amazonaws.com/";
            }

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

    public function logTaskActivity($taskID, $userID, $text, $boardColumnId, $subTaskId = null)
    {
        $activity = new TaskHistory();
        $activity->task_id = $taskID;

        if (!is_null($subTaskId)) {
            $activity->sub_task_id = $subTaskId;
        }

        $activity->user_id = $userID;
        $activity->details = $text;
        $activity->board_column_id = $boardColumnId;
        $activity->save();
    }

    public function checkLicense($company)
    {
        $packageSettingData = PackageSetting::first();
        $packageSetting = ($packageSettingData->status == 'active') ? $packageSettingData : null;
        $packages = Package::all();

        $trialPackage = $packages->filter(function ($value, $key) {
            return $value->default == 'trial';
        })->first();

        $defaultPackage = $packages->filter(function ($value, $key) {
            return $value->default == 'yes';
        })->first();

        $otherPackage = $packages->filter(function ($value, $key) {
            return $value->default == 'no';
        })->first();

        if ($packageSetting && !is_null($trialPackage)) {
            $selectPackage = $trialPackage;
        } elseif ($defaultPackage)
            $selectPackage = $defaultPackage;
        else {
            $selectPackage = $otherPackage;
        }

        // Set default package for license expired companies.
        if ($selectPackage) {
            $currentPackage = $company->package;
            ModuleSetting::where('company_id', $company->id)->delete();

            $moduleInPackage = (array) json_decode($selectPackage->module_in_package);
            $clientModules = ['projects', 'tickets', 'invoices', 'estimates', 'events', 'tasks', 'messages', 'payments', 'contracts', 'notices'];
            if ($moduleInPackage) {
                foreach ($moduleInPackage as $module) {

                    if (in_array($module, $clientModules)) {
                        $moduleSetting = new ModuleSetting();
                        $moduleSetting->company_id = $company->id;
                        $moduleSetting->module_name = $module;
                        $moduleSetting->status = 'active';
                        $moduleSetting->type = 'client';
                        $moduleSetting->save();
                    }

                    $moduleSetting = new ModuleSetting();
                    $moduleSetting->company_id = $company->id;
                    $moduleSetting->module_name = $module;
                    $moduleSetting->status = 'active';
                    $moduleSetting->type = 'employee';
                    $moduleSetting->save();

                    $moduleSetting = new ModuleSetting();
                    $moduleSetting->company_id = $company->id;
                    $moduleSetting->module_name = $module;
                    $moduleSetting->status = 'active';
                    $moduleSetting->type = 'admin';
                    $moduleSetting->save();
                }
            }

            if ($currentPackage->default == 'trial' && !is_null($packageSetting) && !is_null($defaultPackage)) {
                $company->package_id = $defaultPackage->id;
                $company->licence_expire_on = null;
            } elseif ($packageSetting && !is_null($trialPackage)) {
                $company->package_id = $selectPackage->id;
                $noOfDays = (!is_null($packageSetting->no_of_days) && $packageSetting->no_of_days != 0) ? $packageSetting->no_of_days : 30;
                $company->licence_expire_on = Carbon::now()->addDays($noOfDays)->format('Y-m-d');
            } elseif (is_null($packageSetting) && !is_null($defaultPackage)) {
                $company->package_id = $defaultPackage->id;
                $company->licence_expire_on = null;
            }
            $company->status = 'license_expired';
            $company->save();

            if ($company->company_email) {
                $companyUser = auth()->user();
                $companyUser->notify(new LicenseExpire(($companyUser)));
            }
        }
    }
}
