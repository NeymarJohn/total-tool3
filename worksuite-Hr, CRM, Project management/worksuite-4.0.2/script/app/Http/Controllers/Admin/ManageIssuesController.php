<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Issue;
use App\Project;
use Illuminate\Http\Request;

class ManageIssuesController extends AdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageTitle = "Project";
        $this->pageIcon = "layers";
    }

    public function show($id) {
        $this->project = Project::findOrFail($id);
        return view('admin.projects.issues.show', $this->data);
    }

    public function update(Request $request, $id) {
        $issue = Issue::findOrFail($id);
        $issue->status = $request->status;
        $issue->save();

        $this->project = Project::findOrFail($issue->project_id);
        $view = view('admin.projects.issues.ajax-list', $this->data)->render();

        return Reply::successWithData(__('messages.issueStatusChanged'), ['html' => $view]);
    }
}
