<?php

namespace App\Http\Controllers\Member;

use App\Events\TaskReminderEvent;
use App\Helper\Reply;
use App\Http\Controllers\Member\MemberBaseController;
use App\Http\Requests\Tasks\StoreTask;
use App\Notifications\NewTask;
use App\Notifications\TaskCompleted;
use App\Notifications\TaskReminder;
use App\Notifications\TaskUpdated;
use App\Pinned;
use App\Project;
use App\ProjectMember;
use App\Task;
use App\TaskboardColumn;
use App\TaskCategory;
use App\TaskFile;
use App\TaskLabel;
use App\TaskLabelList;
use App\Traits\ProjectProgress;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Notifications\NewClientTask;

class MemberAllTasksController extends MemberBaseController
{
    use ProjectProgress;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.tasks';
        $this->pageIcon = 'ti-layout-list-thumb';
        $this->middleware(function ($request, $next) {
            abort_if(!in_array('tasks', $this->user->modules),403);
            return $next($request);
        });
    }

    public function index()
    {
        $this->projects = ($this->user->cans('view_projects')) ? Project::all() : Project::select('projects.*')->join('project_members', 'project_members.project_id', '=', 'projects.id')
            ->where('project_members.user_id', $this->user->id)
            ->get();
        $this->employees = ($this->user->cans('view_employees')) ? User::allEmployees() : User::where('id', $this->user->id)->get();

        $this->clients = User::allClients();
        $this->taskBoardStatus = TaskboardColumn::all();

        return view('member.all-tasks.index', $this->data);
    }

    public function data(Request $request, $hideCompleted = null, $projectId = null)
    {
        $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->format('Y-m-d');
        $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->format('Y-m-d');

        $taskBoardColumn = TaskboardColumn::where('slug', 'completed')->first();
        $taskBoardColumns = TaskboardColumn::orderBy('priority', 'asc')->get();

        $tasks = Task::leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
            ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->join('users as member', 'task_users.user_id', '=', 'member.id')
            ->leftJoin('users as creator_user', 'creator_user.id', '=', 'tasks.created_by')
            ->join('taskboard_columns', 'taskboard_columns.id', '=', 'tasks.board_column_id')
            ->selectRaw('tasks.id, projects.project_name, tasks.heading, creator_user.name as created_by, creator_user.id as created_by_id, creator_user.image as created_image,
             tasks.due_date, taskboard_columns.column_name as board_column, taskboard_columns.label_color,
              tasks.project_id, tasks.is_private ,( select count("id") from pinned where pinned.task_id = tasks.id and pinned.user_id = '.user()->id.') as pinned_task')            ->whereNull('projects.deleted_at')
            ->with('users', 'activeTimer')
            ->groupBy('tasks.id');

        $tasks->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween(DB::raw('DATE(tasks.`due_date`)'), [$startDate, $endDate]);

            $q->orWhereBetween(DB::raw('DATE(tasks.`start_date`)'), [$startDate, $endDate]);
        });

        if ($projectId != 0 && $projectId !=  null && $projectId !=  'all') {
            $tasks->where('tasks.project_id', '=', $projectId);
        }

        if ($request->assignedTo != '' && $request->assignedTo !=  null && $request->assignedTo !=  'all') {
            $tasks->where('task_users.user_id', '=', $request->assignedTo);
        }

        if ($request->assignedBY != '' && $request->assignedBY !=  null && $request->assignedBY !=  'all') {
            $tasks->where('creator_user.id', '=', $request->assignedBY);
        }

        if ($request->status != '' && $request->status !=  null && $request->status !=  'all') {
            $tasks->where('tasks.board_column_id', '=', $request->status);
        }
        if ($request->billable != '' && $request->billable !=  null && $request->billable !=  'all') {
            $tasks->where('tasks.billable', '=', $request->billable);
        }
        if ($hideCompleted == '1') {
            $tasks->where('tasks.board_column_id', '<>', $taskBoardColumn->id);
        }

        if (!$this->user->cans('view_tasks')) {
            $tasks->where(
                function ($q) {
                    $q->where(
                        function ($q1) {
                            $q1->where(
                                function ($q3) {
                                    $q3->where('tasks.is_private', 0);
                                    $q3->where('task_users.user_id', $this->user->id);
                                }
                            );
                            $q1->orWhere('tasks.created_by', $this->user->id);
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


        $tasks->orderBy('tasks.id', 'desc')->get();

        return DataTables::of($tasks)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $action = '';
                if ($this->user->cans('edit_tasks') || ($this->global->task_self == 'yes' && $this->user->id == $row->created_by_id)) {
                    $action .= '<a href="' . route('member.all-tasks.edit', $row->id) . '" class="btn btn-info btn-circle"
                      data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }

                if ($this->user->cans('delete_tasks') || ($this->global->task_self == 'yes' && $this->user->id == $row->created_by_id)) {
                    $recurringTaskCount = Task::where('recurring_task_id', $row->id)->count();
                    $recurringTask = $recurringTaskCount > 0 ? 'yes' : 'no';

                    $action .= '&nbsp;&nbsp;<a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-task-id="' . $row->id . '" data-recurring="' . $recurringTask . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }
                return $action;
            })
            ->editColumn('due_date', function ($row) {
                if ($row->due_date->endOfDay()->isPast()) {
                    return '<span class="text-danger">' . $row->due_date->format($this->global->date_format) . '</span>';
                }
                return '<span class="text-success">' . $row->due_date->format($this->global->date_format) . '</span>';
            })
            
            ->editColumn('created_by', function ($row) {
                if (!is_null($row->created_by)) {
                    return ($row->created_image) ? '<img src="' . asset_url('avatar/' . $row->created_image) . '"
                                                            alt="user" class="img-circle" width="30" height="30"> ' . ucwords($row->created_by) : '<img src="' . asset('img/default-profile-3.png') . '"
                                                            alt="user" class="img-circle" width="30" height="30"> ' . ucwords($row->created_by);
                }
                return '-';
            })
            ->editColumn('name', function ($row) {
                $members = '';
                foreach ($row->users as $member) {
                    $members .= '<a href="' . route('admin.employees.show', [$member->id]) . '">';
                    $members .= '<img data-toggle="tooltip" data-original-title="' . ucwords($member->name) . '" src="' . $member->image_url . '"
                    alt="user" class="img-circle" width="25" height="25"> ';
                    $members .= '</a>';
                }

                return $members;
            })
            ->editColumn('heading', function ($row) {
                $pin = '';
                if(($row->pinned_task) ){
                    $pin = '<br><span class="font-12"  data-toggle="tooltip" data-original-title="'.__('app.pinned').'"><i class="icon-pin icon-2"></i></span>';
                }

                $name = '<a href="javascript:;" data-task-id="' . $row->id . '" class="show-task-detail">' . ucfirst($row->heading) . '</a> '.$pin;

                if ($row->is_private) {
                    $name .= ' <i data-toggle="tooltip" data-original-title="' . __('app.private') . '" class="fa fa-lock" style="color: #ea4c89"></i>';
                }

                if ($row->activeTimer) {
                    $name .= '<br><label class="label label-inverse" data-toggle="tooltip" data-original-title="' . __('modules.projects.activeTimers') . '" > <i class="fa fa-clock-o" ></i> '.$row->activeTimer->timer.'</label>';
                }
                return $name;
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
            ->editColumn('project_name', function ($row) {
                if (is_null($row->project_id)) {
                    return "";
                }
                return '<a href="' . route('member.projects.show', $row->project_id) . '">' . ucfirst($row->project_name) . '</a>';
            })
            ->rawColumns(['board_column', 'action', 'project_name', 'created_by', 'due_date', 'name', 'heading'])
            ->removeColumn('project_id')
            ->removeColumn('image')
            ->removeColumn('label_color')
            ->removeColumn('taskUserID')
            ->make(true);
    }

    public function edit($id)
    {

        if (!$this->user->cans('edit_tasks') && $this->global->task_self == 'no') {
            abort(403);
        }

        $this->taskBoardColumns = TaskboardColumn::all();
        $this->task = Task::with(['label'])->findOrFail($id);
        $this->labelIds         = $this->task->label->pluck('label_id')->toArray();
        $this->taskLabels = TaskLabelList::all();
        if (!$this->user->cans('add_tasks') && $this->global->task_self == 'yes') {
            $this->projects = Project::join('project_members', 'project_members.project_id', '=', 'projects.id')
                ->join('users', 'users.id', '=', 'project_members.user_id')
                ->where('project_members.user_id', $this->user->id)
                ->select('projects.id', 'projects.project_name')
                ->get();
        } else {
            $this->projects = Project::all();
        }

        $this->employees = User::allEmployees();
        $this->categories = TaskCategory::all();
        $completedTaskColumn = TaskboardColumn::where('slug', '!=', 'completed')->first();
        if ($completedTaskColumn) {
            $this->allTasks = Task::select('tasks.id', 'tasks.heading', 'tasks.due_date')
            ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
                ->where('board_column_id', $completedTaskColumn->id)
                ->where('tasks.id', '!=', $id);

            if ($this->task->project_id != '') {
                $this->allTasks = $this->allTasks->where('project_id', $this->task->project_id);
            }

            if (!$this->user->cans('view_tasks')) {
                $this->allTasks = $this->allTasks->where('task_users.user_id', '=', $this->user->id);
            }

            $this->allTasks = $this->allTasks->get();
        } else {
            $this->allTasks = [];
        }

        $this->upload = can_upload();

        return view('member.all-tasks.edit', $this->data);
    }

    public function update(StoreTask $request, $id)
    {
        $task = Task::findOrFail($id);
        $oldStatus = TaskboardColumn::findOrFail($task->board_column_id);

        $task->heading = $request->title;
        if ($request->description != '') {
            $task->description = $request->description;
        }
        $task->start_date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');
        $task->due_date = Carbon::createFromFormat($this->global->date_format, $request->due_date)->format('Y-m-d');
        $task->priority = $request->priority;
        $task->board_column_id = $request->status;
        $task->task_category_id = $request->category_id;
        $task->dependent_task_id = $request->has('dependent') && $request->dependent == 'yes' && $request->has('dependent_task_id') && $request->dependent_task_id != '' ? $request->dependent_task_id : null;
        $task->is_private = $request->has('is_private') && $request->is_private == 'true' ? 1 : 0;
        $task->billable = $request->has('billable') && $request->billable == 'true' ? 1 : 0;
        $task->estimate_hours = $request->estimate_hours;
        $task->estimate_minutes = $request->estimate_minutes;

        $taskBoardColumn = TaskboardColumn::findOrFail($request->status);
        if ($taskBoardColumn->slug == 'completed') {
            $task->completed_on = Carbon::now()->format('Y-m-d H:i:s');
        } else {
            $task->completed_on = null;
        }

        $task->project_id = $request->project_id;
        $task->save();

        // save labels
        $labels = $request->task_labels;

        if ($labels) {
            TaskLabel::where('task_id', $task->id)->delete();
            foreach ($labels as $label) {
                TaskLabel::create([
                    'label_id' => $label,
                    'task_id' => $task->id
                ]);
            }
        }

        // Sync task users
        $task->users()->sync($request->user_id);

        return Reply::dataOnly(['taskID' => $task->id]);
        //        return Reply::redirect(route('member.all-tasks.index'), __('messages.taskUpdatedSuccessfully'));
    }

    public function destroy(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        // If it is recurring and allowed by user to delete all its recurring tasks
        if ($request->has('recurring') && $request->recurring == 'yes') {
            Task::where('recurring_task_id', $id)->delete();
        }

        Task::destroy($id);

        //calculate project progress if enabled
        $this->calculateProjectProgress($task->project_id);

        return Reply::success(__('messages.taskDeletedSuccessfully'));
    }

    public function showFiles($id)
    {
        $this->taskFiles = TaskFile::where('task_id', $id)->get();
        return view('member.all-tasks.ajax-file-list', $this->data);
    }

    public function create()
    {
        if (!$this->user->cans('add_tasks') && $this->global->task_self == 'no') {
            abort(403);
        }

        if (!$this->user->cans('add_tasks') && $this->global->task_self == 'yes') {
            $this->projects = Project::join('project_members', 'project_members.project_id', '=', 'projects.id')
                ->join('users', 'users.id', '=', 'project_members.user_id')
                ->where('project_members.user_id', $this->user->id)
                ->select('projects.id', 'projects.project_name')
                ->get();
        } else {
            $this->projects = Project::all();
        }

        $this->employees = User::allEmployees();
        $this->categories = TaskCategory::all();
        $this->taskLabels = TaskLabelList::all();
        $completedTaskColumn = TaskboardColumn::where('slug', '!=', 'completed')->first();
        if ($completedTaskColumn) {
            $this->allTasks = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->where('board_column_id', $completedTaskColumn->id);

            if (!$this->user->cans('view_tasks')) {
                $this->allTasks = $this->allTasks->where('task_users.user_id', '=', $this->user->id);
            }

            $this->allTasks = $this->allTasks->get();
        } else {
            $this->allTasks = [];
        }
        $this->upload = can_upload();
        return view('member.all-tasks.create', $this->data);
    }

    public function membersList($projectId)
    {
        $this->members = ProjectMember::byProject($projectId);
        $list = view('member.all-tasks.members-list', $this->data)->render();
        return Reply::dataOnly(['html' => $list]);
    }

    public function remindForTask($taskID)
    {
        $task = Task::with('users')->findOrFail($taskID);

        // Send  reminder notification to user
        event(new TaskReminderEvent($task));

        return Reply::success('messages.reminderMailSuccess');
    }

    public function store(StoreTask $request)
    {

        $task = new Task();
        $task->heading = $request->title;
        if ($request->description != '') {
            $task->description = $request->description;
        }
        $task->start_date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');
        $task->due_date = Carbon::createFromFormat($this->global->date_format, $request->due_date)->format('Y-m-d');
        $task->project_id = (isset($request->task_project_id)) ? $request->task_project_id : $request->project_id;
        $task->priority = $request->priority;
        $task->board_column_id = $this->global->default_task_status;
        $task->task_category_id = $request->category_id;
        $task->created_by = $this->user->id;
        $task->dependent_task_id = $request->has('dependent') && $request->dependent == 'yes' && $request->has('dependent_task_id') && $request->dependent_task_id != '' ? $request->dependent_task_id : null;
        $task->is_private = $request->has('is_private') && $request->is_private == 'true' ? 1 : 0;
        $task->billable = $request->has('billable') && $request->billable == 'true' ? 1 : 0;
        $task->estimate_hours = $request->estimate_hours;
        $task->estimate_minutes = $request->estimate_minutes;

        $task->save();

        // save labels
        $labels = $request->task_labels;

        if ($labels) {
            foreach ($labels as $label) {
                TaskLabel::create([
                    'label_id' => $label,
                    'task_id' => $task->id
                ]);
            }
        }

        // For gantt chart
        if ($request->page_name && $request->page_name == 'ganttChart') {
            $newTask = $task;
            $parentGanttId = $request->parent_gantt_id;
            $taskDuration = $newTask->due_date->diffInDays($newTask->start_date);
            $taskDuration = $taskDuration + 1;

            $ganttTaskArray[] = [
                'id' => $newTask->id,
                'text' => $newTask->heading,
                'start_date' => $newTask->start_date->format('Y-m-d'),
                'duration' => $taskDuration,
                'parent' => $parentGanttId,
                'taskid' => $newTask->id
            ];

            $gantTaskLinkArray[] = [
                'id' => 'link_' . $newTask->id,
                'source' => $parentGanttId,
                'target' => $newTask->id,
                'type' => 1
            ];
        }

        // Add repeated task
        if ($request->has('repeat') && $request->repeat == 'yes') {
            $repeatCount = $request->repeat_count;
            $repeatType = $request->repeat_type;
            $repeatCycles = $request->repeat_cycles;
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');
            $dueDate = Carbon::createFromFormat($this->global->date_format, $request->due_date)->format('Y-m-d');


            for ($i = 1; $i < $repeatCycles; $i++) {
                $repeatStartDate = Carbon::createFromFormat('Y-m-d', $startDate);
                $repeatDueDate = Carbon::createFromFormat('Y-m-d', $dueDate);

                if ($repeatType == 'day') {
                    $repeatStartDate = $repeatStartDate->addDays($repeatCount);
                    $repeatDueDate = $repeatDueDate->addDays($repeatCount);
                } else if ($repeatType == 'week') {
                    $repeatStartDate = $repeatStartDate->addWeeks($repeatCount);
                    $repeatDueDate = $repeatDueDate->addWeeks($repeatCount);
                } else if ($repeatType == 'month') {
                    $repeatStartDate = $repeatStartDate->addMonths($repeatCount);
                    $repeatDueDate = $repeatDueDate->addMonths($repeatCount);
                } else if ($repeatType == 'year') {
                    $repeatStartDate = $repeatStartDate->addYears($repeatCount);
                    $repeatDueDate = $repeatDueDate->addYears($repeatCount);
                }

                $newTask = new Task();
                $newTask->heading = $request->title;
                if ($request->description != '') {
                    $newTask->description = $request->description;
                }
                $newTask->start_date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');
                $newTask->due_date = Carbon::createFromFormat($this->global->date_format, $request->due_date)->format('Y-m-d');
                $newTask->project_id = $request->project_id;
                $newTask->priority = $request->priority;
                $newTask->board_column_id =  $request->board_column_id;
                $newTask->task_category_id = $request->category_id;
                $newTask->created_by = $this->user->id;
                $newTask->recurring_task_id = $task->id;
                $newTask->estimate_hours = $request->estimate_hours;
                $newTask->estimate_minutes = $request->estimate_minutes;
                $newTask->is_private = $request->has('is_private') && $request->is_private == 'true' ? 1 : 0;
                $newTask->billable = $request->has('billable') && $request->billable == 'true' ? 1 : 0;
        

                if ($request->board_column_id) {
                    $newTask->board_column_id = $request->board_column_id;
                }
                $newTask->save();

                if ($labels) {
                    foreach ($labels as $label) {
                        TaskLabel::create([
                            'label_id' => $label,
                            'task_id' => $task->id
                        ]);
                    }
                }

                $startDate = $newTask->start_date->format('Y-m-d');
                $dueDate = $newTask->due_date->format('Y-m-d');
            }
        }

        if ($request->page_name && $request->page_name == 'ganttChart') {

            return Reply::successWithData(
                'messages.taskCreatedSuccessfully',
                [
                    'tasks' => $ganttTaskArray,
                    'links' => $gantTaskLinkArray
                ]
            );
        }


        if ($request->board_column_id) {
            return Reply::success(__('messages.taskCreatedSuccessfully'));
        }
        return Reply::dataOnly(['taskID' => $task->id]);

        //        return Reply::redirect(route('member.all-tasks.index'), __('messages.taskCreatedSuccessfully'));
    }

    public function ajaxCreate($columnId)
    {
        $this->projects = Project::all();
        $this->columnId = $columnId;
        $this->categories = TaskCategory::all();
        $this->employees = User::allEmployees();
        $completedTaskColumn = TaskboardColumn::where('slug', '!=', 'completed')->first();
        if ($completedTaskColumn) {
            $this->allTasks = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')->where('board_column_id', $completedTaskColumn->id);

            if (!$this->user->cans('view_tasks')) {
                $this->allTasks = $this->allTasks->where('task_users.user_id', '=', $this->user->id);
            }

            $this->allTasks = $this->allTasks->get();
        } else {
            $this->allTasks = [];
        }
        return view('member.all-tasks.ajax_create', $this->data);
    }

    public function show($id)
    {
        $this->task = Task::with('board_column', 'subtasks', 'project', 'users', 'files', 'comments', 'label', 'label.label', 'notes', 'activeTimer')->findOrFail($id);
        $this->employees = User::join('employee_details', 'users.id', '=', 'employee_details.user_id')
            ->leftJoin('project_time_logs', 'project_time_logs.user_id', '=', 'users.id')
            ->leftJoin('designations', 'employee_details.designation_id', '=', 'designations.id');

        $where = 'and project_time_logs.task_id="' . $id . '" ';
        
        $this->employees = $this->employees->select(
            'users.name',
            'users.image',
            'users.id',
            'designations.name as designation_name',
            DB::raw(
                "(SELECT SUM(project_time_logs.total_minutes) FROM project_time_logs WHERE project_time_logs.user_id = users.id $where GROUP BY project_time_logs.user_id) as total_minutes"
            )
        );

        $this->employees = $this->employees->where('project_time_logs.task_id', '=', $id);

        $this->employees = $this->employees->groupBy('project_time_logs.user_id')
            ->orderBy('users.name')
            ->get();
        $this->upload = can_upload();
        $view = view('member.all-tasks.show', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function dependentTaskLists($projectId, $taskId = null)
    {
        $completedTaskColumn = TaskboardColumn::where('slug', '!=', 'completed')->first();
        if ($completedTaskColumn) {
            $this->allTasks = Task::select('tasks.id', 'tasks.heading', 'tasks.due_date')
                ->join('task_users', 'task_users.task_id', '=', 'tasks.id')->where('board_column_id', $completedTaskColumn->id)
                ->where('project_id', $projectId);

            if ($taskId != null) {
                $this->allTasks = $this->allTasks->where('tasks.id', '!=', $taskId);
            }

            if (!$this->user->cans('view_tasks')) {
                $this->allTasks = $this->allTasks->where('task_users.user_id', '=', $this->user->id);
            }

            $this->allTasks = $this->allTasks->get();
        } else {
            $this->allTasks = [];
        }
        $list = view('member.tasks.dependent-task-list', $this->data)->render();
        return Reply::dataOnly(['html' => $list]);
    }

    public function history($id)
    {
        $this->task = Task::with('board_column', 'history', 'history.board_column')->findOrFail($id);
        $view = view('admin.tasks.history', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    /**
     * @return mixed
     */
    public function pinnedItem()
    {
        $this->pinnedItems = Pinned::join('tasks', 'tasks.id', '=', 'pinned.task_id')
            ->where('pinned.user_id','=',user()->id)
            ->select('tasks.id', 'heading')
            ->get();

        return view('member.tasks.pinned-task', $this->data);
    }
}
