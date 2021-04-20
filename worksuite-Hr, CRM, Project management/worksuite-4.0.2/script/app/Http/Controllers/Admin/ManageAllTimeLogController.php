<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\ActiveTimeLogsDataTable;
use App\DataTables\Admin\AllTimeLogsDataTable;
use App\Helper\Reply;
use App\Project;
use App\ProjectMember;
use App\ProjectTimeLog;
use App\Task;
use App\TaskUser;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ManageAllTimeLogController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.timeLogs';
        $this->pageIcon = 'icon-clock';
        $this->middleware(function ($request, $next) {
            if (!in_array('timelogs', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index(AllTimeLogsDataTable $dataTable)
    {
        if (!request()->ajax()) {
            $this->employees = User::allEmployees();
            $this->projects = Project::allProjects();
            $this->timeLogProjects = $this->projects;
            $this->tasks = Task::all();
            $this->timeLogTasks = $this->tasks;

            $this->activeTimers = ProjectTimeLog::with('user')
                ->whereNull('end_time')
                ->join('users', 'users.id', '=', 'project_time_logs.user_id')
                ->select('project_time_logs.*', 'users.name')
                ->count();

            $this->startDate = Carbon::today()->subDays(7)->format($this->global->date_format);
            $this->endDate = Carbon::today()->format($this->global->date_format);
        }
        return $dataTable->render('admin.time-logs.index', $this->data);
    }

    public function byEmployee()
    {
        $this->employees = User::allEmployees();
        $this->projects = Project::allProjects();
        $this->timeLogProjects = $this->projects;
        $this->tasks = Task::all();
        $this->timeLogTasks = $this->tasks;

        $this->activeTimers = ProjectTimeLog::with('user')
            ->whereNull('end_time')
            ->join('users', 'users.id', '=', 'project_time_logs.user_id')
            ->select('project_time_logs.*', 'users.name')
            ->count();

        $this->startDate = Carbon::today()->subDays(7)->format($this->global->date_format);
        $this->endDate = Carbon::today()->format($this->global->date_format);
        return view('admin.time-logs.by_employee', $this->data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showActiveTimer()
    {
        $this->activeTimers = ProjectTimeLog::with('user', 'project', 'task')
            ->whereNull('end_time')
            ->join('users', 'users.id', '=', 'project_time_logs.user_id');

        $this->activeTimers = $this->activeTimers
            ->select('project_time_logs.*', 'users.name', 'users.image')
            ->get();

        return view('admin.time-logs.show-active-timer', $this->data);
    }

    public function destroy($id)
    {
        ProjectTimeLog::destroy($id);
        return Reply::success(__('messages.timeLogDeleted'));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function stopTimer(Request $request)
    {
        $timeId = $request->timeId;
        $timeLog = ProjectTimeLog::findOrFail($timeId);
        $timeLog->end_time = Carbon::now();
        $timeLog->save();

        $timeLog->total_hours = $timeLog->end_time->diff($timeLog->start_time)->format('%d') * 24 + $timeLog->end_time->diff($timeLog->start_time)->format('%H');
        $timeLog->total_minutes = ($timeLog->total_hours * 60) + ($timeLog->end_time->diff($timeLog->start_time)->format('%i'));
        $timeLog->edited_by_user = $this->user->id;
        $timeLog->save();

        if (!is_null($timeLog->project_id)) {
            $this->logProjectActivity($timeLog->project_id, __('messages.timerStoppedBy') . ' ' . ucwords(user()->name));
        }

        $this->activeTimers = ProjectTimeLog::whereNull('end_time')->get();
        $view = view('admin.projects.time-logs.active-timers', $this->data)->render();
        return Reply::successWithData(__('messages.timerStoppedSuccessfully'), ['html' => $view, 'activeTimers' => count($this->activeTimers)]);
    }

    /**
     * @param $projectId
     * @return mixed
     * @throws \Throwable
     */
    public function membersList($projectId)
    {
        if ($projectId == "0") {
            $this->employees = User::allEmployees();
            $this->tasks = Task::all();
        } else {
            $this->members = ProjectMember::byProject($projectId);
            $this->tasks = Task::where('project_id', $projectId)->get();
        }

        $list = view('admin.tasks.members-list', $this->data)->render();
        $tasks = view('admin.tasks.tasks-list', $this->data)->render();
        return Reply::dataOnly(['html' => $list, 'tasks' => $tasks]);
    }

    /**
     * @param $projectId
     * @return mixed
     * @throws \Throwable
     */
    public function taskMembersList($taskId)
    {

        $this->members = TaskUser::where('task_id', $taskId)->get();

        $list = view('admin.tasks.members-list', $this->data)->render();
        return Reply::dataOnly(['html' => $list]);
    }

    public function data(Request $request)
    {
        $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
        $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
        $employee = $request->employee;
        $projectId = $request->projectID;
        $taskId = $request->taskID;

        $this->employees = User::join('employee_details', 'users.id', '=', 'employee_details.user_id')
            ->leftJoin('project_time_logs', 'project_time_logs.user_id', '=', 'users.id')
            ->leftJoin('designations', 'employee_details.designation_id', '=', 'designations.id');

        $where = '';

        if ($projectId != "all") {
            $where .= ' and project_time_logs.project_id="' . $projectId . '" ';
        }

        if ($taskId != "all") {
            $where .= ' and project_time_logs.task_id="' . $taskId . '" ';
        }

        $this->employees = $this->employees->select(
            'users.name',
            'users.image',
            'users.id',
            'designations.name as designation_name',
            DB::raw(
                "(SELECT SUM(project_time_logs.total_minutes) FROM project_time_logs WHERE project_time_logs.user_id = users.id and DATE(project_time_logs.start_time) >= '" . $startDate . "' and DATE(project_time_logs.start_time) <= '" . $endDate . "' $where GROUP BY project_time_logs.user_id) as total_minutes"
            ),
            DB::raw(
                "(SELECT SUM(project_time_logs.earnings) FROM project_time_logs WHERE project_time_logs.user_id = users.id and DATE(project_time_logs.start_time) >= '" . $startDate . "' and DATE(project_time_logs.start_time) <= '" . $endDate . "' $where GROUP BY project_time_logs.user_id) as earnings"
            )
        );

        if (!is_null($employee) && $employee !== 'all') {
            $this->employees = $this->employees->where('project_time_logs.user_id', $employee);
        }

        if (!is_null($projectId) && $projectId !== 'all') {
            $this->employees = $this->employees->where('project_time_logs.project_id', '=', $projectId);
        }

        if (!is_null($taskId) && $taskId !== 'all') {
            $this->employees = $this->employees->where('project_time_logs.task_id', '=', $taskId);
        }

        $this->employees = $this->employees->groupBy('project_time_logs.user_id')
            ->orderBy('users.name')
            ->get();
        $html = view('admin.time-logs.members-list', $this->data)->render();
        return Reply::dataOnly(['html' => $html]);
    }

    public function userTimelogs(Request $request)
    {
        $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
        $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
        $employee = $request->employee;
        $projectId = $request->projectID;
        $taskId = $request->taskID;

        $this->timelogs = ProjectTimeLog::with('project', 'task')->select('*')
            ->whereDate('start_time', '>=', $startDate)
            ->whereDate('start_time', '<=', $endDate)
            ->whereNotNull('end_time')
            ->where('user_id', $employee);

        if ($projectId != "all") {
            $this->timelogs = $this->timelogs->where('project_id', $projectId);
        }

        if ($taskId != "all") {
            $this->timelogs = $this->timelogs->where('task_id', $taskId);
        }

        $this->timelogs = $this->timelogs->orderBy('end_time', 'desc')
            ->get();

        $html = view('admin.time-logs.user-timelogs', $this->data)->render();
        return Reply::dataOnly(['html' => $html]);
    }

    public function approveTimelog(Request $request)
    {
        ProjectTimeLog::where('id', $request->id)->update(
            [
                'approved' => 1,
                'approved_by' => user()->id
            ]
        );
        return Reply::dataOnly(['status' => 'success']);
    }

    public function activeTimelogs(ActiveTimeLogsDataTable $dataTable)
    {
        $this->pageTitle = 'modules.projects.activeTimers';
        
        if (!request()->ajax()) {
            $this->employees = User::allEmployees();
            $this->projects = Project::all();
            $this->timeLogProjects = $this->projects;
            $this->tasks = Task::all();
            $this->timeLogTasks = $this->tasks;

            $this->activeTimers = ProjectTimeLog::with('user')
                ->whereNull('end_time')
                ->join('users', 'users.id', '=', 'project_time_logs.user_id')
                ->select('project_time_logs.*', 'users.name')
                ->count();
        }
        return $dataTable->render('admin.time-logs.active', $this->data);
    }

    /**
     * calendar view
     *
     * @return \Illuminate\Http\Response
     */
    public function calendar()
    {
        if (request('start') && request('end')) {
            $startDate = Carbon::parse(request('start'))->format('Y-m-d');
            $endDate = Carbon::parse(request('end'))->format('Y-m-d');

          $timelogs = ProjectTimeLog::select(
                DB::raw('sum(total_minutes) as total_minutes'), 
                DB::raw("DATE_FORMAT(start_time,'%Y-%m-%d') as start")
            )
            ->where('approved', 1)
            ->whereNotNull('end_time')
            ->whereDate('start_time', '>=', $startDate)
            ->whereDate('start_time', '<=', $endDate)
            ->groupBy('start')
            ->get();

            $calendarData = array();

            foreach ($timelogs as $key => $value) {
                $calendarData[] = [
                    'id' => $key+1,
                    'title' => $value->hours,
                    'start' => $value->start
                ];
            }
            return $calendarData;

        }

        return view('admin.time-logs.calendar', $this->data);
    }
    
}
