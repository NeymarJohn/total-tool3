<?php

namespace App\Http\Controllers\Member;

use App\DataTables\Member\LabelDataTable;
use App\Helper\Reply;
use App\Http\Requests\Admin\TaskLabel\StoreRequest;
use App\Http\Requests\Admin\TaskLabel\UpdateRequest;
use App\TaskLabel;
use App\TaskLabelList;

class TaskLabelController extends MemberBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = 'fa fa-file';
        $this->pageTitle = 'app.menu.taskLabel';
        $this->middleware(function ($request, $next) {
            if (!in_array('tasks', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index(LabelDataTable $dataTable)
    {
        return $dataTable->render('member.task-label.index', $this->data);
    }

    public function create()
    {
        return view('member.task-label.create', $this->data);
    }

    public function store(StoreRequest $request)
    {
        $taskLabel = new TaskLabelList();
        $this->storeUpdate($request, $taskLabel);
        return Reply::redirect(route('member.task-label.index'), __('messages.taskLabel.addedSuccess'));
    }

    public function edit($id)
    {
        $this->taskLabel = TaskLabelList::find($id);
        return view('member.task-label.edit', $this->data);
    }

    public function update(UpdateRequest $request, $id)
    {
        $taskLabel = TaskLabelList::findOrFail($id);
        $this->storeUpdate($request, $taskLabel);

        return Reply::redirect(route('member.task-label.index'), __('messages.taskLabel.updatedSuccess'));
    }

    public function show($id)
    {
        //
    }

    private function storeUpdate($request, $taskLabel)
    {
        $taskLabel->label_name  = $request->label_name;
        $taskLabel->color       = $request->color;
        $taskLabel->description = $request->description;
        $taskLabel->save();

        return $taskLabel;
    }

    public function destroy($id)
    {
        TaskLabel::where('label_id', $id)->delete();
        TaskLabelList::destroy($id);

        return Reply::success(__('messages.taskLabel.deletedSuccess'));
    }
}
