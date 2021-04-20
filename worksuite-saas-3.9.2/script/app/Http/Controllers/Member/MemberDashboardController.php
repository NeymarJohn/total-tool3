<?php

namespace App\Http\Controllers\Member;

use App\Attendance;
use App\AttendanceSetting;
use App\Holiday;
use App\LanguageSetting;
use App\Notice;
use App\Project;
use App\ProjectActivity;
use App\ProjectTimeLog;
use App\Task;
use App\TaskboardColumn;
use App\UserActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MemberDashboardController extends MemberBaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->pageTitle = 'app.menu.dashboard';
        $this->pageIcon = 'icon-speedometer';
    }

    public function index()
    {
        $this->languageSettings = LanguageSetting::where('status', 'enabled')->get();
        $this->timer = ProjectTimeLog::memberActiveTimer($this->user->id);
        // Getting Attendance setting data
        $this->attendanceSettings = AttendanceSetting::first();
        //Getting Maximum Check-ins in a day
        $this->maxAttandenceInDay = $this->attendanceSettings->clockin_in_day;

        $this->totalProjects = Project::select('projects.id')
            ->join('project_members', 'project_members.project_id', '=', 'projects.id');

        if (!$this->user->cans('view_projects')) {
            $this->totalProjects = $this->totalProjects->where('project_members.user_id', '=', $this->user->id);
        }

        $taskBoardColumn = TaskboardColumn::all();

        $incompletedTaskColumn = $taskBoardColumn->filter(function ($value, $key) {
            return $value->slug == 'incomplete';
        })->first();

        $completedTaskColumn = $taskBoardColumn->filter(function ($value, $key) {
            return $value->slug == 'completed';
        })->first();

        $this->totalProjects = $this->totalProjects->count();
        $this->counts = DB::table('users')

            ->select(
                DB::raw('(select IFNULL(sum(project_time_logs.total_minutes),0) from `project_time_logs` where user_id = ' . $this->user->id . ') as totalHoursLogged '),
                DB::raw('(select count(tasks.id) from `tasks` inner join task_users on task_users.task_id=tasks.id where tasks.board_column_id=' . $completedTaskColumn->id . ' and task_users.user_id = ' . $this->user->id . ') as totalCompletedTasks'),
                DB::raw('(select count(tasks.id) from `tasks` inner join task_users on task_users.task_id=tasks.id where tasks.board_column_id=' . $incompletedTaskColumn->id . ' and task_users.user_id = ' . $this->user->id . ') as totalPendingTasks')
            )
            ->first();

        $timeLog = intdiv($this->counts->totalHoursLogged, 60) . ' ' . __('modules.hrs'). ' ';

        if (($this->counts->totalHoursLogged % 60) > 0) {
            $timeLog .= ($this->counts->totalHoursLogged % 60) . ' ' . __('modules.mins');
        }

        $this->counts->totalHoursLogged = $timeLog;

        $this->projectActivities = ProjectActivity::join('projects', 'projects.id', '=', 'project_activity.project_id')
            ->join('project_members', 'project_members.project_id', '=', 'projects.id')
            ->where('project_members.user_id', '=', $this->user->id)
            ->whereNull('projects.deleted_at')
            ->select('projects.project_name', 'project_activity.created_at', 'project_activity.activity', 'project_activity.project_id')
            ->limit(15)->orderBy('project_activity.id', 'desc')->get();

        $this->userActivities = UserActivity::limit(15)->orderBy('id', 'desc')->where('user_id', $this->user->id)->get();

        $this->pendingTasks = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->where('tasks.board_column_id', $incompletedTaskColumn->id)
            ->where(DB::raw('DATE(due_date)'), '<=', Carbon::today()->format('Y-m-d'))
            ->where('task_users.user_id', $this->user->id)
            ->select('tasks.*')
            ->groupBy('tasks.id')
            ->get();


        // Getting Current Clock-in if exist
        $this->currenntClockIn = Attendance::where(DB::raw('DATE(clock_in_time)'), Carbon::today()->format('Y-m-d'))
            ->where('user_id', $this->user->id)->whereNull('clock_out_time')->first();
        // Getting Today's Total Check-ins
        $this->todayTotalClockin = Attendance::where(DB::raw('DATE(clock_in_time)'), Carbon::today()->format('Y-m-d'))
            ->where('user_id', $this->user->id)->where(DB::raw('DATE(clock_out_time)'), Carbon::today()->format('Y-m-d'))->count();

        $currentDate = Carbon::now()->format('Y-m-d');

        // Check Holiday by date
        $this->checkTodayHoliday = Holiday::where('date', $currentDate)->first();

        //check office time passed
        $officeEndTime = Carbon::createFromFormat('H:i:s', $this->attendanceSettings->office_end_time, $this->global->timezone)->timestamp;
        $currentTime = Carbon::now()->timezone($this->global->timezone)->timestamp;
        if ($officeEndTime < $currentTime) {
            $this->noClockIn = true;
        }


        if ($this->user->cans('view_timelogs') && in_array('timelogs', $this->user->modules)) {

            $this->activeTimerCount = ProjectTimeLog::with('user', 'project', 'task')
                ->whereNull('end_time')
                ->join('users', 'users.id', '=', 'project_time_logs.user_id');

            $this->activeTimerCount = $this->activeTimerCount
                ->select('project_time_logs.*', 'users.name')
                ->count();
        }

        if ($this->user->cans('view_notice') && in_array('notices', $this->user->modules)) {
            $this->notices = Notice::limit(8)->latest()->get();
        }

        return view('member.dashboard.index', $this->data);
    }
}
