<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\TemplateTasks\StoreTask;
use App\ProjectTemplate;
use App\ProjectTemplateTask;
use App\ProjectTemplateTaskUser;
use App\TaskCategory;
use App\Traits\ProjectProgress;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class ProjectTemplateTaskController extends AdminBaseController
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
     * @param StoreTask $request
     * @return array
     */
    public function store(StoreTask $request)
    {
        $task = new ProjectTemplateTask();
        $task->heading = $request->heading;
        if($request->description != ''){
            $task->description = $request->description;
        }
        // $task->user_id = $request->user_id;
        $task->project_template_id = $request->project_id;
        $task->project_template_task_category_id = $request->category_id;
        $task->priority = $request->priority;
        $task->save();

        foreach ($request->user_id as $key => $value) {
            ProjectTemplateTaskUser::create(
                [
                    'user_id' => $value,
                    'project_template_task_id' => $task->id
                ]
            );
        }

        return Reply::success(__('messages.templateTaskCreatedSuccessfully'));
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
        return view('admin.project-template.tasks.show', $this->data);
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
        $view = view('admin.project-template.tasks.edit', $this->data)->render();
        return Reply::dataOnly(['html' => $view]);
    }

    /**
     * @param StoreTask $request
     * @param $id
     * @return array
     */
    public function update(StoreTask $request, $id)
    {
        $task = ProjectTemplateTask::findOrFail($id);
        $task->heading = $request->heading;
        if($request->description != ''){
            $task->description = $request->description;
        }
        // $task->user_id = $request->user_id;
        $task->project_template_task_category_id = $request->category_id;
        $task->priority = $request->priority;
        $task->save();

        ProjectTemplateTaskUser::where('project_template_task_id', $task->id)->delete();
        foreach ($request->user_id as $key => $value) {
            ProjectTemplateTaskUser::create(
                [
                    'user_id' => $value,
                    'project_template_task_id' => $task->id
                ]
            );
        }

        return Reply::success(__('messages.templateTaskUpdatedSuccessfully'));
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
        ProjectTemplateTask::destroy($id);

        return Reply::success(__('messages.taskDeletedSuccessfully'));
    }

    /**
     * @param Request $request
     * @param null $templateId
     * @return mixed
     */
    public function data(Request $request, $templateId = null) {

        $tasks = ProjectTemplateTask::where('project_template_id', $templateId);

        $tasks->get();

        return DataTables::of($tasks)
            ->addColumn('action', function($row){
                return '<a href="javascript:;" class="btn btn-success btn-circle add-sub-task "
                      data-toggle="tooltip" data-task-id="'.$row->id.'" data-original-title="Add Sub Task"><i class="fa fa-plus" aria-hidden="true"></i></a>
                      &nbsp;&nbsp;<a href="javascript:;" class="btn btn-info btn-circle edit-task"
                      data-toggle="tooltip" data-task-id="'.$row->id.'" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                        &nbsp;&nbsp;<a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-task-id="'.$row->id.'" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
            })
            ->editColumn('heading', function($row){
                return '<a href="javascript:;" data-task-id="'.$row->id.'" class="show-task-detail">'.ucfirst($row->heading).'</a>';
            })

            ->rawColumns(['action', 'heading'])
            ->removeColumn('project_template_id')
            ->make(true);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function taskDetail($id)
    {
        $this->task = ProjectTemplateTask::with('projectTemplate', 'users_many')->findOrFail($id);
        return view('admin.project-template.tasks.task-detail', $this->data);
    }

}
