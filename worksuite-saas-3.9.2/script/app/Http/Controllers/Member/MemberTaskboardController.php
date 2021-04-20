<?php

namespace App\Http\Controllers\Member;

use App\Helper\Reply;
use App\Http\Requests\TaskBoard\StoreTaskBoard;
use App\Http\Requests\TaskBoard\UpdateTaskBoard;
use App\Project;
use App\Task;
use App\TaskboardColumn;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MemberTaskboardController extends MemberBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'modules.tasks.taskBoard';
        $this->pageIcon = 'ti-layout-column3';
        $this->middleware(function ($request, $next) {
            abort_if(!in_array('tasks', $this->user->modules),403);
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $date = Carbon::now();
        $startDate = $date->subDays(15)->format($this->global->date_format);
        $endDate = Carbon::now()->addDays(15)->format($this->global->date_format);

        $boardColumns = TaskboardColumn::with(['tasks' => function ($q) use ($startDate, $endDate, $request) {

            $q->with(['subtasks', 'completedSubtasks', 'comments', 'users', 'project', 'label'])
                ->leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
                ->leftJoin('users as client', 'client.id', '=', 'projects.client_id')
                ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
                ->join('users', 'task_users.user_id', '=', 'users.id')
                ->join('taskboard_columns', 'taskboard_columns.id', '=', 'tasks.board_column_id')
                ->leftJoin('users as creator_user', 'creator_user.id', '=', 'tasks.created_by')
                ->select('tasks.*')
                ->where('task_users.user_id', '=', user()->id)
                ->groupBy('tasks.id');


            $q->whereNull('projects.deleted_at');

            if ($request->startDate != 0 && $request->startDate !=  null && $request->startDate !=  '') {

                $startDateFilter = Carbon::createFromFormat($this->global->date_format, $request->startDate)->format('Y-m-d');
                $q->where(function ($task) use ($request, $startDateFilter) {
                    $task->where(DB::raw('DATE(tasks.`due_date`)'), '>=', $startDateFilter);
                    $task->orWhere(DB::raw('DATE(tasks.`start_date`)'), '<=', $startDateFilter);
                });
            }

            if ($request->endDate != 0 && $request->endDate !=  null && $request->endDate !=  '') {
                $endDateFilter = Carbon::createFromFormat($this->global->date_format, $request->endDate)->format('Y-m-d');
                $q->where(function ($task) use ($request, $endDateFilter) {
                    $task->where(DB::raw('DATE(tasks.`due_date`)'), '<=', $endDateFilter);
                    $task->orWhere(DB::raw('DATE(tasks.`start_date`)'), '>=', $endDateFilter);
                });
            }
            if ($request->projectID != 0 && $request->projectID !=  null && $request->projectID !=  'all') {
                $q = $q->where('tasks.project_id', '=', $request->projectID);
            }

            if ($request->clientID != '' && $request->clientID !=  null && $request->clientID !=  'all') {
                $q = $q->where('projects.client_id', '=', $request->clientID);
            }

            if ($request->assignedTo != '' && $request->assignedTo !=  null && $request->assignedTo !=  'all') {
                    $q = $q->where('task_users.user_id', '=', $request->assignedTo);
            }

            if ($request->assignedBY != '' && $request->assignedBY !=  null && $request->assignedBY !=  'all') {
                $q = $q->where('creator_user.id', '=', $request->assignedBY);
            }

            if (!$this->user->cans('view_tasks')) {
                $q = $q->where(function ($q) {
                    $q->where('tasks.is_private', 0);
                    $q->orWhere('task_users.user_id', $this->user->id);
                    $q->orWhere('tasks.created_by', $this->user->id);
                });

            }
        }])->orderBy('priority', 'asc')->get();

        $this->boardColumns = $boardColumns;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->projects = ($this->user->cans('view_projects')) ? Project::all() : Project::select('projects.*')->join('project_members', 'project_members.project_id', '=', 'projects.id')
            ->where('project_members.user_id', $this->user->id)
            ->get();
        $this->employees = ($this->user->cans('view_employees')) ? User::allEmployees() : User::where('id', $this->user->id)->get();
        $this->clients = User::allClients();

        if ($request->ajax()) {
            $view = view('member.taskboard.board_data', $this->data)->render();
            return Reply::dataOnly(['view' => $view]);
        }
        return view('member.taskboard.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('member.taskboard.create', $this->data);
    }

    /**
     * @param StoreTaskBoard $request
     * @return array
     */
    public function store(StoreTaskBoard $request)
    {
        $maxPriority = TaskboardColumn::max('priority');

        $board = new TaskboardColumn();
        $board->column_name = $request->column_name;
        $board->label_color = $request->label_color;
        $board->priority = ($maxPriority + 1);
        $board->save();

        return Reply::redirect(route('member.taskboard.index'), __('messages.boardColumnSaved'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->boardColumn = TaskboardColumn::findOrFail($id);
        $this->maxPriority = TaskboardColumn::max('priority');
        $view =  view('member.taskboard.edit', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    /**
     * @param StoreTaskBoard $request
     * @param $id
     * @return array
     */
    public function update(UpdateTaskBoard $request, $id)
    {
        $board = TaskboardColumn::findOrFail($id);
        $oldPosition = $board->priority;
        $newPosition = $request->priority;

        if ($oldPosition < $newPosition) {

            $otherColumns = TaskboardColumn::where('priority', '>', $oldPosition)
                ->where('priority', '<=', $newPosition)
                ->orderBy('priority', 'asc')
                ->get();

            foreach ($otherColumns as $column) {
                $pos = TaskboardColumn::where('priority', $column->priority)->first();
                $pos->priority = ($pos->priority - 1);
                $pos->save();
            }
        } else if ($oldPosition > $newPosition) {

            $otherColumns = TaskboardColumn::where('priority', '<', $oldPosition)
                ->where('priority', '>=', $newPosition)
                ->orderBy('priority', 'asc')
                ->get();

            foreach ($otherColumns as $column) {
                $pos = TaskboardColumn::where('priority', $column->priority)->first();
                $pos->priority = ($pos->priority + 1);
                $pos->save();
            }
        }

        $board->column_name = $request->column_name;
        $board->label_color = $request->label_color;
        $board->priority = $request->priority;
        $board->save();

        return Reply::redirect(route('member.taskboard.index'), __('messages.boardColumnSaved'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Task::where('board_column_id', $id)->update(['board_column_id' => 1]);

        $board = TaskboardColumn::findOrFail($id);

        $otherColumns = TaskboardColumn::where('priority', '>', $board->priority)
            ->orderBy('priority', 'asc')
            ->get();

        foreach ($otherColumns as $column) {
            $pos = TaskboardColumn::where('priority', $column->priority)->first();
            $pos->priority = ($pos->priority - 1);
            $pos->save();
        }

        TaskboardColumn::destroy($id);

        return Reply::dataOnly(['status' => 'success']);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function updateIndex(Request $request)
    {
        $taskIds = $request->taskIds;
        $boardColumnIds = $request->boardColumnIds;
        $priorities = $request->prioritys;

        if (!empty($taskIds)) {
            foreach ($taskIds as $key => $taskId) {
                if (!is_null($taskId)) {
                    $task = Task::findOrFail($taskId);
                    $task->board_column_id = $boardColumnIds[$key];
                    $task->column_priority = $priorities[$key];
                    $task->save();
                }
            }
        }
        return Reply::dataOnly(['status' => 'success']);
    }
}
