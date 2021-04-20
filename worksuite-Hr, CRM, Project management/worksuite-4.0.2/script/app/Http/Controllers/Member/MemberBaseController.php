<?php

namespace App\Http\Controllers\Member;

use App\EmailNotificationSetting;
use App\Notification;
use App\ProjectActivity;
use App\ProjectTimeLog;
use App\Role;
use App\Setting;
use App\StickyNote;
use App\Traits\FileSystemSettingTrait;
use App\UniversalSearch;
use App\UserActivity;
use App\UserChat;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\TaskHistory;
use Illuminate\Support\Facades\App;
use App\ThemeSetting;
use Pusher\Pusher;

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
        parent::__construct();

        $this->companyName = $this->global->company_name;

        $this->setFileSystemConfigs();

        $this->middleware(function ($request, $next) {
            $this->employeeTheme = employee_theme();
            $this->pushSetting = push_setting();
            $this->pusherSettings = pusher_settings();

            $this->user = user();
            $this->unreadNotificationCount = count($this->user->unreadNotifications);
            $this->modules = $this->user->modules;

            App::setLocale($this->user->locale);
            Carbon::setUtf8(true);
            Carbon::setLocale($this->user->locale);
            setlocale(LC_TIME, $this->user->locale . '_' . strtoupper($this->user->locale));

            $userRole = cache()->remember(
                'user-roles-'.$this->user->id, 60*60, function () {
                    return $this->user->role; // Getting users all roles
                }
            );

            if (count($userRole) > 1) {
                $roleId = $userRole[1]->role_id;
            } // if single role assign getting role ID
            else {
                $roleId = $userRole[0]->role_id;
            } // if multiple role assign getting role ID

            // Getting role detail by ID that got above according single or multiple roles assigned.
            $this->userRole = cache()->remember(
                'roles-'.$roleId, 60*60, function () use ($roleId) {
                    return Role::where('id', $roleId)->first();
                }
            );

            $this->stickyNotes = cache()->remember(
                'sticky-user-'.$this->user->id, 60*60, function () {
                    return StickyNote::where('user_id', $this->user->id)->orderBy('updated_at', 'desc')->get();
                }
            );


            $this->worksuitePlugins = worksuite_plugins();
            $this->invoiceSetting = invoice_setting();

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

    public function triggerPusher($channel, $event, $data)
    {
        if ($this->pusherSettings->status) {
            $pusher = new Pusher($this->pusherSettings->pusher_app_key, $this->pusherSettings->pusher_app_secret, $this->pusherSettings->pusher_app_id, array('cluster' => $this->pusherSettings->pusher_cluster, 'useTLS' => $this->pusherSettings->force_tls));
            $pusher->trigger($channel, $event, $data);
        }
    }
}
