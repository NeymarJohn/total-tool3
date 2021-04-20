<?php

namespace App\Http\Controllers\Member;

use App\Helper\Reply;
use App\SubTask;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\SubTask\StoreSubTask;

class MemberSubTaskController extends MemberBaseController
{

    public function __construct() {
        parent::__construct();
    }

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
    public function create(Request $request)
    {
        $this->taskID = $request->task_id;
        return view('member.sub_task.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubTask $request)
    {
        $subTask = new SubTask();
        $subTask->title = $request->name;
        $subTask->task_id = $request->taskID;
        $subTask->due_date = Carbon::createFromFormat($this->global->date_format, $request->due_date)->format('Y-m-d');
        $subTask->save();

        $this->subTasks = SubTask::where('task_id', $request->taskID)->get();
        $view = view('member.sub_task.show', $this->data)->render();

        return Reply::successWithData(__('messages.subTaskAdded'), ['view' => $view]);
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
        $this->subTask = SubTask::findOrFail($id);

        return view('admin.sub_task.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreSubTask $request, $id)
    {
        $subTask = SubTask::findOrFail($id);
        $subTask->title = $request->name;
        $subTask->task_id = $request->taskID;
        $subTask->due_date = Carbon::createFromFormat($this->global->date_format, $request->due_date)->format('Y-m-d');
        $subTask->save();

        $this->subTasks = SubTask::where('task_id', $request->taskID)->get();
        $view = view('member.sub_task.show', $this->data)->render();

        return Reply::successWithData(__('messages.subTaskUpdated'), ['view' => $view]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subTask = SubTask::findOrFail($id);
        SubTask::destroy($id);

        $this->subTasks = SubTask::where('task_id', $subTask->task_id)->get();
        $view = view('member.sub_task.show', $this->data)->render();

        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function changeStatus(Request $request){
        $subTask = SubTask::findOrFail($request->subTaskId);
        $subTask->status = $request->status;
        $subTask->save();

        $this->subTasks = SubTask::where('task_id', $subTask->task_id)->get();
        $view = view('member.sub_task.show', $this->data)->render();

        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }
}
