<?php

namespace App\Http\Controllers\Admin;

use App\ProjectActivity;
use App\Traits\FileSystemSettingTrait;
use App\UniversalSearch;
use App\UserActivity;
use App\Http\Controllers\Controller;
use App\TaskHistory;
use Pusher\Pusher;

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

        $this->companyName = $this->global->company_name;

        $this->middleware(function ($request, $next) {
            $this->setFileSystemConfigs();

            $this->languageSettings = language_setting();
            $this->adminTheme = admin_theme();
            $this->pushSetting = push_setting();
            $this->smtpSetting = smtp_setting();
            $this->pusherSettings = pusher_settings();
            $this->mainMenuSettings = main_menu_settings();
            $this->subMenuSettings = sub_menu_settings();
            $this->invoiceSetting = invoice_setting();
            $this->menuInnerSettingMenu = $this->innerSettingMenu();

            $this->user = user();
            $this->modules = $this->user->modules;
            $this->unreadNotificationCount = count($this->user->unreadNotifications);

            $this->stickyNotes = $this->user->sticky;

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

    public function innerSettingMenu()
    {
        $route = \Illuminate\Support\Facades\Route::currentRouteName();
        $data = [];
        foreach($this->subMenuSettings as $menu) {
            if($menu['route'] == $route) {
                $data = $menu;
                break;
            }

            if(isset($menu['children'])) {
                foreach($menu['children'] as $subMenu) {
                    if($route == $subMenu['route']) {
                        $data = $menu;
                            break;
                    }
                }
            }
        }

        return $data;
    }


}
