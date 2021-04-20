<?php

namespace App\Http\Controllers\Admin;

use App\Events\TaskUpdated;
use App\Helper\Reply;
use App\Http\Requests\TaskBoard\StoreTaskBoard;
use App\Http\Requests\TaskBoard\UpdateTaskBoard;
use App\Project;
use App\Task;
use App\TaskboardColumn;
use App\TaskCategory;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\TaskLabelList;

class AdminTaskboardController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'modules.tasks.taskBoard';
        $this->pageIcon = 'ti-layout-column3';
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
    public function index(Request $request)
    {
        $this->startDate = Carbon::now()->subDays(15)->format($this->global->date_format);
        $this->endDate = Carbon::now()->addDays(15)->format($this->global->date_format);
        $this->projects = Project::allProjects();
        $this->clients = User::allClients();
        $this->employees = User::allEmployees();
        $this->publicTaskboardLink = encrypt($this->companyName);
        $this->taskCategories = TaskCategory::all();
        $this->taskLabels  = TaskLabelList::all();

        if (request()->ajax()) {

            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();

            $this->boardEdit = (request()->has('boardEdit') && request('boardEdit') == 'false') ? false : true;
            $this->boardDelete = (request()->has('boardDelete') && request('boardDelete') == 'false') ? false : true;

            $boardColumns = TaskboardColumn::with(['tasks' => function ($q) use ($startDate, $endDate, $request) {
                $q->with(['subtasks', 'completedSubtasks', 'comments', 'users', 'project', 'label'])
                    ->leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
                    ->leftJoin('users as client', 'client.id', '=', 'projects.client_id')
                    ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
                    ->join('users', 'task_users.user_id', '=', 'users.id')
                    ->leftJoin('task_labels', 'task_labels.task_id', '=', 'tasks.id')
                    ->leftJoin('users as creator_user', 'creator_user.id', '=', 'tasks.created_by')
                    ->select('tasks.*')
                    ->groupBy('tasks.id');

                $q->where(function ($task) use ($startDate, $endDate) {
                    $task->whereBetween(DB::raw('DATE(tasks.`due_date`)'), [$startDate, $endDate]);

                    $task->orWhereBetween(DB::raw('DATE(tasks.`start_date`)'), [$startDate, $endDate]);
                });
                $q->whereNull('projects.deleted_at');

                if ($request->projectID != 0 && $request->projectID !=  null && $request->projectID !=  'all') {
                    $q->where('tasks.project_id', '=', $request->projectID);
                }

                if ($request->clientID != '' && $request->clientID !=  null && $request->clientID !=  'all') {
                    $q->where('projects.client_id', '=', $request->clientID);
                }

                if ($request->assignedTo != '' && $request->assignedTo !=  null && $request->assignedTo !=  'all') {
                    $q->where('task_users.user_id', '=', $request->assignedTo);
                }

                if ($request->assignedBY != '' && $request->assignedBY !=  null && $request->assignedBY !=  'all') {
                    $q->where('creator_user.id', '=', $request->assignedBY);
                }

                if ($request->category_id != '' && $request->category_id !=  null && $request->category_id !=  'all') {
                    $q->where('tasks.task_category_id', '=', $request->category_id);
                }
                if ($request->label_id != '' && $request->label_id !=  null && $request->label_id !=  'all') {
                    $q->where('task_labels.label_id', '=', $request->label_id);
                }
            }])->orderBy('priority', 'asc')->get();

            $this->boardColumns = $boardColumns;

            $this->startDate = $startDate;
            $this->endDate = $endDate;

            $view = view('admin.taskboard.board_data', $this->data)->render();
            return Reply::dataOnly(['view' => $view]);
        }
        return view('admin.taskboard.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.taskboard.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskBoard $request)
    {
        $maxPriority = TaskboardColumn::max('priority');

        $board = new TaskboardColumn();
        $board->column_name = $request->column_name;
        $board->label_color = $request->label_color;
        $board->slug        = str_slug($request->column_name, '_');
        $board->priority = ($maxPriority + 1);
        $board->save();

        return Reply::redirect(route('admin.taskboard.index'), __('messages.boardColumnSaved'));
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
        $view =  view('admin.taskboard.edit', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
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

        return Reply::redirect(route('admin.taskboard.index'), __('messages.boardColumnSaved'));
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

        $boardColumn = TaskboardColumn::where('slug', 'incomplete')->first();

        Task::where('board_column_id', $board->id)->update(['board_column_id' => $boardColumn->id]);

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

    public function updateIndex(Request $request)
    {

        $taskIds = $request->taskIds;
        $boardColumnIds = $request->boardColumnIds;
        $priorities = $request->prioritys;

        $board = TaskboardColumn::findOrFail($boardColumnIds[0]);

        if (isset($taskIds) && count($taskIds) > 0) {

            $taskIds = (array_filter($taskIds, function ($value) {
                return $value !== null;
            }));

            foreach ($taskIds as $key => $taskId) {
                if (!is_null($taskId)) {
                    $task = Task::findOrFail($taskId);
                    if ($board->slug == 'completed') {
                        $task->update(
                            [
                                'board_column_id' => $boardColumnIds[$key],
                                'completed_on' => Carbon::now()->format('Y-m-d'),
                                'column_priority' => $priorities[$key]
                            ]
                        );
                    } else {
                        $task->update(
                            [
                                'board_column_id' => $boardColumnIds[$key],
                                'column_priority' => $priorities[$key]
                            ]
                        );    
                    }
                }
            }

            if ($request->draggingTaskId == 0 && $request->draggedTaskId != 0) {
                // $this->logTaskActivity($request->draggedTaskId, $this->user->id, "statusActivity", $board->id);
                // $updatedTask = Task::findOrFail($request->draggedTaskId);
                // event(new TaskUpdated($updatedTask));
            }
            $this->triggerPusher('task-updated-channel', 'task-updated', ['user_id' => $this->user->id, 'task_id' => $request->draggingTaskId]);
        }



        return Reply::dataOnly(['status' => 'success']);
    }
}
