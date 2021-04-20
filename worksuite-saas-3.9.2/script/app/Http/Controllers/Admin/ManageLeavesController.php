<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\Leaves\StoreLeave;
use App\Http\Requests\Leaves\UpdateLeave;
use App\Leave;
use App\LeaveType;
use App\Notifications\LeaveStatusApprove;
use App\Notifications\LeaveStatusReject;
use App\Notifications\LeaveStatusUpdate;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ManageLeavesController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.leaves';
        $this->pageIcon = 'icon-logout';
        $this->middleware(function ($request, $next) {
            abort_if(!in_array('leaves', $this->user->modules),403);
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
        $this->leaves = Leave::where('status', '<>', 'rejected')
            ->get();
        $this->pendingLeaves = Leave::where('status', 'pending')
            ->orderBy('leave_date', 'asc')
            ->get();
        return view('admin.leaves.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->employees = User::allEmployees();
        $this->leaveTypes = LeaveType::all();
        return view('admin.leaves.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLeave $request)
    {
        if ($request->duration == 'multiple') {
            session(['leaves_duration' => 'multiple']);
            $dates = explode(',', $request->multi_date);
            foreach ($dates as $date) {
                $this->storeLeave($request, $date);
                session()->forget('leaves_duration');
            }
        } else {
            $this->storeLeave($request, $request->leave_date);
        }

        return Reply::redirect(route('admin.leaves.index'), __('messages.leaveAssignSuccess'));
    }

    public function storeLeave($request, $date)
    {
        $leave = new Leave();
        $leave->user_id = $request->user_id;
        $leave->leave_type_id = $request->leave_type_id;
        $leave->duration = $request->duration;
        $leave->leave_date = Carbon::createFromFormat($this->global->date_format, $date)->format('Y-m-d');
        $leave->reason = $request->reason;
        $leave->status = $request->status;
        $leave->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->leave = Leave::findOrFail($id);
        return view('admin.leaves.show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->employees = User::allEmployees();
        $this->leaveTypes = LeaveType::all();
        $this->leave = Leave::findOrFail($id);
        $view = view('admin.leaves.edit', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLeave $request, $id)
    {
        $leave = Leave::findOrFail($id);
        $oldStatus = $leave->status;

        $leave->user_id = $request->user_id;
        $leave->leave_type_id = $request->leave_type_id;
        $leave->leave_date = Carbon::createFromFormat($this->global->date_format, $request->leave_date)->format('Y-m-d');
        $leave->reason = $request->reason;
        $leave->status = $request->status;
        $leave->save();

        return Reply::redirect(route('admin.leaves.index'), __('messages.leaveAssignSuccess'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Leave::destroy($id);
        return Reply::success('messages.leaveDeleteSuccess');
    }

    public function leaveAction(Request $request)
    {
        $leave = Leave::findOrFail($request->leaveId);
        $leave->status = $request->action;
        if (!empty($request->reason)) {
            $leave->reject_reason = $request->reason;
        }
        $leave->save();

        return Reply::success(__('messages.leaveStatusUpdate'));
    }

    public function rejectModal(Request $request)
    {
        $this->leaveAction = $request->leave_action;
        $this->leaveID = $request->leave_id;
        return view('admin.leaves.reject-reason-modal', $this->data);
    }

    public function allLeave()
    {
        $this->employees = User::allEmployees();
        $this->fromDate = Carbon::today()->subDays(7);
        $this->toDate = Carbon::today()->addDays(30);
        $this->pendingLeaves = Leave::where('status', 'pending')->count();
        return view('admin.leaves.all-leaves', $this->data);
    }

    public function data(Request $request, $employeeId = null)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $startDt = '';
        $endDt = '';

        if (!is_null($startDate)) {
            $startDate = Carbon::createFromFormat($this->global->date_format, $startDate)->format('Y-m-d');
            $startDt = 'DATE(leaves.`leave_date`) >= ' . '"' . $startDate . '"';
        }

        if (!is_null($endDate)) {
            $endDate = Carbon::createFromFormat($this->global->date_format, $endDate)->format('Y-m-d');
            $endDt = 'DATE(leaves.`leave_date`) <= ' . '"' . $endDate . '"';
        }

        $leavesList = Leave::select('leaves.id', 'users.name', 'leaves.leave_date', 'leaves.status', 'leave_types.type_name', 'leave_types.color', 'leaves.duration')
            ->where('leaves.status', '<>', 'rejected')
            ->whereRaw($startDt)
            ->whereRaw($endDt)
            ->join('users', 'users.id', '=', 'leaves.user_id')
            ->join('leave_types', 'leave_types.id', '=', 'leaves.leave_type_id');

        if ($employeeId != 0) {
            $leavesList->where('leaves.user_id', $employeeId);
        }

        $leaves = $leavesList->get();

        return DataTables::of($leaves)
            ->addColumn('employee', function ($row) {
                return ucwords($row->name);
            })
            ->addColumn('date', function ($row) {
                return $row->leave_date->format('Y-m-d');
            })
            ->addColumn('status', function ($row) {
                $label = $row->status == 'pending' ? 'warning' : 'success';
                return '<div class="label label-' . $label . '">' . $row->status . '</div>';
            })
            ->addColumn('leave_type', function ($row) {
                $type = '<div class="label-'.$row->color.' label">'.$row->type_name.'</div>';

                if ($row->duration == 'half day') {
                    $type.= ' <div class="label-inverse label">'.__('modules.leaves.halfDay').'</div>';
                }

                return $type;
            })
            ->addColumn('action', function ($row) {
                if ($row->status == 'pending') {
                    return '<a href="javascript:;"
                            data-leave-id=' . $row->id . ' 
                            data-leave-action="approved" 
                            class="btn btn-success btn-circle leave-action"
                            data-toggle="tooltip"
                            data-original-title="' . __('app.approved') . '">
                                <i class="fa fa-check"></i>
                            </a>
                            <a href="javascript:;" 
                            data-leave-id=' . $row->id . '
                            data-leave-action="rejected"
                            class="btn btn-danger btn-circle leave-action-reject"
                            data-toggle="tooltip"
                            data-original-title="' . __('app.reject') . '">
                                <i class="fa fa-times"></i>
                            </a>
                            
                            <a href="javascript:;"
                            data-leave-id=' . $row->id . '
                            class="btn btn-info btn-circle show-leave"
                            data-toggle="tooltip"
                            data-original-title="' . __('app.details') . '">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </a>';
                }

                return '<a href="javascript:;"
                        data-leave-id=' . $row->id . '
                        class="btn btn-info btn-circle show-leave"
                        data-toggle="tooltip"
                        data-original-title="' . __('app.details') . '">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </a>';
            })
            ->addIndexColumn()
            ->rawColumns(['date', 'status', 'leave_type', 'action'])
            ->make(true);
    }

    public function pendingLeaves()
    {
        $pendingLeaves = Leave::with('type', 'user', 'user.leaveTypes')->where('status', 'pending')
            ->orderBy('leave_date', 'asc')
            ->get();
        $this->pendingLeaves = $pendingLeaves->each->append('leaves_taken_count');

        return view('admin.leaves.pending', $this->data);
    }
}
