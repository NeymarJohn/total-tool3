<?php

namespace App\Http\Controllers\Member;

use App\Attendance;
use App\AttendanceSetting;
use App\Holiday;
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

        // Getting Attendance setting data
        $this->attendanceSettings = AttendanceSetting::first();

        //Getting Maximum Check-ins in a day
        $this->maxAttendanceInDay = $this->attendanceSettings->clockin_in_day;
    }

    public function index()
    {
        $this->languageSettings = language_setting();
        $this->timer = ProjectTimeLog::memberActiveTimer($this->user->id);
        $completedTaskColumn = TaskboardColumn::completeColumn();

        $this->totalProjects = Project::select('projects.id')
            ->join('project_members', 'project_members.project_id', '=', 'projects.id');

        if (!$this->user->can('view_projects')) {
            $this->totalProjects = $this->totalProjects->where('project_members.user_id', '=', $this->user->id);
        }

        $this->totalProjects =  $this->totalProjects->groupBy('projects.id');
        $this->totalProjects = count($this->totalProjects->get());
        $this->counts = DB::table('users')
            ->select(
                DB::raw('(select IFNULL(sum(project_time_logs.total_minutes),0) from `project_time_logs` where user_id = ' . $this->user->id . ') as totalHoursLogged '),
                DB::raw('(select count(tasks.id) from `tasks` inner join task_users on task_users.task_id=tasks.id where tasks.board_column_id=' . $completedTaskColumn->id . ' and task_users.user_id = ' . $this->user->id . ') as totalCompletedTasks'),
                DB::raw('(select count(tasks.id) from `tasks` inner join task_users on task_users.task_id=tasks.id where tasks.board_column_id!=' . $completedTaskColumn->id . ' and task_users.user_id = ' . $this->user->id . ') as totalPendingTasks')
            )
            ->first();

        $timeLog = intdiv($this->counts->totalHoursLogged, 60) . ' hrs ';

        if (($this->counts->totalHoursLogged % 60) > 0) {
            $timeLog .= ($this->counts->totalHoursLogged % 60) . ' mins';
        }

        $this->counts->totalHoursLogged = $timeLog;

        $this->projectActivities = ProjectActivity::join('projects', 'projects.id', '=', 'project_activity.project_id')
            ->join('project_members', 'project_members.project_id', '=', 'projects.id');

        if (!$this->user->can('view_projects')) {
            $this->projectActivities = $this->projectActivities->where('project_members.user_id', '=', $this->user->id);
        }

        $this->projectActivities = $this->projectActivities->whereNull('projects.deleted_at')
            ->select('projects.project_name', 'project_activity.created_at', 'project_activity.activity', 'project_activity.project_id')
            ->limit(15)->orderBy('project_activity.id', 'desc')->groupBy('project_activity.id')->get();

        if ($this->user->can('view_notice')) {
            $this->notices = Notice::latest()->get();
        }

        $this->userActivities = UserActivity::with('user')->limit(15)->orderBy('id', 'desc');

        if (!$this->user->can('view_employees')) {
            $this->userActivities = $this->userActivities->where('user_id', $this->user->id);
        }

        $this->userActivities = $this->userActivities->get();

        $this->pendingTasks = Task::with('project')
            ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->where('tasks.board_column_id', '<>', $completedTaskColumn->id)
            ->where(DB::raw('DATE(due_date)'), '<=', Carbon::today()->format('Y-m-d'))
            ->where('task_users.user_id', $this->user->id)
            ->select('tasks.*')
            ->groupBy('tasks.id')
            ->limit(15)
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
            // $this->noClockIn = true;
        }

        if ($this->user->can('view_timelogs') && in_array('timelogs', $this->user->modules)) {

            $this->activeTimerCount = ProjectTimeLog::with('user', 'project', 'task')
                ->whereNull('end_time')
                ->join('users', 'users.id', '=', 'project_time_logs.user_id');

            $this->activeTimerCount = $this->activeTimerCount
                ->select('project_time_logs.*', 'users.name')
                ->count();
        }

        $this->languageSettings = language_setting();

        return view('member.dashboard.index', $this->data);
    }
}
