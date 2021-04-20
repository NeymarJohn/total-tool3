<?php

namespace App\Http\Controllers\Member;

use App\Helper\Reply;
use App\Http\Requests\TimeLogs\StoreTimeLog;
use App\Project;
use App\ProjectMember;
use App\ProjectTimeLog;
use App\Task;
use App\TaskUser;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MemberAllTimeLogController extends MemberBaseController
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

    public function index()
    {
        if ($this->user->can('view_timelogs')) {
            $this->projects = Project::where('projects.manual_timelog', 'enable')->get();
            $this->tasks = Task::all();
            $this->timeLogProjects = $this->projects;
            $this->timeLogTasks = [];
        } else {

            $this->projects = Project::join('project_members', 'project_members.project_id', '=', 'projects.id')
                ->select('projects.*')
                ->where('project_members.user_id', '=', $this->user->id)
                ->get();

            $this->tasks = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')
                ->where('task_users.user_id', '=', $this->user->id)
                ->select('tasks.*')
                ->get();

            $this->timeLogProjects = Project::join('project_members', 'project_members.project_id', '=', 'projects.id')
                ->select('projects.*')
                ->where('project_members.user_id', '=', $this->user->id)
                ->where('projects.manual_timelog', 'enable')
                ->get();

            $this->timeLogTasks = [];
        }

        $this->employees = User::allEmployees();

        return view('member.time-log.index', $this->data);
    }

    public function data(Request $request, $startDate = null, $endDate = null, $projectId = null, $employee = null)
    {
        $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
        $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
        $projectId = $request->projectId;
        $taskID = $request->taskID;
        $employee = $request->employee;


        $timeLogs = ProjectTimeLog::with('project', 'task')
            ->join('users', 'users.id', '=', 'project_time_logs.user_id')
            ->leftJoin('projects', 'projects.id', '=', 'project_time_logs.project_id');

        if (!$this->user->can('view_timelogs')) {
            $timeLogs->where('project_time_logs.user_id', $this->user->id);
        }

        $timeLogs->select('project_time_logs.id', 'project_time_logs.start_time', 'project_time_logs.end_time', 'project_time_logs.total_hours', 'project_time_logs.total_minutes', 'project_time_logs.memo', 'project_time_logs.project_id', 'project_time_logs.task_id', 'users.name', 'project_time_logs.approved');

        if (!is_null($startDate)) {
            $timeLogs->where(DB::raw('DATE(project_time_logs.`start_time`)'), '>=', $startDate);
        }

        if (!is_null($endDate)) {
            $timeLogs->where(function ($query) use ($endDate) {
                $query->where(DB::raw('DATE(project_time_logs.`end_time`)'), '<=', $endDate)
                    ->orWhereNull('project_time_logs.end_time');
            });
        }

        if (!is_null($employee) && $employee !== 'all') {
            $timeLogs->where('project_time_logs.user_id', $employee);
        }

        if ($projectId != 0) {
            $timeLogs->where('project_time_logs.project_id', '=', $projectId);
        }

        if ($taskID != 0) {
            $timeLogs->where('project_time_logs.task_id', '=', $taskID);
        }

        $timeLogs->orderBy('project_time_logs.id', 'desc')->get();;

        return DataTables::of($timeLogs)
            ->addColumn('action', function ($row) {

                if ($this->user->can('edit_timelogs') || $this->user->can('edit_timelogs') || $this->user->can('delete_timelogs')) {
                    $action = '<div class="btn-group dropdown m-r-10">
                    <button aria-expanded="false" data-toggle="dropdown" class="btn btn-default dropdown-toggle waves-effect waves-light" type="button"><i class="fa fa-gears "></i></button>
                    <ul role="menu" class="dropdown-menu pull-right">';

                    if ($this->user->can('edit_timelogs')) {
                        $action .= '<li><a href="javascript:;" class="edit-time-log" data-toggle="tooltip" data-time-id="' . $row->id . '"  data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> ' . trans('app.edit') . '</a></li>';
                    }

                    if ($this->user->can('delete_timelogs')) {
                        $action .= '<li> <a href="javascript:;" class="sa-params" data-toggle="tooltip" data-time-id="' . $row->id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i> ' . trans('app.delete') . '</a></li>';
                    }

                    if (!$row->approved) {
                        $action .= '<li> <a href="javascript:;" class="approve-timelog"
                        data-time-id="' . $row->id . '"><i class="fa fa-check" aria-hidden="true"></i> ' . trans('app.approve') . '</a></li>';
                    }

                    $action .= '</ul> </div>';
                    return $action;
                } else {
                    return "--";
                }
            })
            ->editColumn('start_time', function ($row) {
                return $row->start_time->timezone($this->global->timezone)->format($this->global->date_format . ' ' . $this->global->time_format);
            })
            ->editColumn('name', function ($row) {
                return ucwords($row->name);
            })
            ->editColumn('end_time', function ($row) {
                if (!is_null($row->end_time)) {
                    return $row->end_time->timezone($this->global->timezone)->format($this->global->date_format . ' ' . $this->global->time_format);
                } else {
                    return "<label class='label label-success'>" . __('app.active') . "</label>";
                }
            })
            ->editColumn('project_name', function ($row) {
                if (!is_null($row->project_id) && !is_null($row->task_id)) {
                    return '<span class="font-semi-bold">' . $row->task->heading . '</span><br><span class="text-muted">' . $row->project->project_name . '</span>';
                } else if (!is_null($row->project_id)) {
                    return '<span class="font-semi-bold">' . $row->project->project_name . '</span>';
                } else if (!is_null($row->task_id)) {
                    return '<span class="font-semi-bold">' . $row->task->heading . '</span>';
                }
            })
            ->editColumn('total_hours', function ($row) {
                $timeLog = intdiv($row->total_minutes, 60) . ' ' . __('app.hrs') . ' ';

                if (($row->total_minutes % 60) > 0) {
                    $timeLog .= ($row->total_minutes % 60) . ' ' . __('app.mins');
                }

                if ($row->approved) {
                    $timeLog .= ' <i data-toggle="tooltip" data-original-title="' . __('app.approved') . '" class="fa fa-check-circle text-success"></i>';
                } else {
                    $timeLog .= ' <i data-toggle="tooltip" data-original-title="' . __('app.pending') . '" class="fa fa-check-circle text-muted" ></i>';
                }


                return $timeLog;
            })
            ->rawColumns(['end_time', 'action', 'project_name', 'total_hours'])
            ->removeColumn('project_id')
            ->removeColumn('task_id')
            ->removeColumn('total_minutes')
            ->addIndexColumn()
            ->make(true);
    }

    public function destroy($id)
    {
        ProjectTimeLog::destroy($id);
        return Reply::success(__('messages.timeLogDeleted'));
    }

    public function store(StoreTimeLog $request)
    {

        $start_time = Carbon::parse($request->start_date)->format('Y-m-d') . ' ' . Carbon::parse($request->start_time)->format('H:i:s');
        $start_time = Carbon::createFromFormat('Y-m-d H:i:s', $start_time, $this->global->timezone)->setTimezone('UTC');
        $end_time = Carbon::parse($request->end_date)->format('Y-m-d') . ' ' . Carbon::parse($request->end_time)->format('H:i:s');
        $end_time = Carbon::createFromFormat('Y-m-d H:i:s', $end_time, $this->global->timezone)->setTimezone('UTC');


        $activeTimer = ProjectTimeLog::with('user')
            ->where(function ($query) use ($start_time, $end_time) {
                $query->whereBetween('end_time', [$start_time, $end_time])
                    ->orWhereNull('end_time');
            })
            ->join('users', 'users.id', '=', 'project_time_logs.user_id')
            ->where('user_id', $request->user_id)->first();

        if (is_null($activeTimer)) {
            $timeLog = new ProjectTimeLog();

            $timeLog->task_id = $request->task_id;
            $timeLog->user_id = $request->user_id;
            $timeLog->project_id = $request->project_id;

            $timeLog->start_time = Carbon::parse($request->start_date)->format('Y-m-d') . ' ' . Carbon::parse($request->start_time)->format('H:i:s');
            $timeLog->start_time = Carbon::createFromFormat('Y-m-d H:i:s', $timeLog->start_time, $this->global->timezone)->setTimezone('UTC');
            $timeLog->end_time = Carbon::parse($request->end_date)->format('Y-m-d') . ' ' . Carbon::parse($request->end_time)->format('H:i:s');
            $timeLog->end_time = Carbon::createFromFormat('Y-m-d H:i:s', $timeLog->end_time, $this->global->timezone)->setTimezone('UTC');
            $timeLog->total_hours = $timeLog->end_time->diff($timeLog->start_time)->format('%d') * 24 + $timeLog->end_time->diff($timeLog->start_time)->format('%H');
            $timeLog->total_minutes = ($timeLog->total_hours * 60) + ($timeLog->end_time->diff($timeLog->start_time)->format('%i'));


            $timeLog->memo = $request->memo;
            $timeLog->edited_by_user = $this->user->id;
            $timeLog->save();

            return Reply::success(__('messages.timeLogAdded'));
        }

        return Reply::error(__('messages.timelogAlreadyExist'));
    }

    public function edit($id)
    {
        $this->timeLog = ProjectTimeLog::findOrFail($id);
        $this->project = Project::findOrFail($this->timeLog->project_id);
        return view('member.time-log.edit', $this->data);
    }


    public function update(StoreTimeLog $request, $id)
    {
        $timeLog = ProjectTimeLog::findOrFail($id);

        if ($timeLog->task_id != null) {
            $timeLog->user_id = $request->user_id;
        } else {

            $timeLog->user_id = $request->user_id;
        }
        $timeLog->start_time = Carbon::parse($request->start_date)->format('Y-m-d') . ' ' . Carbon::parse($request->start_time)->format('H:i:s');
        $timeLog->start_time = Carbon::createFromFormat('Y-m-d H:i:s', $timeLog->start_time, $this->global->timezone)->setTimezone('UTC');
        $timeLog->end_time = Carbon::parse($request->end_date)->format('Y-m-d') . ' ' . Carbon::parse($request->end_time)->format('H:i:s');
        $timeLog->end_time = Carbon::createFromFormat('Y-m-d H:i:s', $timeLog->end_time, $this->global->timezone)->setTimezone('UTC');
        $timeLog->total_hours = $timeLog->end_time->diff($timeLog->start_time)->format('%d') * 24 + $timeLog->end_time->diff($timeLog->start_time)->format('%H');
        $timeLog->total_minutes = ($timeLog->total_hours * 60) + ($timeLog->end_time->diff($timeLog->start_time)->format('%i'));

        $timeLog->memo = $request->memo;
        $timeLog->edited_by_user = $this->user->id;
        $timeLog->save();

        return Reply::success(__('messages.timeLogUpdated'));
    }

    public function membersList($projectId)
    {
        if ($this->user->can('add_timelogs')) {
            if ($projectId == "0") {
                $this->employees = User::allEmployees();
                $this->tasks = Task::all();
            } else {
                $this->members = ProjectMember::byProject($projectId);
                $this->tasks = Task::where('project_id', $projectId)->get();
            }
        } else {
            if ($projectId == "0") {
                $this->members = ProjectMember::where('user_id', $this->user->id)
                    ->get();
                $this->tasks = Task::join('projects', 'projects.id', '=', 'tasks.project_id')
                    ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
                    ->join('users', 'task_users.user_id', '=', 'users.id')
                    ->where('task_users.user_id', $this->user->id)
                    ->select('tasks.*')
                    ->groupBy('tasks.id')
                    ->get();
            } else {
                $this->members = ProjectMember::where('project_id', $projectId)
                    ->where('user_id', $this->user->id)
                    ->get();
                $this->tasks = Task::join('projects', 'projects.id', '=', 'tasks.project_id')
                    ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
                    ->join('users', 'task_users.user_id', '=', 'users.id')
                    ->where('tasks.project_id', $projectId)
                    ->where('task_users.user_id', $this->user->id)
                    ->select('tasks.*')
                    ->groupBy('tasks.id')
                    ->get();
            }
        }
        $list = view('member.all-tasks.members-list', $this->data)->render();
        $tasks = view('admin.tasks.tasks-list', $this->data)->render();
        return Reply::dataOnly(['html' => $list, 'tasks' => $tasks]);
    }

    public function taskMembersList($taskId)
    {
        $this->members = TaskUser::where('task_id', $taskId)->get();

        $list = view('member.all-tasks.members-list', $this->data)->render();
        return Reply::dataOnly(['html' => $list]);
    }

    public function showActiveTimer()
    {
        $this->activeTimers = ProjectTimeLog::with('user', 'project', 'task')
            ->whereNull('end_time')
            ->join('users', 'users.id', '=', 'project_time_logs.user_id');


        $this->activeTimers = $this->activeTimers
            ->select('project_time_logs.*', 'users.name')
            ->get();

        return view('member.time-log.show-active-timer', $this->data);
    }

    public function stopTimer(Request $request)
    {
        $timeId = $request->timeId;
        $timeLog = ProjectTimeLog::findOrFail($timeId);
        $timeLog->end_time = Carbon::now();
        $timeLog->edited_by_user = $this->user->id;
        $timeLog->save();

        $timeLog->total_hours = ($timeLog->end_time->diff($timeLog->start_time)->format('%d') * 24) + ($timeLog->end_time->diff($timeLog->start_time)->format('%H'));

        $timeLog->total_hours = $timeLog->end_time->diff($timeLog->start_time)->format('%d') * 24 + $timeLog->end_time->diff($timeLog->start_time)->format('%H');
        $timeLog->total_minutes = ($timeLog->total_hours * 60) + ($timeLog->end_time->diff($timeLog->start_time)->format('%i'));

        $timeLog->save();

        $this->activeTimers = ProjectTimeLog::whereNull('end_time')
            ->get();
        $view = view('member.time-log.active-timers', $this->data)->render();
        $buttonHtml = '';
        if ($timeLog->user_id == $this->user->id) {
            $buttonHtml = '<div class="nav navbar-top-links navbar-right pull-right m-t-10">
                        <a class="btn btn-rounded btn-default timer-modal" href="javascript:;">' . __("modules.timeLogs.startTimer") . ' <i class="fa fa-check-circle text-success"></i></a>
                    </div>';
        }
        return Reply::successWithData(__('messages.timerStoppedSuccessfully'), ['html' => $view, 'buttonHtml' => $buttonHtml, 'activeTimers' => count($this->activeTimers)]);
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
}
