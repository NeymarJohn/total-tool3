<?php

namespace App\Http\Controllers\Client;

use App\GdprSetting;
use App\LanguageSetting;
use App\MessageSetting;
use App\Notification;
use App\ProjectActivity;
use App\Setting;
use App\StickyNote;
use App\Traits\FileSystemSettingTrait;
use App\UserActivity;
use App\UserChat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TaskHistory;
use App\ThemeSetting;
use Illuminate\Support\Facades\App;

class ClientBaseController extends Controller
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
        return isset($this->data[ $name ]);
    }

    /**
     * UserBaseController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        // Inject currently logged in user object into every view of user dashboard

        $this->companyName = $this->global->company_name;

        $this->setFileSystemConfigs();

        $this->middleware(function ($request, $next) {

            $this->clientTheme = client_theme();
            $this->languageSettings = language_setting();
            $this->messageSetting = message_setting();
            $this->gdpr = GdprSetting::first();

            $this->user = user();
            $this->unreadNotificationCount = count($this->user->unreadNotifications);
            $this->modules = $this->user->modules;
            // dd($this->modules);
            // dd(in_array('meetings', $this->modules));
            $this->stickyNotes = StickyNote::where('user_id', $this->user->id)->orderBy('updated_at', 'desc')->get();

            App::setLocale($this->user->locale);
            Carbon::setLocale($this->user->locale);
            setlocale(LC_TIME,$this->user->locale.'_'.strtoupper($this->user->locale));

            $this->worksuitePlugins = worksuite_plugins();
            return $next($request);
        });

    }

    public function logProjectActivity($projectId, $text) {
        $activity = new ProjectActivity();
        $activity->project_id = $projectId;
        $activity->activity = $text;
        $activity->save();
    }

    public function logUserActivity($userId, $text) {
        $activity = new UserActivity();
        $activity->user_id = $userId;
        $activity->activity = $text;
        $activity->save();
    }

    public function logTaskActivity($taskID, $userID, $text, $boardColumnId)
    {
        $activity = new TaskHistory();
        $activity->task_id = $taskID;
        $activity->user_id = $userID;
        $activity->details = $text;
        $activity->board_column_id = $boardColumnId;
        $activity->save();
    }

}
