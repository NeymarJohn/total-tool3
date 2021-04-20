<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\TimeLogReportDataTable;
use App\Helper\Reply;
use App\Project;
use App\ProjectTimeLog;
use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimeLogReportController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.timeLogReport';
        $this->pageIcon = 'ti-pie-chart';
    }

    public function index(TimeLogReportDataTable $dataTable)
    {

        $this->employees = User::allEmployees();
        $this->projects = Project::allProjects();
        $this->tasks = Task::all();
        $this->fromDate = Carbon::today()->subDays(30);
        $this->toDate = Carbon::today();

        $this->chartData = DB::table('project_time_logs');
        $this->chartData = $this->chartData->where('start_time', '>=', $this->fromDate)
            ->where('start_time', '<=', $this->toDate)
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get([
                DB::raw('DATE_FORMAT(start_time,\'%d/%M/%y\') as date'),
                DB::raw('FLOOR(sum(total_minutes/60)) as total_hours')
            ])
            ->toJSON();

        // return view('admin.reports.time-log.index', $this->data);
        return $dataTable->render('admin.reports.time-log.index', $this->data);
    }

    public function store(Request $request)
    {
        $fromDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
        $toDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
        $projectId = $request->projectId;
        $taskID = $request->taskID;

        $timeLog = ProjectTimeLog::select('start_time', DB::raw('DATE_FORMAT(start_time,\'%d/%M/%y\') as date'), DB::raw('FLOOR(sum(total_minutes/60)) as total_hours'))
            ->whereDate('start_time', '>=', $fromDate)
            ->whereDate('start_time', '<=', $toDate);

        if (!is_null($projectId)) {
            $timeLog =  $timeLog->where('project_time_logs.project_id', '=', $projectId);
        }

        if (!is_null($taskID)) {
            $timeLog =  $timeLog->where('project_time_logs.task_id', '=', $projectId);
        }

        $employee = $request->employee;
        if (!is_null($employee) && $employee !== 'all') {
            $timeLog->where('project_time_logs.user_id', $employee);
        }

        $timeLog = $timeLog->groupBy('date')
            ->orderBy('start_time', 'ASC')
            ->get()
            ->toJson();

        if (empty($timeLog)) {
            return Reply::error('No record found.');
        }
        return Reply::successWithData(__('messages.reportGenerated'), ['chartData' => $timeLog]);
    }
}
