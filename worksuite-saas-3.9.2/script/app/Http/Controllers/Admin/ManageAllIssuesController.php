<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Issue;
use App\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ManageAllIssuesController extends AdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.issues';
        $this->pageIcon = 'ti-alert';
    }

    public function index() {
        return view('admin.issues.index', $this->data);
    }

    public function data() {
        $issues = Issue::join('projects', 'projects.id', '=', 'issues.project_id')
            ->select('issues.id', 'issues.project_id', 'issues.description', 'projects.project_name', 'issues.status', 'issues.created_at')
            ->orderBy('issues.id', 'desc')
            ->get();

        return DataTables::of($issues)
            ->addColumn('action', function ($row) {
                 if($row->status == 'pending'){
                     return '<a href="javascript:;" class="btn btn-primary btn-xs btn-outline m-l-10 change-status" data-issue-id="'.$row->id.'" data-new-status="resolved">'.__('modules.issues.markResolved').'</a>';
                 }
                else{
                    return  '<a href="javascript:;" class="btn btn-primary btn-xs btn-outline m-l-10 change-status" data-issue-id="'.$row->id.'" data-new-status="pending">'.__('modules.issues.markPending').'</a>';
                }
            })
            ->editColumn('project_name', function ($row) {
                return '<a href="' . route('admin.projects.show', $row->project_id) . '">' . ucfirst($row->project_name) . '</a>';
            })
            ->editColumn('status', function ($row) {
                if($row->status == 'pending'){
                    return '<label class="label label-danger">'.__('modules.issues.pending').'</label>';
                }else{
                    return '<label class="label label-success">'.__('modules.issues.resolved').'</label>';
                }
            })
            ->editColumn(
                'created_at',
                function ($row) {
                    return Carbon::parse($row->created_at)->format($this->global->date_format);
                }
            )
            ->rawColumns(['project_name', 'action', 'status'])
            ->removeColumn('project_id')
            ->make(true);
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
