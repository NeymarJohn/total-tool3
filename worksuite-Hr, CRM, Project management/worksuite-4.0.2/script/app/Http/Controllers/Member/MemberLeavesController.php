<?php

namespace App\Http\Controllers\Member;

use App\EmployeeLeaveQuota;
use App\Helper\Reply;
use App\Http\Requests\Leaves\StoreLeave;
use App\Http\Requests\Leaves\UpdateLeave;
use App\Leave;
use App\LeaveType;
use App\Notifications\LeaveApplication;
use App\Notifications\NewLeaveRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MemberLeavesController extends MemberBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.leaves';
        $this->pageIcon = 'icon-logout';
        $this->middleware(function ($request, $next) {
            if (!in_array('leaves', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index()
    {
        $this->leaves = Leave::byUser($this->user->id);
        $this->leavesCount = Leave::byUserCount($this->user->id);
        $this->leaveTypes = LeaveType::byUser($this->user->id);
        $this->allowedLeaves = $this->user->leaveTypes->sum('no_of_leaves');
        $this->pendingLeaves = Leave::where('status', 'pending')
            ->where('user_id', $this->user->id)
            ->orderBy('leave_date', 'asc')
            ->get();
        $this->employeeLeavesQuota = $this->user->leaveTypes;

        return view('member.leaves.index', $this->data);
    }

    public function create()
    {
        $this->leaveTypes = EmployeeLeaveQuota::with('leaveType')
            ->where('no_of_leaves', '>', 0)
            ->where('user_id', $this->user->id)
            ->get();
        $this->leaves = Leave::where('user_id', $this->user->id)
            ->select('leave_date')
            ->where('status', '<>', 'rejected')
            ->where('duration', '<>', 'half day')
            ->groupBy('leave_date')
            ->get();
        return view('member.leaves.create', $this->data);
    }

    public function store(StoreLeave $request)
    {
        if ($request->duration == 'multiple') {
            session(['leaves_duration' => 'multiple']);

            $dates = explode(',', $request->multi_date);
            foreach ($dates as $date) {
                $leave = new Leave();
                $leave->user_id = $request->user_id;
                $leave->leave_type_id = $request->leave_type_id;
                $leave->duration = $request->duration;
                $leave->leave_date = Carbon::createFromFormat($this->global->date_format, $date)->format('Y-m-d');
                $leave->reason = $request->reason;
                $leave->status = $request->status;
                $leave->save();
                session()->forget('leaves_duration');
            }
        } else {
            $leave = new Leave();
            $leave->user_id = $request->user_id;
            $leave->leave_type_id = $request->leave_type_id;
            $leave->duration = $request->duration;
            $leave->leave_date = Carbon::createFromFormat($this->global->date_format, $request->leave_date)->format('Y-m-d');
            $leave->reason = $request->reason;
            $leave->status = $request->status;
            $leave->save();
        }

        return Reply::redirect(route('member.leaves.index'), __('messages.leaveApplySuccess'));
    }

    public function show($id)
    {
        $this->leave = Leave::findOrFail($id);
        return view('member.leaves.show', $this->data);
    }

    public function edit($id)
    {
        $this->leaveTypes = EmployeeLeaveQuota::with('leaveType')
            ->where('no_of_leaves', '>', 0)
            ->where('user_id', $this->user->id)
            ->get();
        $this->leave = Leave::findOrFail($id);
        $view = view('member.leaves.edit', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function update(UpdateLeave $request, $id)
    {
        $leave = Leave::findOrFail($id);
        $leave->user_id = $request->user_id;
        $leave->leave_type_id = $request->leave_type_id;
        $leave->leave_date = Carbon::createFromFormat($this->global->date_format, $request->leave_date)->format('Y-m-d');
        $leave->reason = $request->reason;
        $leave->status = $request->status;
        $leave->save();

        return Reply::redirect(route('member.leaves.index'), __('messages.leaveAssignSuccess'));
    }

    public function destroy($id)
    {
        Leave::destroy($id);
        return Reply::success('messages.leaveDeleteSuccess');
    }

    public function leaveAction(Request $request)
    {
        Leave::destroy($request->leaveId);

        return Reply::success(__('messages.leaveStatusUpdate'));
    }

    public function data()
    {
        $leaves = Leave::with('user', 'type')
            ->where('user_id', $this->user->id)
            ->orderBy('id', 'desc')
            ->get();
        return DataTables::of($leaves)
            ->addColumn('action', function ($row) {
                $action = '';

                $action .= '<a href="javascript:;" onclick="getEventDetail(' . $row->id . ')" class="btn btn-info btn-circle"
                      data-toggle="tooltip" data-original-title="View"><i class="fa fa-search" aria-hidden="true"></i></a>';

                if ($row->status == 'pending') {
                    $action .= '  <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-user-id="' . $row->id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }

                return $action;
            })
            ->addColumn('type', function ($row) {
                return ucfirst($row->type->type_name);
            })
            ->editColumn('leave_date', function ($row) {
                return Carbon::parse($row->leave_date)->format($this->global->date_format);
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'approved') {
                    return '<label class="label label-success">' . ucfirst($row->status) . '</label>';
                } elseif ($row->status == 'pending') {
                    return '<label class="label label-warning">' . ucfirst($row->status) . '</label>';
                } else {
                    return '<label class="label label-danger">' . ucfirst($row->status) . '</label>';
                }
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'status'])
            ->make(true);
    }
}
