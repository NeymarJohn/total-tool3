<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\LeaveReportDataTable;
use App\Leave;
use App\LeaveType;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LeaveReportController extends AdminBaseController
{
    /**
     * LeaveReportController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.leaveReport';
        $this->pageIcon = 'ti-pie-chart';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(LeaveReportDataTable $dataTable)
    {
        $this->employees = User::allEmployees();
        $this->fromDate = Carbon::today()->subDays(30);
        $this->toDate = Carbon::today();

        return $dataTable->render('admin.reports.leave.index', $this->data);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $id)
    {
        $this->modalHeader = 'approved';
        $this->leave_types = LeaveType::with(['leaves' => function ($query) use ($request, $id) {
            if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
                $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
                $query->where(DB::raw('DATE(leaves.`leave_date`)'), '>=', $startDate);
            }
            if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
                $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
                $query->where(DB::raw('DATE(leaves.`leave_date`)'), '<=', $endDate);
            }
            $query->where('status', 'approved')->where('user_id', $id);
        }])->get();

        $leaves = Leave::join('leave_types', 'leave_types.id', '=', 'leaves.leave_type_id')
            ->select('leave_types.type_name', 'leaves.leave_date', 'leaves.reason', 'leaves.duration')
            ->where('leaves.status', 'approved')
            ->where('leaves.user_id', $id);

        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $leaves = $leaves->where(DB::raw('DATE(leaves.`leave_date`)'), '>=', $startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            $leaves = $leaves->where(DB::raw('DATE(leaves.`leave_date`)'), '<=', $endDate);
        }

        $leaves = $leaves->get();

        $this->leaves = $leaves;

        return view('admin.reports.leave.leave-detail', $this->data);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pendingLeaves(Request $request, $id)
    {
        $this->modalHeader = 'pending';

        $this->leave_types = LeaveType::with(['leaves' => function ($query) use ($request, $id) {
            if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
                $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
                $query->where(DB::raw('DATE(`leave_date`)'), '>=', $startDate);
            }
            if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
                $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
                $query->where(DB::raw('DATE(`leave_date`)'), '<=', $endDate);
            }
            $query->where('status', 'pending')->where('user_id', $id);
        }])->get();

        $leaves = Leave::join('leave_types', 'leave_types.id', '=', 'leaves.leave_type_id')
            ->select('leave_types.type_name', 'leaves.leave_date', 'leaves.reason')
            ->where('leaves.status', 'pending')
            ->where('leaves.user_id', $id);

        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $leaves = $leaves->where(DB::raw('DATE(leaves.`leave_date`)'), '>=', $startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            $leaves = $leaves->where(DB::raw('DATE(leaves.`leave_date`)'), '<=', $endDate);
        }

        $leaves = $leaves->get();

        $this->leaves = $leaves;


        return view('admin.reports.leave.leave-detail', $this->data);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function upcomingLeaves(Request $request, $id)
    {
        $this->modalHeader = 'upcoming';

        $this->leave_types = LeaveType::with(['leaves' => function ($query) use ($request, $id) {
            if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
                $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
                $query->where(DB::raw('DATE(leaves.`leave_date`)'), '>=', $startDate);
            }
            if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
                $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
                $query->where(DB::raw('DATE(leaves.`leave_date`)'), '<=', $endDate);
            }
            $query->where('user_id', $id)->where(function ($q) {
                $q->where('leaves.status', 'pending')
                    ->orWhere('leaves.status', 'approved');
            })->where('leave_date', '>', Carbon::now()->format('Y-m-d'));
        }])->get();

        $leaves = Leave::join('leave_types', 'leave_types.id', '=', 'leaves.leave_type_id')
            ->select('leave_types.type_name', 'leaves.leave_date', 'leaves.reason')
            ->where(function ($q) {
                $q->where('leaves.status', 'pending')
                    ->orWhere('leaves.status', 'approved');
            })
            ->where('leaves.leave_date', '>', Carbon::now()->format('Y-m-d'))
            ->where('leaves.user_id', $id);

        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $leaves = $leaves->where(DB::raw('DATE(leaves.`leave_date`)'), '>=', $startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            $leaves = $leaves->where(DB::raw('DATE(leaves.`leave_date`)'), '<=', $endDate);
        }

        $leaves = $leaves->get();

        $this->leaves = $leaves;

        return view('admin.reports.leave.leave-detail', $this->data);
    }

    public function export($id, $startDate = null, $endDate = null)
    {
        $startDate = Carbon::parse($startDate)->toDateString();
        $endDate = Carbon::parse($endDate)->toDateString();

        $employees = User::find($id);
        $rows = Leave::join('leave_types', 'leave_types.id', '=', 'leaves.leave_type_id')
            ->where('leaves.user_id', $id)
            ->select(
                'leave_types.type_name',
                'leaves.leave_date',
                'leaves.reason',
                'leaves.status',
                'leaves.reject_reason'
            );

        if ($startDate !== null && $startDate != 'null' && $startDate != '') {
            $rows = $rows->where(DB::raw('DATE(leaves.`leave_date`)'), '>=', $startDate);
        }

        if ($endDate !== null && $endDate != 'null' && $endDate != '') {
            $rows = $rows->where(DB::raw('DATE(leaves.`leave_date`)'), '<=', $endDate);
        }


        $rows = $rows->get();

        // Initialize the array which will be passed into the Excel
        // generator.
        $exportArray = [];

        // Define the Excel spreadsheet headers
        $exportArray[] = ['Leave Type', 'Date', 'Reason', 'Status', 'Reject Reason'];

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($rows as $row) {
            $exportArray[] = $row->toArray();
        }

        // Generate and return the spreadsheet
        Excel::create($employees->name . ' Leaves', function ($excel) use ($employees, $exportArray) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle($employees->name . ' Leaves');
            $excel->setCreator('Worksuite')->setCompany($this->companyName);
            $excel->setDescription('Leaves file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($exportArray) {
                $sheet->fromArray($exportArray, null, 'A1', false, false);

                $sheet->row(1, function ($row) {

                    // call row manipulation methods
                    $row->setFont(array(
                        'bold' => true
                    ));
                });
            });
        })->download('xlsx');
    }
}
