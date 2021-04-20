<?php

namespace App\Http\Controllers\Member;

use App\Helper\Reply;
use App\Http\Requests\Tasks\StoreTask;
use App\Notifications\NewTask;
use App\Notifications\TaskCompleted;
use App\Notifications\TaskUpdated;
use App\Project;
use App\SubTask;
use App\Task;
use App\TaskboardColumn;
use App\TaskCategory;
use App\TaskUser;
use App\Traits\ProjectProgress;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Notification;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class MemberProjectsController
 * @package App\Http\Controllers\Member
 */
class MemberTasksController extends MemberBaseController
{
    use ProjectProgress;

    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = 'icon-layers';
        $this->pageTitle = 'app.menu.projects';
        $this->middleware(function ($request, $next) {
            if (!in_array('tasks', $this->user->modules)) {
                abort(403);
            }
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTask $request)
    {
        $taskBoardColumn = TaskboardColumn::where('slug', 'incomplete')->first();
        $task = new Task();
        $task->heading = $request->heading;
        if ($request->description != '') {
            $task->description = $request->description;
        }
        $task->start_date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');
        $task->due_date = Carbon::createFromFormat($this->global->date_format, $request->due_date)->format('Y-m-d');

        if ($request->project_id != "") {
            $project = Project::find($request->project_id);
        }

        $task->project_id = $request->project_id;
        $task->priority = $request->priority;
        $task->task_category_id = $request->category_id;
        $task->board_column_id = $taskBoardColumn->id;
        $task->dependent_task_id = $request->has('dependent') && $request->dependent == 'yes' && $request->has('dependent_task_id') && $request->dependent_task_id != '' ? $request->dependent_task_id : null;
        $task->is_private = $request->has('is_private') && $request->is_private == 'true' ? 1 : 0;
        $task->billable = $request->has('billable') && $request->billable == 'true' ? 1 : 0;
        $task->estimate_hours = $request->estimate_hours;
        $task->estimate_minutes = $request->estimate_minutes;

        $task->save();


        $this->project = Project::findOrFail($task->project_id);
        $view = view('admin.projects.tasks.task-list-ajax', $this->data)->render();

        //calculate project progress if enabled
        $this->calculateProjectProgress($request->project_id);

        //log search
        $this->logSearchEntry($task->id, 'Task: ' . $task->heading, 'admin.all-tasks.edit', 'task');

        return Reply::successWithData(__('messages.taskCreatedSuccessfully'), ['html' => $view]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->project = Project::findOrFail($id);
        $this->categories = TaskCategory::all();
        $completedTaskColumn = TaskboardColumn::where('slug', '=', 'completed')->first();
        if ($completedTaskColumn) {
            $this->allTasks = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')->where('board_column_id', '<>', $completedTaskColumn->id)->select('tasks.*')
                ->where('tasks.id', '!=', $id);

            $this->allTasks = $this->allTasks->where('project_id', $id);

            if (!$this->user->can('view_tasks') && !$this->project->isProjectAdmin) {
                $this->allTasks = $this->allTasks->where('task_users.user_id', '=', $this->user->id);
            }

            $this->allTasks = $this->allTasks->get();
        } else {
            $this->allTasks = [];
        }
        // if($this->project->isProjectAdmin || $this->user->can('edit_projects'))
        //     $this->tasks = Task::where('project_id', $id)->get();
        // else
        //     $this->tasks = Task::where('project_id', $id)->where('user_id', $this->user->id)->get();

        return view('member.tasks.show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->task = Task::findOrFail($id);
        $this->taskBoardColumns = TaskboardColumn::all();
        $this->categories = TaskCategory::all();
        $completedTaskColumn = TaskboardColumn::where('slug', '=', 'completed')->first();
        if ($completedTaskColumn) {
            $this->allTasks = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')->where('board_column_id', '<>', $completedTaskColumn->id)
                ->where('tasks.id', '!=', $id);

            if ($this->task->project_id != '') {
                $this->allTasks = $this->allTasks->where('project_id', $this->task->project_id);
                $this->project = Project::find($this->task->project_id);
            }


            if (!$this->user->can('view_tasks') && !$this->project->isProjectAdmin) {
                $this->allTasks = $this->allTasks->where('task_users.user_id', '=', $this->user->id);
            }

            $this->allTasks = $this->allTasks->get();
        } else {
            $this->allTasks = [];
        }

        $view = view('member.tasks.edit', $this->data)->render();
        return Reply::dataOnly(['html' => $view]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTask $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->heading = $request->heading;
        if ($request->description != '') {
            $task->description = $request->description;
        }
        $task->start_date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');
        $task->due_date = Carbon::createFromFormat($this->global->date_format, $request->due_date)->format('Y-m-d');

        $project = Project::find($task->project_id);


        $task->priority = $request->priority;
        $task->board_column_id = $request->status;
        $task->task_category_id = $request->category_id;
        $task->dependent_task_id = $request->has('dependent') && $request->dependent == 'yes' && $request->has('dependent_task_id') && $request->dependent_task_id != '' ? $request->dependent_task_id : null;

        $taskBoardColumn = TaskboardColumn::findOrFail($request->status);
        if ($taskBoardColumn->slug == 'completed') {
            $task->completed_on = Carbon::now();
        } else {
            $task->completed_on = null;
        }

        $task->is_private = $request->has('is_private') && $request->is_private == 'true' ? 1 : 0;
        $task->billable = $request->has('billable') && $request->billable == 'true' ? 1 : 0;
        $task->estimate_hours = $request->estimate_hours;
        $task->estimate_minutes = $request->estimate_minutes;

        $task->save();

        TaskUser::where('task_id', $task->id)->delete();
        foreach ($request->user_id as $key => $value) {
            TaskUser::create(
                [
                    'user_id' => $value,
                    'task_id' => $task->id
                ]
            );
        }

        //calculate project progress if enabled
        $this->calculateProjectProgress($request->project_id);

        $this->project = Project::findOrFail($task->project_id);

        return Reply::success(__('messages.taskUpdatedSuccessfully'));
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
     * @param Request $request
     * @return array
     * @throws \Exception
     * @throws \Throwable
     */
    public function changeStatus(Request $request)
    {
        $taskId = $request->taskId;
        $status = $request->status;

        $task = Task::with('project')->findOrFail($taskId);

        if ($task->is_task_user || (!is_null($task->project_id) && $task->project->isProjectAdmin) || $this->user->can('edit_tasks') || $task->created_by == user()->id) {
            $taskBoardColumn = TaskboardColumn::where('slug', $status)->first();

            $task->board_column_id = $taskBoardColumn->id;
            if ($taskBoardColumn->slug == 'completed') {
                $task->completed_on = Carbon::today()->format('Y-m-d');
                $task->save();
            } else {
                $task->completed_on = null;
            }

            $task->save();

            $this->logTaskActivity($task->id, $this->user->id, "statusActivity", $task->board_column_id);

            if ($task->project_id != null) {
                if ($task->project->calculate_task_progress == "true") {
                    //calculate project progress if enabled
                    $this->calculateProjectProgress($task->project_id);
                }
                $this->project = Project::find($task->project_id);
                if ($this->project->isProjectAdmin || $this->user->can('edit_tasks'))
                    $this->project->tasks = Task::where('project_id', $this->project->id)->orderBy($request->sortBy, 'desc')->get();
                else
                    $this->project->tasks = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')
                    ->where('project_id', $this->project->id)
                    ->where('task_users.user_id', $this->user->id)
                    ->select('tasks.*')
                    ->orderBy($request->sortBy, 'desc')
                    ->get();
            }

            $this->task = $task;

            $view = view('member.tasks.task-list-ajax', $this->data)->render();

            return Reply::successWithData(__('messages.taskUpdatedSuccessfully'),  ['html' => $view, 'textColor' => $task->board_column->label_color, 'column' => $task->board_column->column_name]);
        } else {
            return Reply::error(Lang::get('messages.unAuthorisedUser'));
        }
    }

    public function sort(Request $request)
    {
        $projectId = $request->projectId;
        $this->sortBy = $request->sortBy;
        $taskBoardColumn = TaskboardColumn::completeColumn();
        $this->project = Project::findOrFail($projectId);
        if ($request->sortBy == 'due_date') {
            $order = "asc";
        } else {
            $order = "desc";
        }

        if ($this->project->isProjectAdmin) {
            $tasks = Task::whereProjectId($projectId)
                ->orderBy($request->sortBy, $order);
        } else {
            $tasks = Task::whereProjectId($projectId)
                ->where('user_id', $this->user->id)
                ->orderBy($request->sortBy, $order);
        }

        if ($request->hideCompleted == '1') {
            $tasks = $tasks->where('board_column_id', '!=', $taskBoardColumn->id);
        }

        //        $tasks = Task::whereProjectId($projectId)->orderBy($request->sortBy, $order);

        $this->project->tasks = $tasks->get();

        $view = view('member.tasks.task-list-ajax', $this->data)->render();

        return Reply::dataOnly(['html' => $view]);
    }

    public function checkTask($taskID)
    {
        $task = Task::findOrFail($taskID);
        $subTask = SubTask::where('task_id', $taskID)->where('status', 'incomplete')->count();

        return Reply::dataOnly(['taskCount' => $subTask, 'lastStatus' => $task->board_column->slug]);
    }

    public function data(Request $request, $projectId = null)
    {

        $tasks = Task::leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
            ->leftJoin('users as client', 'client.id', '=', 'projects.client_id')
            ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->join('users', 'task_users.user_id', '=', 'users.id')
            ->join('taskboard_columns', 'taskboard_columns.id', '=', 'tasks.board_column_id')
            ->leftJoin('users as creator_user', 'creator_user.id', '=', 'tasks.created_by')
            ->select('tasks.id', 'projects.project_name', 'tasks.heading', 'client.name as clientName', 'creator_user.name as created_by', 'creator_user.image as created_image', 'tasks.due_date', 'taskboard_columns.column_name as board_column', 'taskboard_columns.label_color', 'tasks.project_id', 'projects.project_admin', 'users.name as username', 'tasks.created_by as creator_id', 'tasks.is_private')
            ->where('tasks.project_id', $projectId)
            ->with('users')
            ->groupBy('tasks.id');
            
        if (!$this->user->can('view_tasks')) {
            $tasks->where(
                function ($q) {
                    $q->where(
                        function ($q1) {
                            $q1->where(
                                function ($q3) {
                                    $q3->where('tasks.is_private', 0);
                                }
                            );
                        }
                    );
                    $q->orWhere(
                        function ($q2) {
                            $q2->where('tasks.is_private', 1);
                            $q2->where('task_users.user_id', $this->user->id);
                        }
                    );
                }
            );
        }

        $tasks->get();

        return DataTables::of($tasks)
            ->addColumn('action', function ($row) {
                $action = '';

                if ($this->user->can('edit_tasks') || ($row->project_admin == $this->user->id) || ($this->global->task_self == 'yes' && $this->user->id == $row->creator_id)) {
                    $action .= '<a href="javascript:;" class="btn btn-info btn-circle edit-task"
                    data-toggle="tooltip" data-task-id="' . $row->id . '" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }

                if ($this->user->can('delete_tasks') || ($this->global->task_self == 'yes' && $this->user->id == $row->creator_id) || ($row->project_admin == $this->user->id)) {
                    $action .= '&nbsp;&nbsp;<a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-task-id="' . $row->id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }

                if ($action == '') {
                    $action = '--';
                }

                return $action;
            })
            ->editColumn('due_date', function ($row) {
                if ($row->due_date->isPast()) {
                    return '<span class="text-danger">' . $row->due_date->format($this->global->date_format) . '</span>';
                }
                return '<span class="text-success">' . $row->due_date->format($this->global->date_format) . '</span>';
            })
            ->editColumn('username', function ($row) {
                $members = '';
                foreach ($row->users as $member) {
                    $members.= '<a href="' . route('admin.employees.show', [$member->id]) . '">';
                    $members .= '<img data-toggle="tooltip" data-original-title="' . ucwords($member->name) . '" src="' . $member->image_url . '"
                    alt="user" class="img-circle" width="25" height="25"> ';
                    $members.= '</a>';
                }
                return $members;
            })
            ->editColumn('clientName', function ($row) {
                return ($row->clientName) ? ucwords($row->clientName) : '-';
            })
            ->editColumn('created_by', function ($row) {
                if (!is_null($row->created_by)) {
                    return ($row->created_image) ? '<img src="' . asset_url('avatar/' . $row->created_image) . '"
                                                            alt="user" class="img-circle" width="25" height="25"> ' . ucwords($row->created_by) : '<img src="' . asset('img/default-profile-3.png') . '"
                                                            alt="user" class="img-circle" width="25" height="25"> ' . ucwords($row->created_by);
                }
                return '-';
            })
            ->editColumn('heading', function ($row) {
                $name = '<a href="javascript:;" data-task-id="' . $row->id . '" class="show-task-detail">' . ucfirst($row->heading) . '</a>';

                if ($row->is_private) {
                    $name .= ' <i data-toggle="tooltip" data-original-title="' . __('app.private') . '" class="fa fa-lock" style="color: #ea4c89"></i>';
                }
                return $name;
            })
            ->editColumn('board_column', function ($row) {
                return '<label class="label" style="background-color: ' . $row->label_color . '">' . $row->board_column . '</label>';
            })
            ->rawColumns(['board_column', 'action', 'clientName', 'due_date', 'username', 'created_by', 'heading'])
            ->removeColumn('project_id')
            ->removeColumn('image')
            ->removeColumn('created_image')
            ->removeColumn('label_color')
            ->addIndexColumn()
            ->make(true);
    }
}
