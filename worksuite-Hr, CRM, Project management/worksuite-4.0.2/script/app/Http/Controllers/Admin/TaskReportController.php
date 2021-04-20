<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\TaskReportDataTable;
use App\Helper\Reply;
use App\Project;
use App\Task;
use App\TaskboardColumn;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class TaskReportController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.taskReport';
        $this->pageIcon = 'ti-pie-chart';
    }

    public function index(TaskReportDataTable $dataTable)
    {
        if (!request()->ajax()) {
            $this->projects = Project::allProjects();
            $this->fromDate = Carbon::now($this->global->timezone)->subDays(30);
            $this->toDate = Carbon::now($this->global->timezone);
            $this->employees = User::allEmployees();

            $taskBoardColumn = TaskboardColumn::all();

            $incompletedTaskColumn = $taskBoardColumn->filter(function ($value, $key) {
                return $value->slug == 'incomplete';
            })->first();

            $completedTaskColumn = $taskBoardColumn->filter(function ($value, $key) {
                return $value->slug == 'completed';
            })->first();

            $this->clients = User::allClients();
            $this->taskBoardStatus = $taskBoardColumn;

            $taskStatus = array();
            foreach ($this->taskBoardStatus as $key => $value) {
                $totalTasks = Task::where(DB::raw('DATE(`due_date`)'), '>=', $this->fromDate->format('Y-m-d'))
                    ->where(DB::raw('DATE(`due_date`)'), '<=', $this->toDate->format('Y-m-d'));

                $totalTasks = $totalTasks->where('tasks.board_column_id', $value->id);
                $taskStatus[$value->slug] = [
                    'count' => $totalTasks->count(),
                    'label' => $value->column_name,
                    'color' => $value->label_color
                ];
            }
            $this->taskStatus = json_encode($taskStatus);


            $this->totalTasks = Task::where(DB::raw('DATE(`due_date`)'), '>=', $this->fromDate->format('Y-m-d'))
                ->where(DB::raw('DATE(`due_date`)'), '<=', $this->toDate->format('Y-m-d'))
                ->count();

            $this->completedTasks = Task::where(DB::raw('DATE(`due_date`)'), '>=', $this->fromDate->format('Y-m-d'))
                ->where(DB::raw('DATE(`due_date`)'), '<=', $this->toDate->format('Y-m-d'))
                ->where('tasks.board_column_id', $completedTaskColumn->id)
                ->count();

            $this->pendingTasks = Task::where(DB::raw('DATE(`due_date`)'), '>=', $this->fromDate->format('Y-m-d'))
                ->where(DB::raw('DATE(`due_date`)'), '<=', $this->toDate->format('Y-m-d'))
                ->where('tasks.board_column_id', '<>', $completedTaskColumn->id)
                ->count();
        }

        return $dataTable->render('admin.reports.tasks.index', $this->data);
    }

    public function store(Request $request)
    {
        $taskBoardColumn = TaskboardColumn::all();
        $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
        $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();

        $incompletedTaskColumn = $taskBoardColumn->filter(function ($value, $key) {
            return $value->slug == 'incomplete';
        })->first();

        $completedTaskColumn = $taskBoardColumn->filter(function ($value, $key) {
            return $value->slug == 'completed';
        })->first();

        $taskStatus = array();

        foreach ($taskBoardColumn as $key => $value) {
            $totalTasks = Task::leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
                ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
                ->where(DB::raw('DATE(`due_date`)'), '>=', $startDate)
                ->where(DB::raw('DATE(`due_date`)'), '<=', $endDate);

            if (!is_null($request->projectId)) {
                $totalTasks->where('project_id', $request->projectId);
            }

            if (!is_null($request->employeeId)) {
                $totalTasks->where('task_users.user_id', $request->employeeId);
            }

            if ($request->clientID != 'all') {
                $totalTasks->where('projects.client_id', $request->clientID);
            }

            if ($request->status != '' && $request->status !=  null && $request->status !=  'all') {
                $totalTasks->where('tasks.board_column_id', '=', $request->status);
            }

            $totalTasks = $totalTasks->where('tasks.board_column_id', $value->id);
            $taskStatus[$value->slug] = [
                'count' => $totalTasks->count(),
                'label' => $value->column_name,
                'color' => $value->label_color
            ];
        }
    //    return $taskStatus;


        $totalTasks = Task::leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
            ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->where(DB::raw('DATE(`due_date`)'), '>=', $startDate)
            ->where(DB::raw('DATE(`due_date`)'), '<=', $endDate);

        if (!is_null($request->projectId)) {
            $totalTasks->where('project_id', $request->projectId);
        }

        if (!is_null($request->employeeId)) {
            $totalTasks->where('task_users.user_id', $request->employeeId);
        }

        if ($request->clientID != 'all') {
            $totalTasks->where('projects.client_id', $request->clientID);
        }

        $totalTasks = $totalTasks->count();

        $completedTasks = Task::leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
            ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->where(DB::raw('DATE(`due_date`)'), '>=', $startDate)
            ->where(DB::raw('DATE(`due_date`)'), '<=', $endDate);

        if (!is_null($request->projectId)) {
            $completedTasks->where('project_id', $request->projectId);
        }

        if (!is_null($request->employeeId)) {
            $completedTasks->where('task_users.user_id', $request->employeeId);
        }


        if ($request->clientID != 'all') {
            $completedTasks->where('projects.client_id', $request->clientID);
        }


        $completedTasks = $completedTasks->where('tasks.board_column_id', $completedTaskColumn->id)->count();

        $pendingTasks = Task::leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
            ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->where(DB::raw('DATE(`due_date`)'), '>=', $startDate)
            ->where(DB::raw('DATE(`due_date`)'), '<=', $endDate);

        if (!is_null($request->projectId)) {
            $pendingTasks->where('project_id', $request->projectId);
        }

        if (!is_null($request->employeeId)) {
            $pendingTasks->where('task_users.user_id', $request->employeeId);
        }

        if ($request->clientID != 'all') {
            $pendingTasks->where('projects.client_id', $request->clientID);
        }

        $pendingTasks = $pendingTasks->where('tasks.board_column_id', '<>', $completedTaskColumn->id)->count();

        return Reply::successWithData(
            __('messages.reportGenerated'),
            ['pendingTasks' => $pendingTasks, 'completedTasks' => $completedTasks, 'totalTasks' => $totalTasks, 'taskStatus' => $taskStatus]
        );
    }


}
