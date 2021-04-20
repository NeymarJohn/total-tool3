<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\Tasks\StoreTask;
use App\Notifications\NewClientTask;
use App\Notifications\NewTask;
use App\Notifications\TaskCompleted;
use App\Notifications\TaskUpdated;
use App\Notifications\TaskUpdatedClient;
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
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ManageTasksController extends AdminBaseController
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
        $task = new Task();
        $task->heading = $request->heading;
        if ($request->description != '') {
            $task->description = $request->description;
        }
        $taskBoardColumn = TaskboardColumn::where('slug', 'incomplete')->first();

        $task->start_date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');
        $task->due_date = Carbon::createFromFormat($this->global->date_format, $request->due_date)->format('Y-m-d');
        $task->project_id = $request->project_id;
        $task->priority = $request->priority;
        $task->board_column_id = $taskBoardColumn->id;
        $task->task_category_id = $request->category_id;
        $task->dependent_task_id = $request->has('dependent') && $request->dependent == 'yes' && $request->has('dependent_task_id') && $request->dependent_task_id != '' ? $request->dependent_task_id : null;
        $task->is_private = $request->has('is_private') && $request->is_private == 'true' ? 1 : 0;
        $task->billable = $request->has('billable') && $request->billable == 'true' ? 1 : 0;
        $task->estimate_hours = $request->estimate_hours;
        $task->estimate_minutes = $request->estimate_minutes;

        if ($request->milestone_id != '') {
            $task->milestone_id = $request->milestone_id;
        }

        $task->save();

        $this->project = Project::findOrFail($task->project_id);
        $view = view('admin.projects.tasks.task-list-ajax', $this->data)->render();

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
            $this->allTasks = Task::where('board_column_id', '<>', $completedTaskColumn->id)
                ->where('project_id', $id)
                ->get();
        } else {
            $this->allTasks = [];
        }
        return view('admin.projects.tasks.show', $this->data);
    }

    public function kanbanboard($id)
    {
        $this->project = Project::findOrFail($id);
        $this->startDate = Carbon::now()->subDays(15)->format($this->global->date_format);
        $this->endDate = Carbon::now()->addDays(15)->format($this->global->date_format);
        $this->employees = User::allEmployees();
        return view('admin.projects.tasks.kanbanboard', $this->data);
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
            $this->allTasks = Task::where('board_column_id', '<>', $completedTaskColumn->id)
                ->where('id', '!=', $id);

            if ($this->task->project_id != '') {
                $this->allTasks = $this->allTasks->where('project_id', $this->task->project_id);
            }

            $this->allTasks = $this->allTasks->get();
        } else {
            $this->allTasks = [];
        }

        $view = view('admin.projects.tasks.edit', $this->data)->render();
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
        $task->priority = $request->priority;
        $task->task_category_id = $request->category_id;
        $task->board_column_id = $request->status;
        $task->dependent_task_id = $request->has('dependent') && $request->dependent == 'yes' && $request->has('dependent_task_id') && $request->dependent_task_id != '' ? $request->dependent_task_id : null;
        $task->is_private = $request->has('is_private') && $request->is_private == 'true' ? 1 : 0;
        $task->billable = $request->has('billable') && $request->billable == 'true' ? 1 : 0;
        $task->estimate_hours = $request->estimate_hours;
        $task->estimate_minutes = $request->estimate_minutes;

        $taskBoardColumn = TaskboardColumn::findOrFail($request->status);
        if ($taskBoardColumn->slug == 'completed') {
            $task->completed_on = Carbon::now();
        } else {
            $task->completed_on = null;
        }

        if ($request->milestone_id != '') {
            $task->milestone_id = $request->milestone_id;
        }

        $task->save();

        // Sync task users
        $task->users()->sync($request->user_id);



        //calculate project progress if enabled
        $this->calculateProjectProgress($request->project_id);



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

    public function changeStatus(Request $request)
    {
        $taskId = $request->taskId;
        $status = $request->status;
        $taskBoardColumn = TaskboardColumn::where('slug', $status)->first();
        $task = Task::with('project')->findOrFail($taskId);
        $task->board_column_id = $taskBoardColumn->id;
        //        $task->status = $status;

        if ($taskBoardColumn->slug == 'completed') {
            $task->completed_on = Carbon::now()->format('Y-m-d');
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
            $this->project->tasks = Task::whereProjectId($this->project->id)->orderBy($request->sortBy, 'desc')->get();
        }

        $this->task = $task;

    //    $view = view('admin.projects.tasks.task-list-ajax', $this->data)->render();
        $view = '';

        return Reply::successWithData(__('messages.taskUpdatedSuccessfully'), ['html' => $view, 'textColor' => $task->board_column->label_color, 'column' => $task->board_column->column_name]);
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

        $tasks = Task::whereProjectId($projectId)->orderBy($request->sortBy, $order);

        if ($request->hideCompleted == '1') {
            $tasks->where('board_column_id', '!=', $taskBoardColumn->id);
        }

        $this->project->tasks = $tasks->get();

        $view = view('admin.projects.tasks.task-list-ajax', $this->data)->render();

        return Reply::dataOnly(['html' => $view]);
    }

    public function checkTask($taskID)
    {
        $task = Task::findOrFail($taskID);
        $subTask = SubTask::where(['task_id' => $taskID, 'status' => 'incomplete'])->count();

        return Reply::dataOnly(['taskCount' => $subTask, 'lastStatus' => $task->board_column->slug]);
    }

    public function data(Request $request, $projectId = null)
    {

        $tasks = Task::leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
            ->leftJoin('users as client', 'client.id', '=', 'projects.client_id')
            ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->join('users as member', 'task_users.user_id', '=', 'member.id')
            ->join('taskboard_columns', 'taskboard_columns.id', '=', 'tasks.board_column_id')
            ->leftJoin('users as creator_user', 'creator_user.id', '=', 'tasks.created_by')
            ->select('tasks.id', 'projects.project_name', 'tasks.heading', 'client.name as clientName', 'creator_user.name as created_by', 'creator_user.image as created_image', 'tasks.due_date', 'taskboard_columns.column_name as board_column', 'taskboard_columns.label_color', 'tasks.project_id')
            ->where('projects.id', $projectId)
            ->with('users')
            ->groupBy('tasks.id');

        $tasks->get();

        $taskBoardColumns = TaskboardColumn::orderBy('priority', 'asc')->get();

        return DataTables::of($tasks)
            ->addColumn('action', function ($row) {
                return '<a href="javascript:;" class="btn btn-info btn-circle edit-task"
                      data-toggle="tooltip" data-task-id="' . $row->id . '" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                        &nbsp;&nbsp;<a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-task-id="' . $row->id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
            })
            ->editColumn('due_date', function ($row) {
                if ($row->due_date->isPast()) {
                    return '<span class="text-danger">' . $row->due_date->format($this->global->date_format) . '</span>';
                }
                return '<span class="text-success">' . $row->due_date->format($this->global->date_format) . '</span>';
            })
            ->editColumn('users', function ($row) {
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
                return '<a href="javascript:;" data-task-id="' . $row->id . '" class="show-task-detail">' . ucfirst($row->heading) . '</a>';
            })
            ->editColumn('board_column', function ($row) use ($taskBoardColumns) {
                $status = '<div class="btn-group dropdown">';
                $status .= '<button aria-expanded="true" data-toggle="dropdown" class="btn dropdown-toggle waves-effect waves-light btn-xs"  style="border-color: ' . $row->label_color . '; color: ' . $row->label_color . '" type="button">' . $row->board_column . ' <span class="caret"></span></button>';
                $status .= '<ul role="menu" class="dropdown-menu pull-right">';
                foreach ($taskBoardColumns as $key => $value) {
                    $status .= '<li><a href="javascript:;" data-task-id="' . $row->id . '" class="change-status" data-status="' . $value->slug . '">' . $value->column_name . '  <span style="width: 15px; height: 15px; border-color: ' . $value->label_color . '; background: ' . $value->label_color . '"
                    class="btn btn-warning btn-small btn-circle">&nbsp;</span></a></li>';
                }
                $status .= '</ul>';
                $status .= '</div>';
                return $status;
            })
            
            ->rawColumns(['board_column', 'action', 'clientName', 'due_date', 'users', 'created_by', 'heading'])
            ->removeColumn('project_id')
            ->removeColumn('image')
            ->removeColumn('created_image')
            ->removeColumn('label_color')
            ->addIndexColumn()
            ->make(true);
    }


}
