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
        $leaveType->paid = $request->paid;
        $leaveType->no_of_leaves = $request->leave_number;
        $leaveType->save();

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
        $type->paid = $request->paid;
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
