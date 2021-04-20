<?php

namespace App\Http\Controllers\Member;

use App\Helper\Reply;
use App\Http\Requests\TimeLogs\StartTimer;
use App\Http\Requests\TimeLogs\StoreTimeLog;
use App\Project;
use App\ProjectMember;
use App\ProjectTimeLog;
use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MemberTimeLogController extends MemberBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = 'icon-layers';
        $this->pageTitle = 'app.menu.projects';
        $this->middleware(function ($request, $next) {
            abort_if(!in_array('timelogs', $this->user->modules),403);
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->tasks = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->where('task_users.user_id', '=', $this->user->id)
            ->select('tasks.*')
            ->get();
        $this->projects = ProjectMember::with('project')->where('user_id', $this->user->id)->get();

        return view('member.time-log.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StartTimer $request)
    {
        $timeLog = new ProjectTimeLog();

        $activeTimer = ProjectTimeLog::with('user')
            ->whereNull('end_time')
            ->join('users', 'users.id', '=', 'project_time_logs.user_id')
            ->where('user_id', $this->user->id)->first();

        if (is_null($activeTimer)) {
            $taskId = $request->task_id;
            if ($request->has('create_task')) {
                $task = new Task();
                $task->heading = $request->memo;
                $task->board_column_id = $this->global->default_task_status;
                $task->is_private = $request->has('is_private') && $request->is_private == 'true' ? 1 : 0;
                $task->start_date = Carbon::now($this->global->timezone)->format('Y-m-d');
                $task->due_date = Carbon::now($this->global->timezone)->format('Y-m-d');

                if ($request->project_id != "") {
                    $task->project_id = $request->project_id;
                }
                $task->save();
                $taskId = $task->id;
            }
            
            if ($request->project_id != "") {
                $timeLog->project_id = $request->project_id;
            }
            $timeLog->task_id = $taskId;

            $timeLog->user_id = $this->user->id;
            $timeLog->start_time = Carbon::now()->timezone('UTC');
            $timeLog->memo = $request->memo;
            $timeLog->save();

            $this->logUserActivity($this->user->id, __('messages.timerStartedTask') . ucwords($timeLog->task->heading));
            if ($request->project_id != "") {
                $this->logProjectActivity($request->project_id, __('messages.timerStartedBy') . ' ' . ucwords($timeLog->user->name));
                $this->logUserActivity($this->user->id, __('messages.timerStartedProject') . ucwords($timeLog->project->project_name));
            }
            return Reply::successWithData(__('messages.timerStartedSuccessfully'), ['html' => '<div class="nav navbar-top-links navbar-right pull-right m-t-10">
                        <a class="btn btn-rounded btn-default stop-timer-modal" href="javascript:;" data-timer-id="' . $timeLog->id . '">
                            <i class="ti-alarm-clock"></i>
                            <span id="active-timer">' . $timeLog->timer . '</span>
                            <label class="label label-danger">' . __("app.stop") . '</label></a>
                    </div>']);
        }

        return Reply::error(__('messages.timerAlreadyRunning'));
    }

    // Store time log for task or project from project/timelog
    public function storeTimeLog(StoreTimeLog $request)
    {
        $timeLog = new ProjectTimeLog();

        $timeLog->project_id = $request->project_id;
        $timeLog->task_id = $request->task_id;
        $timeLog->user_id = $request->user_id;

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->timeLog = ProjectTimeLog::findOrFail($id);

        return view('member.time-log.show', $this->data);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showTomeLog($id)
    {
        $this->project = Project::findOrFail($id);

        $this->tasks = Task::where('project_id', $id)->get();

        return view('member.time-log.show-log', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $this->timeLog = ProjectTimeLog::findOrFail($id);

        if ($this->timeLog->task_id) {
            $this->task = Task::with('project', 'project.members')->findOrFail($this->timeLog->task_id);
            $this->tasks = Task::where('project_id', $this->task->project_id)->get();
        } else {
            $this->project =  Project::findOrFail($this->timeLog->project_id);
            $this->timeLogProjects = Project::all();
        }

        return view('member.time-log.edit-project-log', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
            $this->logProjectActivity($timeLog->project_id, __('messages.timerStoppedBy') . ' ' . ucwords($timeLog->user->name));
        }

        return Reply::successWithData(__('messages.timerStoppedSuccessfully'), ['html' => '<div class="nav navbar-top-links navbar-right pull-right m-t-10">
                        <a class="btn btn-rounded btn-default timer-modal" href="javascript:;">' . __("modules.timeLogs.startTimer") . ' <i class="fa fa-check-circle text-success"></i></a>
                    </div>']);
    }

    // Update time log for task or project from project/timelog
    public function updateTimeLog(StoreTimeLog $request, $id)
    {
        $timeLog = ProjectTimeLog::findOrFail($id);

        $timeLog->user_id = $request->user_id;
        if ($request->has('task_id')) {
            $timeLog->task_id = $request->task_id;
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param $id
     * @return mixed
     */
    public function data($id)
    {
        $timeLogs = ProjectTimeLog::with(['user', 'editor'])->where('project_id', $id);

        if (!$this->user->cans('view_timelogs')) {
            $timeLogs = $timeLogs->where('project_time_logs.user_id', $this->user->id);
        }

        $timeLogs = $timeLogs->orderBy('id', 'desc')->get();


        return DataTables::of($timeLogs)
            ->addColumn('action', function ($row) {
                $action = '';
                if ($this->user->cans('edit_timelogs')) {
                    $action .= '<a href="javascript:;" class="btn btn-info btn-circle edit-time-log"
                      data-toggle="tooltip" data-time-id="' . $row->id . '"  data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }
                if ($this->user->cans('delete_timelogs')) {
                    $action .= '&nbsp;<a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-time-id="' . $row->id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }
                return $action;
            })
            ->editColumn('start_time', function ($row) {
                return $row->start_time->timezone($this->global->timezone)->format($this->global->date_format . ' ' . $this->global->time_format);
            })
            ->editColumn('end_time', function ($row) {
                if (!is_null($row->end_time)) {
                    return $row->end_time->timezone($this->global->timezone)->format($this->global->date_format . ' ' . $this->global->time_format);
                } else {
                    return "<label class='label label-success'>" . __('app.active') . "</label>";
                }
            })
            ->editColumn('user_id', function ($row) {
                return ucwords($row->user->name);
            })
            ->editColumn('heading', function ($row) {
                if(isset($row->task->heading)){
                    return ucwords($row->task->heading);

                }
            })
            ->editColumn('edited_by_user', function ($row) {
                if (!is_null($row->edited_by_user)) {
                    return ucwords($row->editor->name);
                }
            })
            ->editColumn('total_hours', function ($row) {
                $timeLog = intdiv($row->total_minutes, 60) . ' hrs ';

                if (($row->total_minutes % 60) > 0) {
                    $timeLog .= ($row->total_minutes % 60) . ' mins';
                }

                return $timeLog;
            })
            ->rawColumns(['end_time', 'action'])
            ->removeColumn('project_id')
            ->make(true);
    }
}
