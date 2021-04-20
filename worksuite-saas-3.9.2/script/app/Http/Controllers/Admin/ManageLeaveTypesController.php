<?php

namespace App\Http\Controllers\Admin;

use App\EmployeeDetails;
use App\EmployeeLeaveQuota;
use App\Helper\Reply;
use App\Http\Requests\LeaveType\StoreLeaveType;
use App\LeaveType;
use Illuminate\Http\Request;

class ManageLeaveTypesController extends AdminBaseController
{
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
        $this->leaveTypes = LeaveType::all();
        return view('admin.leave-type.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLeaveType $request)
    {
        $leaveType = new LeaveType();
        $leaveType->type_name = $request->type_name;
        $leaveType->color = $request->color;
        $leaveType->no_of_leaves = $request->leave_number;
        $leaveType->save();

        if($request->all_employees == 'no'){
            $employees = EmployeeDetails::all();

            foreach ($employees as $key => $employee) {
                $employeeLeave = new EmployeeLeaveQuota();
                $employeeLeave->company_id = company()->id;
                $employeeLeave->user_id = $employee->user_id;
                $employeeLeave->leave_type_id = $leaveType->id;
                $employeeLeave->no_of_leaves = $leaveType->no_of_leaves;
                $employeeLeave->save();
            }
        }

        return Reply::success(__('messages.leaveTypeAdded'));
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->leaves < 0) {
            return Reply::error('messages.leaveTypeValueError');
        }
        $type = LeaveType::findOrFail($id);
        $type->no_of_leaves = $request->leaves;
        $type->save();

        return Reply::success(__('messages.leaveTypeAdded'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        LeaveType::destroy($id);
        return Reply::success(__('messages.leaveTypeDeleted'));
    }
}
