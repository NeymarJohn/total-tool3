<?php

namespace App\Http\Controllers\Member;

use App\Helper\Reply;
use App\Http\Requests\TemplateTasks\StoreTask;
use App\ProjectTemplate;
use App\ProjectTemplateTask;
use App\TaskCategory;
use App\Traits\ProjectProgress;


class ProjectTemplateTaskController extends MemberBaseController
{

    use ProjectProgress;

    public function __construct() {
        parent::__construct();
        $this->pageIcon = 'icon-layers';
        $this->pageTitle = 'app.menu.projectTemplateTask';
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTask $request)
    {
        $task = new ProjectTemplateTask();
        $task->heading = $request->heading;
        if($request->description != ''){
            $task->description = $request->description;
        }
        $task->project_template_task_category_id = $request->category_id;
        $task->project_template_id = $request->project_id;
        $task->priority = $request->priority;
        $task->save();

        // Sync task users
        $task->users_many()->sync($request->user_id);

        $this->project = ProjectTemplate::findOrFail($task->project_template_id);
        $view = view('member.project-template.tasks.task-list-ajax', $this->data)->render();

        return Reply::successWithData(__('messages.templateTaskCreatedSuccessfully'), ['html' => $view]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->project = ProjectTemplate::findOrFail($id);
        $this->categories = TaskCategory::all();
        return view('member.project-template.tasks.show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->task = ProjectTemplateTask::findOrFail($id);
        $this->categories = TaskCategory::all();
        $view = view('member.project-template.tasks.edit', $this->data)->render();
        return Reply::dataOnly(['html' => $view]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTask $request, $id)
    {
        $task = ProjectTemplateTask::findOrFail($id);
        $task->heading = $request->heading;
        if($request->description != ''){
            $task->description = $request->description;
        }
        $task->project_template_task_category_id = $request->category_id;
        $task->priority = $request->priority;
        $task->save();
        
        // Sync task users
        $task->users_many()->sync($request->user_id);

        $this->project = ProjectTemplate::findOrFail($task->project_template_id);

        $view = view('member.project-template.tasks.task-list-ajax', $this->data)->render();

        return Reply::successWithData(__('messages.templateTaskUpdatedSuccessfully'), ['html' => $view]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
