<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\SubTask;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\SubTask\StoreSubTask;
use App\Task;
use App\Events\SubTaskCompletedEvent;

class ManageSubTaskController extends AdminBaseController
{

    public function __construct()
    {
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
        return view('admin.sub_task.create', $this->data);
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
        $subTask->due_date = Carbon::createFromFormat('m/d/Y', $request->due_date)->format('Y-m-d');
        $subTask->save();

        $task = Task::findOrFail($request->taskID);
        $this->logTaskActivity($task->id, $this->user->id, "subTaskCreateActivity", $task->board_column_id, $subTask->id);

        $this->subTasks = SubTask::where('task_id', $request->taskID)->get();

        $view = view('admin.sub_task.show', $this->data)->render();

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
        $subTask->due_date = Carbon::createFromFormat('m/d/Y', $request->due_date)->format('Y-m-d');
        $subTask->save();

        $task = Task::findOrFail($request->taskID);
        $this->logTaskActivity($task->id, $this->user->id, "subTaskUpdateActivity", $task->board_column_id, $subTask->id);

        $this->subTasks = SubTask::where('task_id', $request->taskID)->get();
        $view = view('admin.sub_task.show', $this->data)->render();

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
        $view = view('admin.sub_task.show', $this->data)->render();

        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function changeStatus(Request $request)
    {
        $subTask = SubTask::findOrFail($request->subTaskId);
        $subTask->status = $request->status;
        $subTask->save();

        // event(new SubTaskCompletedEvent($subTask));
        
        $task = Task::findOrFail($subTask->task_id);
        $this->logTaskActivity($task->id, $this->user->id, "subTaskUpdateActivity", $task->board_column_id, $subTask->id);

        $this->subTasks = SubTask::where('task_id', $subTask->task_id)->get();
        $view = view('admin.sub_task.show', $this->data)->render();

        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }
}
