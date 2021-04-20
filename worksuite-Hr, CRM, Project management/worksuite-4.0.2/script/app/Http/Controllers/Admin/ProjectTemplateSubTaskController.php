<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\TemplateTasks\SubTaskStoreRequest;
use App\Http\Requests\TemplateTasks\StoreTask;
use App\ProjectTemplate;
use App\ProjectTemplateSubTask;
use App\ProjectTemplateTask;
use App\ProjectTemplateTaskUser;
use App\TaskCategory;
use App\Traits\ProjectProgress;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class ProjectTemplateSubTaskController extends AdminBaseController
{

    use ProjectProgress;

    public function __construct() {
        parent::__construct();
        $this->pageIcon = 'icon-layers';
        $this->pageTitle = 'app.menu.projectTemplateSubTask';
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

        return view('admin.project-template.sub-task.create-edit', $this->data);
    }

    /**
     * @param StoreTask $request
     * @return array
     */
    public function store(SubTaskStoreRequest $request)
    {
        foreach ($request->name as  $value) {
            if ($value){
                ProjectTemplateSubTask::firstOrCreate([
                    'title' => $value,
                    'project_template_task_id' => $request->taskID,
                ]);
            }
        }
        return Reply::success(__('messages.templateSubTaskCreatedSuccessfully'));
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
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function taskDetail($id)
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
     * @param StoreTask $request
     * @param $id
     * @return array
     */
    public function update(StoreTask $request, $id)
    {
       //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Delete task
        ProjectTemplateSubTask::destroy($id);

        return Reply::success(__('messages.templateSubTaskDeletedSuccessfully'));
    }

}
