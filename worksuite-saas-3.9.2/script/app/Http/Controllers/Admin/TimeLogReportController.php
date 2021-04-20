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
        $this->middleware(function ($request, $next) {
            abort_if(!in_array('reports', $this->user->modules),403);
            return $next($request);
        });
    }

    public function index(TimeLogReportDataTable $dataTable)
    {

        if (!request()->ajax()) {
            $this->employees = User::allEmployees();
            $this->projects = Project::all();
            $this->tasks = Task::all();
            $this->fromDate = Carbon::today()->subDays(30);
            $this->toDate = Carbon::today();

            $this->chartData = DB::table('project_time_logs');

            $this->chartData = $this->chartData->whereDate('start_time', '>=', $this->fromDate)
                ->whereDate('start_time', '<=', $this->toDate)
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get([
                    DB::raw('DATE_FORMAT(start_time,\'%d/%M/%y\') as date'),
                    DB::raw('FLOOR(sum(total_minutes/60)) as total_hours')
                ])
                ->toJSON();
        }

        return $dataTable->render('admin.reports.time-log.index', $this->data);
    }

    public function store(Request $request)
    {

        $fromDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
        $toDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();

        $projectId = $request->projectId;
        $employeeId = $request->employeeID;

        $timeLog = ProjectTimeLog::select('start_time', DB::raw('DATE_FORMAT(start_time,\'%d/%M/%y\') as date'), DB::raw('FLOOR(sum(total_minutes/60)) as total_hours'))
            ->whereDate('start_time', '>=', $fromDate)
            ->whereDate('start_time', '<=', $toDate);

        if (!is_null($projectId)) {
            $timeLog =  $timeLog->where('project_time_logs.project_id', '=', $projectId);
        }
        if (!is_null($employeeId) && $employeeId !== 'all') {
            $timeLog =  $timeLog->where('project_time_logs.user_id', '=', $employeeId);
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
